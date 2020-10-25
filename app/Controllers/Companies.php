<?php
namespace App\Controllers;

use App\Modules\Company;
use App\Modules\Telegram;
use App\Modules\Users;
use Nekrida\Core\Storage;
use Nekrida\Locale\View;

class Companies extends Controller {
    public function add() {
        $id = Company::insertSet([
            'name' => $this->request->post('name'),
            'bin' => $this->request->post('bin'),
            'iban' => $this->request->post('iban'),
            'address' => $this->request->post('address'),
            'site' => $this->request->post('site'),
            'accountant_email' => $this->request->post('accountant-email'),
        ])->query();
        $this->request->redirectByUrl('/companies');
    }

    public function addBalance($id) {
        $sum = $this->request->post('sum');
        Company::updateSet([])
            ->setRaw('balance','balance + '.$sum)
            ->where('id','=',(int)$id)
            ->query();
        $this->request->redirectByUrl('/companies');
    }

    public function removeBalance($id) {
        $sum = $this->request->post('sum');
        Company::updateSet([])
            ->setRaw('balance','balance - '.$sum)
            ->where('id','=',(int)$id)
            ->query();
        $this->request->redirectByUrl('/companies');
    }

    public function bill($id) {
        $billFile = $this->request->files('bill');
        $billUrl = Storage::upload($billFile,['company' => $id],'main');

        Company::insertSet([
            'company' => (int)$id,
            'url' => $billUrl,
        ])->setRaw('bill_date','CURRENT_TIMESTAMP')
            ->dependentTable('bills')
            ->where('id','=',(int)$id)
            ->query();

        $this->request->redirectByUrl('/companies');
    }

    public function block($id) {
        $reason = $this->request->post('reason');
        Company::updateSet([
            'active'=>'0',
            //'block_reason' => $reason
        ])
            ->where('id','=',(int)$id)->query();
        $this->request->redirectByUrl('/companies');
    }

    public function unblock($id) {
        Company::updateSet(['active'=>'1'])->where('id','=',(int)$id)->query();
        $this->request->redirectByUrl('/companies');
    }

    public function delete() {
        $id = $this->request->post('id');
        Company::deleteById($id);
        return $this->showAll();
    }

    public function edit($id) {
        Company::updateSet([
            'name' => $this->request->post('name'),
            'bin' => $this->request->post('bin'),
            'iban' => $this->request->post('iban'),
            'address' => $this->request->post('address'),
            'site' => $this->request->post('site'),
            'accountant_email' => $this->request->post('accountant-email'),
        ])->where('id','=',(int)$id)
            ->query();
        $this->request->redirectByUrl('/companies');
    }

    public function showAdd() {
        return View::render('admin/companies/add');
    }

    public function showAll() {
    	$date = date('Y-m');

        $items = Company::select(['c.id','c.name','c.balance','c.active','cost' => 'sum(t.cost)'])
			->groupBy(['c.id','c.name','c.balance','c.active'])
			->leftJoin('users')->onA('u.company','=','c.id')
			->leftJoin('telegrams')->onA('t.author','=','u.id')
			->on('t.send_date','>',$date.'-01')
			->on('t.status','>',8)
			->orderBy(['c.name'])
			->where('c.confirmed','=','t')
			->query()
			->fetchAll(2);

        /*foreach ($items as $key => $item) {
            $items[$key]['decision_doc'] = Storage::url($items[$key]['decision_doc']);
            $items[$key]['const_doc'] = Storage::url($items[$key]['const_doc']);
        }*/

        return View::render('admin/companies/companies',[
            'companies'=>$items,
            'rights' => $this->rights,
        ]);
    }

    public function show($id=0)
    {
        $company = Company::select()->where('id','=',(int)$id)->query()->fetch(2);
        $users = Users::select()->where('company','=',(int)$id)->query()->fetchAll(2);
        foreach($users as $id => $user){
            if(!$company['active']){
                $users[$id]['active'] = false;
                $users[$id]['company-active'] = false;
            }else{
                $users[$id]['company-active'] = true;
            }
            $users[$id]["roleV"]="";
        }

        return View::render('admin/users/users',[
            'prefix'=> '/users',
            'header' => 'Users',
            'users'=>$users
        ]);
    }

    public function showEdit($id) {
        $company = Company::select()->where('id','=',(int)$id)->query()->fetch(2);
        return View::render('admin/companies/edit',['company'=>$company]);
    }

}
