<?php
namespace App\Controllers;

use App\Modules\Company;
use App\Modules\Destination;
use App\Modules\Department;
use App\Modules\TelegramLog;
use App\Modules\Transaction;
use Nekrida\Auth\User;
use Nekrida\Core\Config;
use Nekrida\Locale\View;
use App\Modules\Telegram;
use App\Modules\Certificate;

class Telegrams extends Controller
{
    protected static $statuses = [
        'correspondent' => [
            '1' => 'Saved',
            '2' => 'On sign',
            '3' => 'Deleted',
            '4' => 'Recalled',
            '5' => 'Returned',
            '6' => 'Signed',
            '7' => 'Sent',
            '8' => 'Accepted',
            '9' => 'Rejected',
            '10' => 'In telegraphist',
            '11' => 'Sent to receiver',
			'12' => 'Sent to receiver',
        ],
        'telegraphist' => [
            '7' => 'On check',
            '8' => 'Accepted',
            '9' => 'Rejected',
            '10' => 'On send',
            '11' => 'Sent',
			'12' => 'Confirmed',
        ]
    ];

    //CORRESPONDENT

    protected function countWords($text) {
    	$a = preg_replace('/[аАбБвВгГдДеЕёЁжЖзЗиИйЙкКлЛмМнНоОпПрРсСтТуУфФхХцЦчЧшШщЩъЪыЫьЬэЭюЮяЯA-z]/','a',$text);
    	$a = str_replace("\n",' ',$a);

		return preg_match_all('/\b[a-]+\b|\b[0-9.]+|\b[^\s\n]/',$a);
    }

    protected function countCost($words,$isUrgent) {
        $tariff = $isUrgent ? Config::get('telegrams/urgentCost') + Config::get('telegrams/regularCost') : Config::get('telegrams/regularCost');
        return $words * $tariff;
    }

    public function delete($id) {
        $me = $this->request->session('user');
        Telegram::updateSet(['status' => Telegram::STATUS_DELETED])
            ->where('id','=',(int)$id)
            ->query();
        TelegramLog::log($me,Telegram::STATUS_DELETED,$id);
    }

    public function deleteMany() {
        $ids = $this->request->post('ids');
        Telegram::updateSet(['status' => Telegram::STATUS_DELETED])
            ->whereA('id','IN','('.implode(',',$ids).')')
            ->query();
        $this->request->redirectByUrl('/telegrams');
    }

    public function recall($id) {
        $me = $this->request->session('user');
        Telegram::updateSet(['status' => Telegram::STATUS_SIGNED])->where('id','=',(int)$id)->query();
        TelegramLog::log($me,Telegram::STATUS_RECALLED,$id);

        $this->request->redirectByUrl('/telegrams');
    }

    public function return($id) {
        $me = $this->request->session('user');
        Telegram::updateSet(['status' => Telegram::STATUS_SAVED])
            ->where('id','=',(int)$id)
            ->query();
        TelegramLog::log($me,Telegram::STATUS_RETURNED,$id);
        $this->request->redirectByUrl('/telegrams');
    }

    public function save($id) {
        $this->saveTrait($id);
        $this->request->redirectByUrl('/telegrams');
    }

    protected function saveTrait($id = 0) {
        $message = $this->request->post('message');
        $wordsCount = $this->countWords($message);
        $isUrgent = $this->request->post('is-urgent') ? '1' : '0';

        $cost = $this->countCost($wordsCount,$isUrgent);
        if ($id && $id > 0) {
            $user = $this->request->cache('user');
            //if($user['role']!=1){
                Telegram::delete()->dependentTable('destinations')->where('telegram','=',(int)$id)->query();
                $sql = Telegram::insertColumns(['destination','telegram'])
                    ->prepareRow()
                    ->dependentTable('destinations');
                foreach($this->request->post('points') as $point){
                    $sql->query([$point,$id]);
                }
                //return $id;
            //}
			if ($user['role']==2)
				Telegram::updateSet([
					'message' => $message,
					'destination' => $this->request->post('destination'),
					'wordcount' => $wordsCount,
					'cost' => $cost
				])->where('id', '=', (int)$id)
					->query();
			else
				Telegram::updateSet([
					'message' => $message,
					//'1' and '0' mean TRUE and FALSE in PostgreSQL
					'is_urgent' => $isUrgent,
					'destination' => $this->request->post('destination'),
					'status' => Telegram::STATUS_SAVED,
					'wordcount' => $wordsCount,
					'cost' => $cost
				])->where('id', '=', (int)$id)
					->query();
        } else {
            $id = Telegram::insertSet([
                'message' => $this->request->post('message'),
                //'1' and '0' mean TRUE and FALSE in PostgreSQL
                'is_urgent' => $isUrgent,
                'destination' => $this->request->post('destination'),
                'status' => Telegram::STATUS_SAVED,
                'wordcount' => $wordsCount,
                'cost' => $cost,
                'author' => $this->request->session('user'),
            ])->query()->lastInsertId();
			$sql = Telegram::insertColumns(['destination','telegram'])
				->prepareRow()
				->dependentTable('destinations');
			foreach($this->request->post('points') as $point){
				$sql->query([$point,$id]);
			}
        }
        return $id;
    }

