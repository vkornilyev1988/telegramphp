<?php


namespace App\Controllers;


use App\Modules\Certificate;
use Koks\mail;
use Nekrida\Auth\User;
use Nekrida\Core\Config;
use Nekrida\Locale\View;

class ClientCertificates extends Controller
{
    private $path;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->path = str_replace(
            '{root}',
            Config::dir(),
            Config::get('storage/local/certs/root')
        );
    }

    public function generate() {
        $userId = $this->request->post('user');
		$user = User::getById($userId);
        $days = (int)$this->request->post('days') ?: 365; //one year
        Certificate::recallByUser($userId);
        Certificate::generateByUser($userId, $this->path, $days);

		$rootPath = str_replace(
			'{root}',
			Config::dir(),
			Config::get('storage/local/certs/root')
		);

		$certPath = str_replace(
			['{root}','{user}'],
			[$rootPath,$userId],
			Certificate::$userCertificatePath
		);
		$mail = new mail(
			Config::get('mail/smtp/host'),
			Config::get('mail/smtp/port'),
			Config::get('mail/smtp/user'),
			Config::get('mail/smtp/pass'),
			Config::get('mail/smtp/from'),
			Config::get('mail/smtp/name')
		);
		$mail->send(
			$user['login'],
			Config::get('mail/subject/accept-sign'),
			View::view('/mail/accept-sign',['user'=>$user]),
			"text/html",
			"SIGN_TELEGRAM.crt",
			file_get_contents($certPath)
		);

        $this->request->redirectByUrl('/certificates/clients');
    }

    public function recall($id) {
        Certificate::updateSet([
            'status' => 'f'
        ])->where('id','=',(int)$id)
            ->query();
        $this->request->redirectByUrl('/certificates/clients');
    }

    public function showAdd() {

    }

    public function showAll() {
        $certificates = Certificate::select(['c.id','c.start_date','c.end_date','c.status',
            'u.surname','u.name','company' => 'c1.name'])
            ->join('users')->onA('u.id','=','c.user')
            ->join('companies')->onA('c1.id','=','u.company')
            ->whereA('user','IS NOT','NULL')
			->where('c.status','=','t')
            ->query()->fetchAll(2);

        $users = User::select(['u.id','u.surname','u.name','company' => 'c.name','u.iin'])
            ->join('companies')->onA('c.id','=','u.company')
            ->query()->fetchAll(2);
        return View::render('admin/certificates/client',[
            'certificates' => $certificates,
            'users' => $users
        ]);
    }
}