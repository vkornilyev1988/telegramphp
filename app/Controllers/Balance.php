<?php
namespace App\Controllers;

//use App\Modules\Company;
use App\Modules\Company;
use App\Modules\Department;
use App\Modules\Transaction;
use Nekrida\Core\Config;
use Nekrida\Locale\View;
use Nekrida\Auth\User;
use Koks\WalletOne;

class Balance extends Controller
{

    private $WalletOne;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->WalletOne = new WalletOne(
            Config::get("merchants/WalletOne/merchantID"),
            Config::get("merchants/WalletOne/secretKey")
        );
    }

    public function add()
    {
        return View::render('/correspondent/balance/add');
    }
    public function new()
    {
        $company = null;
        $department = null;
        $user = User::getById($this->request->session('user'));
        if($user['department'] != null) $department = $user['department'];
        if($user['company'] != null) $company = $user['company'];
        $id = Transaction::insertSet([
            'user_id'=>(int)$this->request->session('user'),
            'sum'=>(double)$this->request->post('sum'),
            'department_id'=>$department,
            'company'=>$company,
            'status'=>0
            ])->query()->lastInsertId();
        $this->request->redirectByUrl('/profile/invoice/'.$id);
    }
    public function result()
    {
        $result = $this->WalletOne->result();
        if(!isset($result['id']))return;
        Transaction::updateSet(['status'=>(int)$result['status']])->where('id','=',(int)$result['id'])->query();
        $invoice = Transaction::select()->table('transactions')->where('id','=',(int)$result['id'])->query()->fetch(2);
        $user = User::getById($invoice['user_id']);
        if($user['department'] != null){
            $department = Department::select()->where('id','=',(int)$user['department'])->query()->fetch(2);
            Department::updateSet(['balance'=>(double)$department['balance']+$invoice['sum']])->where('id','=',(int)$user['department'])->query();
        }
        if($user['company'] != null){
            $company = Company::select()->where('id','=',(int)$user['company'])->query()->fetch(2);
            Company::updateSet(['balance'=>(double)$company['balance']+$invoice['sum']])->where('id','=',(int)$user['company'])->query();
        }

        print $result['text'];
    }
}