    public function search() {
        $search = '%'.$this->request->get('search').'%';
        $role = $this->request->session('role') == 1 ? "correspondent" : "telegraphist";
        $telegrams = Telegram::select(['t.id','t.number','t.status','t.message','t.wordcount','t.destination','t.sign_date','t.register_date','t.send_date','t.cost','t.status','destinations' => 'count(dt.destination)'])
            ->join('users')->onA('u.id','=','t.author')
            ->dependentLeftJoin('destinations')->onA('dt.telegram','=','t.id')
            ->groupBy(['t.id','t.number','t.status','t.message','t.wordcount','t.destination','t.sign_date','t.register_date','t.send_date','t.cost','t.status'])
            ->whereRaw("t.message LIKE :search OR number LIKE :search OR t.destination LIKE :search OR u.surname LIKE :search OR u.name LIKE :search");
        $telegrams = $telegrams->query([':search' => $search])->fetchAll(2);

        return View::render($role.'/telegrams/telegrams',[
            'searchString' => $this->request->get('search'),
            'telegrams' => $telegrams,
            'statuses' => self::$statuses[$role],
        ]);
    }

    public function send($id,$save = 0) {
        if ($save)
            $id = $this->saveTrait($id);

        $isSigned = Telegram::select(['sign'])
            ->where('id','=',(int)$id)
            ->query()->fetchColumn(0);
        if ($this->rights['telegram.sign']) {
            if ($isSigned)
                $this->sendToTelegraphist($id);
        } else {
            $this->sendToHead($id);
        }

        $this->request->redirectByUrl('/telegrams');
    }

    public function sendToHead($id) {
        Telegram::updateSet(['status' => Telegram::STATUS_ON_SIGN])->where('id','=',(int)$id)->query();
        return true;
    }

    public function sendToTelegraphist($id) {
        $me = (int)$this->request->session('user');

        //Remove 100Tg from
        //IF users.department != null
        //THEN from department
        //ELSE from company
        $depAndCo = User::select(['u.company','coBalance'=> 'c.balance','department', 'depBalance' => 'd.balance'])
            ->leftJoin('companies')->onA('c.id','=','u.company')
            ->leftJoin('departments')->onA('d.id','=','u.department')
            ->where('u.id','=',$me)
            ->query()->fetch(2);
        $cost = Config::get('telegrams/usageCost');
        if ($depAndCo['department']) {
            if ($depAndCo['depBalance'] < $cost)
                return false;
            Transaction::insertSet([
                'telegram_id' => (int)$id,
                'user_id' => $me,
                'department_id' => (int)$depAndCo['department'],
                'sum' => -(double)$cost,
                'status' => 1,
            ])->query();
            Department::updateSet([])->setRaw('balance', 'balance - ' . (double)$cost)
                ->where('id', '=', (int)$depAndCo['department'])
                ->query();
        } else {
            if ($depAndCo['coBalance'] < $cost)
                return false;
            Transaction::insertSet([
                'telegram_id' => (int)$id,
                'user_id' => $me,
                'company' => (int)$depAndCo['company'],
                'sum' => -(double)$cost,
                'status' => 1,
            ])->query();
            Company::updateSet([])->setRaw('balance','balance - '.(double)$cost)
                ->where('id','=',(int)$depAndCo['company'])
                ->query();
        }
        //Set responsible telegraphist
		$res = User::select(['u.id','telegrams' => 'count(t.id)'])
			->leftJoin('telegrams')->onA('t.acceptor','=','u.id')
				->on('t.status','=',Telegram::STATUS_SENT_TO_TELEGRAPHIST)
			->where('u.active','=','t')
			->where('role','=','2')
			->where("rights -> 'telegram.confirm'", 'IS',null)
			->groupBy(['u.id'])
			->orderBy(['count(t.id)'])
			->limit(1)
			->query()->fetch(2);

        Telegram::updateSet(['status' => Telegram::STATUS_SENT_TO_TELEGRAPHIST,'paid'=>'t','acceptor' => $res['id']])
            ->setRaw('send_date','NOW()')
            ->where('id','=',(int)$id)
            ->query();
        TelegramLog::log($me,Telegram::STATUS_SENT_TO_TELEGRAPHIST,$id);

        return true;
    }

