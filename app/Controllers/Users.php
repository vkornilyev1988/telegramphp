<?php
namespace App\Controllers;


use App\Modules\Company;
use Nekrida\Auth\User;
use Nekrida\Database\Query;
use Nekrida\Locale\View;

class Users extends Controller
{
    protected function getValidatedRights($role,$rights) {
        if (!is_array($rights))
            return [];
        if (empty($rights))
            return false;
        $acceptableRights = Query::select(['rights'])
            ->table('roles')
            ->where('id','=',(int)$role)
            ->query()
            ->fetch(2);
        if (!$acceptableRights)
            //Wrong role
            return false;
        $acceptableRights = json_decode($acceptableRights['rights'], true);

        $total = array_intersect($rights,$acceptableRights);
        return $total;
    }

    protected function rightsValueToKey($rights) {
        $newRights = [];
        foreach ($rights as $right) {
            $newRights[$right] = 1;
        }
        return $newRights;
    }

    protected function parseRawRights($rights) {
        $parsedRights = [];
        foreach ($rights as $right) {
            foreach (explode(',',$right) as $item) {
                $parsedRights[] = $item;
            }
        }

        return $parsedRights;
    }

    public function activate($id) {
        User::updateSet(['active' => 't'])
            ->where('id','=',(int)$id)
            ->query();
        $this->request->redirectByUrl('/users');
    }

    public function add() {
        $password = password_hash($this->request->post('password'),PASSWORD_BCRYPT);

        $role = intval($this->request->post('main-role'));
        $role = $role < 0 ? 3 : $role;

        $rights = $this->getValidatedRights($role,$this->parseRawRights($this->request->post('rights') ?: []));
        $rights = $rights ? $this->rightsValueToKey($rights) : [];

        User::insertSet([
            'login' => $this->request->post('login'),
            'surname' => $this->request->post('surname'),
            'name' => $this->request->post('name'),
            'patronym' => $this->request->post('patronymic'),
            'password' => $password,
            'role' => $role,
            'active' => 't',
            'rights' => json_encode($rights),
            'mobile' => $this->request->post('mobile'),
            'work_phone' => $this->request->post('work-phone'),
            'iin' => $this->request->post('iin'),
            'position' => $this->request->post('position'),
            'company' => $role == 1 ? (int)$this->request->post('company') : null,
        ])->query();

        $this->request->redirectByUrl('/users');
    }

    public function block($id) {
        User::updateSet(['active' => 'f'])
            ->where('id','=',(int)$id)
            ->query();
        $this->request->redirectByUrl('/users');
    }

    public function delete() {
        $id = $this->request->post('id');
        User::deleteById($id);

        return $this->showAll();
    }

    public function edit($id) {
        $password = $this->request->post('password');

        $role = intval($this->request->post('main-role'));
        $role = $role < 0 ? 3 : $role;

        $rights = $this->getValidatedRights($role,$this->parseRawRights($this->request->post('rights') ?: []));
        $rights = $rights ? $this->rightsValueToKey($rights) : [];

        $sql = User::updateSet([
            'login' => $this->request->post('login'),
            'surname' => $this->request->post('surname'),
            'name' => $this->request->post('name'),
            'patronym' => $this->request->post('patronymic'),
            'role' => $role,
            'rights' => json_encode($rights),
            'mobile' => $this->request->post('mobile'),
            'work_phone' => $this->request->post('work-phone'),
            'iin' => $this->request->post('iin'),
            'position' => $this->request->post('position'),
            'company' => $role == 1 ? (int)$this->request->post('company') : null,
        ])->where('id','=',(int)$id);

        if ($password) {
            $password = password_hash($password,PASSWORD_BCRYPT);
            $sql->set('password',$password);
        }
        $sql->query();

        if ($this->request->getUser()['id'] == $id) {
            $this->request->setSession('login',$this->request->post('login'));
            $this->request->setSession('fullName',$this->request->post('name'));
        }

        $this->request->redirectByUrl('/users');
    }

    public function showAdd() {
        $companies = Company::select()->query()->fetchAll();
        return View::render('admin/users/add',['companies'=>$companies]);
    }

    protected static $roleToWord = [
        '1' => 'Correspondent',
        '2' => 'Telegraphist',
        '3' => 'Administrator'
    ];

    public function showAll() {
        $users = User::select(['distinct u.id','login','u.surname','u.name','u.patronym','u.role','u.iin','u.active',
            'company' => 'c.name','cert_status' => 'c1.status'])
            ->leftJoin('companies')->onA('u.company','=','c.id')
            ->leftJoin('certificates')->onA('c1.user','=','u.id')
            ->onA('c1.status','IS','true')
            ->where('u.id','>',0)
            ->orderBy(['login'])
            ->query()->fetchAll(2);
        foreach ($users as $row=>$user) {
            $users[$row]['roleV'] = self::$roleToWord[($user['role'].'')[0]];
        }
        return View::render('admin/users/users',['users'=>$users]);
    }

    public function showEdit($id) {
        $user = User::getById($id);
        $rights = json_decode($user['rights'],true);
        $companies = Company::select()->query()->fetchAll();
        return View::render('admin/users/edit',[
            'user' => $user,
            'rights' => $rights,
            'companies'=>$companies
        ]);
    }
}
