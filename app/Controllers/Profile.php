<?php
namespace App\Controllers;

use App\Modules\Telegram;
use App\Modules\Transaction;
use Nekrida\Locale\View;
use Nekrida\Auth\User;
use App\Modules\Company;
use Nekrida\Database\Query;
use Nekrida\Core\Config;
use Koks\WalletOne;

class Profile extends Controller
{
    private $WalletOne;

    public function show()
    {
        $user = $this->request->cache('user');
        $transactions = Transaction::select([
            'id',
            'create_date'=>'extract(epoch from create_date)',
            'sum',
            'status'
            ])->orderBy(['id' => 'desc']);
        $company = Query::select()->table('companies')->where('id','=',(int)$user['company'])->query()->fetch(2);
        $departments = Query::select()
            ->table('departments')
            ->where('company','=',(int)$company['id'])
            ->query()->fetchAll(2);
        $balance = $company['balance'];
        $transactions->where('company','=',(int)$company['id']);
        if($user['department'] != null){
            $department = Query::select()->table('departments')->where('id','=',(int)$user['department'])->query()->fetch(2);
            $balance = $department['balance'];
            $transactions->where('department_id','=',(int)$department['id']);
        } else
            $department = [];
        $transactions = $transactions->query()->fetchAll(2);

        $now = date('Y-m-d h:i');
        $lastWeek = date_sub(new \DateTime(),new \DateInterval('P7D'));
        $lastMonth = date_sub(new \DateTime(),new \DateInterval('P1M'));

        $telegrams = [
        	'week' => Telegram::select(['count(send_date)'])
				->where('send_date','>=',$lastWeek->format('Y-m-d h:i:s'))
				->where('author','=',$user['id'])
				->query()->fetch()[0],
			'month' => Telegram::select(['count(send_date)'])
				->where('send_date','>=',$lastMonth->format('Y-m-d h:i:s'))
				->where('author','=',$user['id'])
				->query()->fetch()[0],
		];
        $wordsCount = [
            'week' => Telegram::select(['sum(wordcount)'])
                ->where('send_date','>=',$lastWeek->format('Y-m-d h:i:s'))
                ->where('author','=',$user['id'])
                ->query()->fetch()[0],
            'month' => Telegram::select(['sum(wordcount)'])
                ->where('send_date','>=',$lastMonth->format('Y-m-d h:i:s'))
                ->where('author','=',$user['id'])
                ->query()->fetch()[0],
        ];


        return View::render('correspondent/profile/profile',[
            'user'=>$user,
            'company'=>$company,
            'departments' => $departments,
            'department'=>$department,
            'balance'=>$balance,
            'transactions'=>$transactions,
			'telegrams' => $telegrams,
            'wordsCount' => $wordsCount,
        ]);
    }
    public function invoices()
    {
        //$transactions = Company::getInvoices($this->request->session('user'));
		$user = $this->request->cache('user');
		$transactions = Transaction::select([
			'id',
			'create_date'=>'extract(epoch from create_date)',
			'sum',
			'status'
		])->orderBy(['id' => 'desc']);
		$company = Query::select()->table('companies')->where('id','=',(int)$user['company'])->query()->fetch(2);
		$transactions->where('company','=',(int)$company['id']);
		if($user['department'] != null){
			$department = Query::select()->table('departments')->where('id','=',(int)$user['department'])->query()->fetch(2);
			$balance = $department['balance'];
			$transactions->where('department_id','=',(int)$department['id']);
		} else
			$department = [];
		$transactions = $transactions->query()->fetchAll(2);
        return View::render('correspondent/profile/invoices',['transactions'=>$transactions]);
    }
    public function invoice($id)
    {
        $invoice = Query::select()->table('transactions')->where('id','=',(int)$id)->query()->fetch(2);
        $params = [];
        if($invoice['status']==0){
            $this->WalletOne = new WalletOne(
                Config::get("merchants/WalletOne/merchantID"),
                Config::get("merchants/WalletOne/secretKey")
            );
            $this->WalletOne->Pay($invoice['id'],$invoice['sum']);
            $params = $this->WalletOne->getParams();
        }

        return View::render('/correspondent/profile/invoice',['params'=>$params,'invoice'=>$invoice]);
    }
    public function changePassword()
    {
        return View::render('/correspondent/profile/changePassword');
    }
    public function setNewPassword()
    {
        $user = User::getById($this->request->session('user'));
        if(!password_verify($this->request->post('old-password'),$user['password'])){
            return View::render('/correspondent/profile/changePassword',['error'=>'old-password']);
        }
        if($this->request->post('new-password') !== $this->request->post('retry-new-password')){
            return View::render('/correspondent/profile/changePassword',['error'=>'new-password']);
        }
        User::updateSet(['password'=>password_hash($this->request->post('new-password'),PASSWORD_BCRYPT)])->where('id','=',(int)$this->request->session('user'))->query();
        return View::render('/correspondent/profile/changePassword',['success'=>true]);
    }
    public function balance($id)
    {
        $department = Query::select()->table('departments')->where('id','=',(int)$id)->query()->fetch();
        $company = Query::select()->table('companies')->where('id','=',(int)$department['company'])->query()->fetch();
        return View::render('/correspondent/profile/balance',['department'=>$department,'company'=>$company]);
    }
    public function setNewBalance($id)
    {
        Query::updateSet(['balance'=>$this->request->post('balance')])->table('departments')->where('id','=',(int)$id)->query();
        $department = Query::select()->table('departments')->where('id','=',(int)$id)->query()->fetch();
        $company = Query::select()->table('companies')->where('id','=',(int)$department['company'])->query()->fetch();
        return View::render('/correspondent/profile/balance',['department'=>$department,'company'=>$company,'success'=>true]);
    }
}
