<?php
namespace App\Controllers;


use Nekrida\Auth\User;
use Nekrida\Locale\View;
use Nekrida\Core\Config;
use App\Modules\Certificate;
use Koks\mail;

class SignRequests extends Controller
{
    const JSON_REQUEST_TELEGRAM_SIGN = 'request.telegram.sign';
    const JSON_TELEGRAM_SIGN = 'telegram.sign';

    public function showAll() {
        $users = User::select(['u.id','u.surname','u.name','u.iin','u.login','u.mobile','company' => 'c.name'])
            ->join('companies')->onA('u.company','=','c.id')
            ->where("rights ->> '".self::JSON_REQUEST_TELEGRAM_SIGN."'",'=','1')
            ->query()->fetchAll(2);

        return View::render('admin/requests/users',[
            'users' => $users
        ]);
    }

    public function accept($id) {
        $user = User::getById($id);
        $rootPath = str_replace(
            '{root}',
            Config::dir(),
            Config::get('storage/local/certs/root')
        );
        $certPath = str_replace(
            ['{root}','{user}'],
            [$rootPath,$id],
            Certificate::$userCertificatePath
        );
        Certificate::generateByUser($id, $rootPath);
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
        User::updateSet([])
            ->setRaw('rights',
                '('.
                User::select(["rights::jsonb - '".self::JSON_REQUEST_TELEGRAM_SIGN."' || jsonb '{\"".self::JSON_TELEGRAM_SIGN."\":1}'"])->where('id','=',(int)$id)
                .')')
            ->where('id','=',(int)$id)
            ->query();
        $this->request->redirectByUrl('/requests/sign');
    }

    public function reject($id) {
        //Delete "request.telegram.sign" from user rights
        User::updateSet([])
            ->setRaw('rights',
                '('.
                User::select(["rights::jsonb - '".self::JSON_REQUEST_TELEGRAM_SIGN."'"])->where('id','=',(int)$id)
                .')')
            ->where('id','=',(int)$id)
            ->query();
        $this->request->redirectByUrl('/requests/sign');
    }
}