    public function show($id) {
        $user = $this->request->cache('user');
        $telegram = Telegram::select(['t.id','t.type','t.number','destination','is_urgent','wordcount','cost','message','t.status','t.sign',
            'u.surname','u.name','u.patronym','u.mobile','u.work_phone','author' => 'u.id'
        ])  ->join('users')->onA('u.id','=','t.author')
            ->where('t.id','=',(int)$id)
            ->query()->fetch(2);

        if ($telegram['status'] == Telegram::STATUS_REJECTED) {
			$ext = Telegram::select(['reason'])
				->dependentTable('logs')
				->where('telegram', '=', (int)$id)
				->where('action', '=', Telegram::STATUS_REJECTED)
				->query()->fetch(2);
			$telegram['reason'] = $ext['reason'];
		}

        /*if (!$telegram)
            return View::render('errors/404');*/
        $role = $user['role'] == 1 ? "correspondent" : "telegraphist";

        $canSign = $user['role'] == 1 ? isset($this->rights['telegram.sign']) : isset($this->rights['telegram.send']);


        $points = [];
        //if($user['role'] != 1){
            $ponts = Destination::select()
                ->dependentLeftJoin('telegrams')
                ->onA('dt.destination','=','d.id')
                ->on('dt.telegram','=',(int)$id)
                ->query()->fetchAll(2);
            foreach($ponts as $point) {
                    $point['selected'] = !!$point['destination'];
                $points[] = $point;//['name'];
            }

            //$canSign = isset($user['rights']['telegram.send']);
            $canConfirm = isset($user['rights']['telegram.confirm']);
        //}
        return View::render($role.'/telegrams/show',[
            'telegram' => $telegram,
            'canSign' => $canSign,
            'canConfirm' => $canConfirm,
            'me' => $this->request->session('user'),
            'points' => $points,
            'statuses' => self::$statuses[$role],

			'disableSelect' => true
        ]);
    }

    public function showAllCorrespondent($status = 1) {
        $me = (int)$this->request->session('user');
        $myInfo = User::select(['company','department','is_head'])
            ->where('id','=',$me)
            ->query()->fetch(2);
        $sql = Telegram::select(['t.id','t.number','t.send_date','t.author','t.sign_date','t.register_date','t.destination','t.cost','t.wordcount','t.status','t.is_urgent','destinations' => 'count(dt.destination)','points'=>"array_to_string(array_agg(d.name), ', ')"])
                ->groupBy(['t.id','t.number','t.send_date','t.author','t.sign_date','t.register_date','t.destination','t.cost','t.wordcount','t.status','t.is_urgent'])
                ->dependentLeftJoin('destinations')->onA('dt.telegram','=','t.id')
				->leftJoin('destinations')->onA('dt.destination','=','d.id')
                ->where('status','<>',(int)Telegram::STATUS_DELETED);
        if (isset($this->rights['telegram.sign'])) {

            $sql->join('users')->onA('author', '=', 'u.id');
            if ($myInfo['department'])
                $sql->where('department', '=',(int)$myInfo['department']);
            else
                $sql->where('company','=',(int)$myInfo['company']);
        } else {
            $sql->where('author','=',(int)$this->request->session('user'));
        }

        $sql->orderBy(['status','is_urgent' => 'desc','t.id'=>'desc']);

        $sql->query();

        $telegrams = [];
        //foreach($telegramsR as $row) {
        while ($row = $sql->fetch(2)) {
            if ($row['status'] == 1 && $row['author'] != $me)
                continue;
            $telegrams[] = $row;
        }
//        }
        //$telegrams = $telegramsSQL->query()->fetchAll();
        return View::render('correspondent/telegrams/telegrams',[
            'telegrams'=>$telegrams,
            'status'=>$status,
            'statuses' => self::$statuses['correspondent']
        ]);
    }

