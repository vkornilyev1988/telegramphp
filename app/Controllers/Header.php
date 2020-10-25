<?php
namespace App\Controllers;


use Nekrida\Locale\View;
use Nekrida\Auth\User;
use Nekrida\Database\Query;
use Nekrida\Log\Web;

class Header extends Controller
{
    public function header($content,$contentParameters = [])
    {
        $user = User::getById($this->request->session('user'));
        $balance = 0;
        if($user['company'] != null){
            $company = Query::select()
                ->table('companies')
                ->where('id','=',(int)$user['company'])
                ->query()->fetch(2);
            $balance = $company['balance'];
        }
        if($user['department'] != null){
            $department = Query::select()
                ->table('departments')
                ->where('id','=',(int)$user['department'])
                ->query()->fetch(2);
            $balance = $department['balance'];
        }
        return View::view('header',[
            'content'=>$content,
            'fromContent' =>$contentParameters,
            'login' => $this->request->session('fullName'),
            'role' => $this->request->session('role'),
            'balance' => $balance,
            'isHead' => $user['is_head'],
            'rights' => $this->rights,
            'alerts' => Web::getMyAlerts($this->request),
            
        ]);
    }
}
