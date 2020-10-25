<?php
namespace App\Controllers;

use App\Modules\Company;
use Nekrida\Core\Log;
use Nekrida\Core\Storage;
use Nekrida\Locale\View;
use Nekrida\Auth\User;

/**
 *
 */
class Auth extends Controller
{

    public function logout() {
        $this->request->clearSession();

        $this->request->redirectByUrl('/');
    }

    public function login() {
        $user = $this->request->post('login');
        $password = $this->request->post('password');

        $login = User::getIdAndGroupByLogin($user,$password);
        if (!$login) {
            Log::error('{# Wrong login #}');
            return View::render('login', ['error' => true]);
        }
        else {
            $this->request->setSession('user',(int)$login['id']);
            $this->request->setSession('fullName',$login['surname'].' '.$login['name']);
            $this->request->setSession('login',$user);
            $this->request->setSession('role',$login['role']);
            $this->request->redirectByUrl($this->request->url());
        }
        //TODO: remove
        $this->request->redirectByUrl('/');
    }


    public function showLogin() {
        return View::render('login');
    }

    public function register() {
        /*Required:
        login, surname, name, password, con-password, e-mail,
        org
        IF director, THEN assign-file
        ELSE position, dir[surname], dir[name],

        IF can-sign THEN iin

        */
        $password = $this->request->post('password');
        $confirm = $this->request->post('con-password');

        if ($password !== $confirm)
            return false;
        $password = password_hash($password, PASSWORD_BCRYPT);

        if ($this->request->post('is-director'))
            $assignFile = Storage::upload($this->request->files('assign-file'),[],'main');
        else
            $assignFile = Storage::upload($this->request->files('allow-file'),[],'main');

        $constDocs = Storage::upload($this->request->files('const-documents'),[],'main');
        $decision = Storage::upload($this->request->files('decision'),[],'main');


        $company = Company::insertSet([
            'name' => $this->request->post('org/name'),
            'bin' => $this->request->post('org/bin'),
            'iban'=> $this->request->post('org/iban'),
            'address' => $this->request->post('org/address'),
            'site' => $this->request->post('org/site'),
            'accountant_email' => $this->request->post('org/accountant-email'),
            'dir_surname' => $this->request->post('is-director') ? $this->request->post('surname') :$this->request->post('director/surname'),
            'dir_name' => $this->request->post('is-director') ? $this->request->post('name') : $this->request->post('director/name'),
            'dir_patronym' => $this->request->post('is-director') ? $this->request->post('patronym') : $this->request->post('director/patronym'),
            'const_doc' => $constDocs ?: null,
            'decision_doc' => $decision ?: null,
        ])->query()->lastInsertId();

        User::insertSet([
            'login' => $this->request->post('login'),
            'surname' => $this->request->post('surname'),
            'name' => $this->request->post('name'),
            'patronym' => $this->request->post('patronym'),
            'iin' => $this->request->post('iin'),
            'password' => $password,
            'email' => $this->request->post('email'),
            'mobile' => $this->request->post('mobile'),
            'work_phone' => $this->request->post('work'),
            'is_head' => 't',
            'role' => 1,
            'rights' => '{"request.telegram.sign": 1}',
            'position' => $this->request->post('is-director') ? '{# Director #}' : $this->request->post('position'),
            'company' => (int)$company,
            'assign_doc' => $assignFile ?: null
        ])->query();

        Log::success('{# #}');
        $this->request->redirectByUrl('/');

        return '';
    }

    public function showRegister() {
        return View::render('register');
    }
}