    public function showAdd() {
        $telegram = User::select(['surname','name','patronym','mobile','work_phone'])
            ->where('id','=',(int)$this->request->session('user'))
            ->query()->fetch(2);
        $cost = Config::get('telegrams/regularCost');
        $costUrgent = Config::get('telegrams/urgentCost');
        $canSign = isset($this->rights['telegram.sign']);

        $points = Destination::selectAll(2);
        return View::render('correspondent/telegrams/add', [
            'telegram' => $telegram,
            'canSign' => $canSign,
            'points' => $points,
            'wordCost' => $cost,
            'wordCostUrgent' => $costUrgent
        ]);
    }

    public function showEdit($id) {
        $user = $this->request->cache('user');
        $telegram = Telegram::select(['t.id','t.number','destination','is_urgent','wordcount','cost','message','t.status',
            'u.surname','u.name','u.patronym','u.mobile','u.work_phone'
        ])  ->join('users')->onA('u.id','=','t.author')
            ->where('t.id','=',(int)$id)
            ->query()->fetch(2);
        $canSign = isset($this->rights['telegram.sign']);
        $cost = Config::get('telegrams/regularCost');
        $costUrgent = Config::get('telegrams/urgentCost');
        $role = $user['role'] == 1 ? "correspondent" : "telegraphist";


        $points = [];

        //if($user['role'] != 1){
            //IF "selected" has IS NOT NULL
            //THEN point is selected
            $points = Destination::select(['d.id','d.name','selected' => 'dt.destination'])
                ->dependentLeftJoin('telegrams')
                ->onA('dt.destination','=','d.id')
                //Don't move it to where
                ->on('dt.telegram','=',(int)$id)
                ->query()->fetchAll(2);
        //}
        return View::render($role.'/telegrams/edit', [
            'telegram' => $telegram,
            'canSign' => $canSign,
            'wordCost' => $cost,
            'wordCostUrgent' => $costUrgent,
            'points' => $points,
            'statuses' => self::$statuses[$role]
        ]);
    }

    //TODO implement getting signature from certificate
    protected function checkSignature($file,$user) {
        $private = str_replace(["{root}","{root}"],[Config::get('storage/local/certs/root'),Config::dir()],Certificate::$userCertificateKeyPath);
        $private = str_replace("{user}",$user,$private);
        return openssl_x509_check_private_key($file,file_get_contents($private));
    }

    public function sign($id) {
        $file = $this->request->files('signature');
        if (!$file)
            return false;

        //$password = $this->request->post('cert-pass');
        $me = (int)$this->request->session('user');
        //TODO: replace with getting signature from cert
        if ($this->checkSignature(file_get_contents($file['tmp_name']),$me)) {
            Telegram::updateSet(['status'=>Telegram::STATUS_SIGNED,'sign' => $me])
                ->setRaw('sign_date','NOW()')
                ->where('id','=',(int)$id)
                ->query();
            TelegramLog::log($me,Telegram::STATUS_SIGNED,$id);
        }

        $this->request->redirectByUrl('/telegram/{id}',[$id]);
    }

    //TELEGRAPHIST

    public function accept($id) {
		$me = $this->request->session('user');

        Telegram::updateSet(['status' => Telegram::STATUS_ACCEPTED,'acceptor' => $me])
            ->where('id','=',(int)$id)
            ->query();
        TelegramLog::log($this->request->session('user'),Telegram::STATUS_ACCEPTED,$id);

        $this->request->redirectByUrl('/telegram/{id}',[$id]);
    }

    public function reject($id) {
		$me = $this->request->session('user');

        $reason = $this->request->post('reason');
        Telegram::updateSet(['status' => Telegram::STATUS_REJECTED,'acceptor' => $me])
            ->where('id','=',(int)$id)
            ->query();
        TelegramLog::log($this->request->session('user'),Telegram::STATUS_REJECTED,$id,$reason);

        $this->request->redirectByUrl('/telegram/{id}',[$id]);
    }

    public function sendToVector($id) {
        Telegram::updateSet(['status' => Telegram::STATUS_SENT])
            ->where('id','=',(int)$id)
            ->query();
        TelegramLog::log($this->request->session('user'),Telegram::STATUS_SENT,$id);

        $this->request->redirectByUrl('/telegram/{id}',[$id]);
    }

	public function confirm($id) {
		$me = $this->request->session('user');

		Telegram::updateSet(['status' => Telegram::STATUS_CONFIRMED,'confirmer' => $me])
			->where('id','=',(int)$id)
			->query();
		TelegramLog::log($this->request->session('user'),Telegram::STATUS_CONFIRMED,$id);

		$this->request->redirectByUrl('/telegram/{id}',[$id]);
	}

    public function showAllTelegraphist($status = 7) {
        $user = $this->request->cache('user');
		$me = $this->request->session('user');
        $seesAll = isset($this->rights['telegram.confirm']);
        $sql = Telegram::select(['t.id','t.send_date','t.sign_date','company' => 'c.name','wordcount','cost',
            't.register_date', 't.is_urgent', 'destinations' => 'count(dt.destination)','points'=>"array_to_string(array_agg(d.name), ', ')",
            'uSurname'=>'u.surname','uName' => 'u.name', 'uPatronymic' => 'u.patronym'
        ])
			->groupBy(['t.id','t.send_date','t.sign_date','c.name','wordcount','cost','t.register_date', 't.is_urgent', 'u.surname','u.name', 'u.patronym'])
            ->join('users')->onA('u.id','=','t.author')
            ->join('companies')->onA('c.id','=','u.company')
			->dependentLeftJoin('destinations')->onA('dt.telegram','=','t.id')
			->leftJoin('destinations')->onA('dt.destination','=','d.id')
            ->where('status','=',(int)$status)
            ->orderBy(['is_urgent' => 'desc','id']);

        if (!$seesAll)
        	$sql->where('acceptor','=',(int)$me);

        $telegrams = $sql->query()->fetchAll(2);

        return View::render('telegraphist/telegrams/telegrams',[
            'user'=>$user,
            'telegrams'=>$telegrams,
            'status'=>$status,
            'statuses' => self::$statuses['telegraphist']
        ]);
    }

    public function showArchiveTelegraphist () {
		$user = $this->request->cache('user');
		$me = $this->request->session('user');
		$seesAll = isset($this->rights['telegram.confirm']);

		$sql = Telegram::select(['t.id','t.send_date','t.sign_date','company' => 'c.name','wordcount','cost', 't.status',
			't.register_date', 't.is_urgent', 'destinations' => 'count(dt.destination)','points'=>"array_to_string(array_agg(d.name), ', ')",
			'uSurname'=>'u.surname','uName' => 'u.name', 'uPatronymic' => 'u.patronym',
			'vSurname'=>'u1.surname','vName' => 'u1.name',
			'wSurname'=>'u2.surname','wName' => 'u2.name',
		])
			->groupBy(['t.id','t.send_date','t.sign_date','c.name','wordcount','cost','t.register_date', 't.is_urgent', 'u.surname','u.name', 'u.patronym','status','u1.surname','u1.name','u2.surname','u2.name'])
			->join('users')->onA('u.id','=','t.author')
			->join('companies')->onA('c.id','=','u.company')
			->leftJoin('users')->onA('u1.id','=','t.acceptor')
			->leftJoin('users')->onA('u2.id','=','t.confirmer')
			->dependentLeftJoin('destinations')->onA('dt.telegram','=','t.id')
			->leftJoin('destinations')->onA('dt.destination','=','d.id')
			->where('status','>=',7)
			->orderBy(['is_urgent' => 'desc','t.send_date']);

		if (!$seesAll)
			$sql->where('acceptor','=',(int)$me);

		$telegrams = $sql->query()->fetchAll(2);
		return View::render('telegraphist/telegrams/telegrams',[
			'user'=>$user,
			'telegrams'=>$telegrams,
			'status' => 9,
			'statuses' => self::$statuses['telegraphist']
		]);
	}

    public function export() {

    }

    public function formReport() {

    }


    //ADMINISTRATOR

    public function showCost() {
        $urgent = Config::get('telegrams/urgentCost');
        $regular = Config::get('telegrams/regularCost');
        $usage = Config::get('telegrams/usageCost');
        return View::render('admin/cost', [
            'urgent' => $urgent,
            'regular' => $regular,
            'usage' => $usage
        ]);
    }

    public function setCost() {
        $urgent = $this->request->post('urgent');
        $regular = $this->request->post('regular');
        $usage = $this->request->post('usage');
        Config::set('telegrams/urgentCost',$urgent);
        Config::set('telegrams/regularCost',$regular);
        Config::set('telegrams/usageCost',$usage);
        Config::export('telegrams');
        return $this->showCost();
    }

}
