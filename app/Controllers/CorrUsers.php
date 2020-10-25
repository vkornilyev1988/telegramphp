<?php
namespace App\Controllers;

use App\Modules\Company;
use App\Modules\Department;
use Nekrida\Auth\User;
use Nekrida\Core\Storage;
use Nekrida\Locale\View;

/**
 * Class CorrUsers
 * Controller to manage Correspondent users
 * @package App\Controllers
 */
class CorrUsers extends Controller
{
    public function add() {

        $password = password_hash($this->request->post('password'),PASSWORD_BCRYPT);

        $companyId = User::select(['company'])
            ->where('id','=',(int)$this->request->session('user'))
            ->query()->fetch()[0];

        $sql = User::insertSet([
            'login' => $this->request->post('login'),
            'surname' => $this->request->post('surname'),
            'name' => $this->request->post('name'),
            'patronym' => $this->request->post('patronymic'),
            'department' => $this->request->post('department'),
            'email' => $this->request->post('email'),
            'iin' => $this->request->post('iin'),
            'position' => $this->request->post('position'),
            'mobile' => $this->request->post('mobile'),
            'work_phone' => $this->request->post('work-phone'),
            'password' => $password,
            'company' => (int)$companyId,
            'role' => 1
            //'assign_doc' => $allowanceFile,
        ]);
        //Set able to sign
        if ($this->request->files('allowance-file')
            && $this->request->post('can-sign')
            && $this->request->post('iin')
        ) {
            $rights = [
                'correspondent.askForSign' => 1
            ];
            $allowanceFile = Storage::upload($this->request->files('allowance-file'));

            $sql->set('assign_doc',$allowanceFile)
            ->set('rights',json_encode($rights));
        }
        $sql->query();
        //TODO: Notify manager

        $this->request->redirectByUrl('/users');
    }

    public function delete() {
        $id = $this->request->post('id');
        User::deleteById($id);

        return $this->showAll();
    }

    public function edit($id) {

        $sql = User::updateSet([
            'login' => $this->request->post('login'),
            'surname' => $this->request->post('surname'),
            'name' => $this->request->post('name'),
            'patronym' => $this->request->post('patronymic'),
            'department' => $this->request->post('department'),
            'email' => $this->request->post('email'),
            'iin' => $this->request->post('iin'),
            'position' => $this->request->post('position'),
            'mobile' => $this->request->post('mobile'),
            'work_phone' => $this->request->post('work-phone'),
        ])->where('id','=',(int)$id);

        //IF password is not entered
        //THEN we do not change it
        if ($this->request->post('password')) {
            $password = password_hash($this->request->post('password'), PASSWORD_BCRYPT);
            $sql->set('password',$password);
        }

        //Set able to sign
        if ($this->request->files('allowance-file')
            && $this->request->post('can-sign')
            && $this->request->post('iin')
        ) {
            $rights = [
                'correspondent.askForSign' => 1
            ];
            $allowanceFile = Storage::upload($this->request->files('allowance-file'));

            $sql->set('assign_doc',$allowanceFile)
                ->set('rights',json_encode($rights));
        } else {
            $allowanceFile = User::select(['assign_doc'])
                ->where('id','=',(int)$id)
                ->query()->fetch()[0];
            if ($allowanceFile) {
                Storage::delete($allowanceFile);
                $sql->set('assign_doc',null);
            }

        }

        $sql->query();

        $this->request->redirectByUrl('/users');
    }

    public function showAdd() {
        $companyId = User::select(['company'])
            ->where('id','=',(int)$this->request->session('user'))
            ->query()->fetch()[0];
        $company = Company::select(['id','name'])
            ->where('id','=',(int)$companyId)
            ->query()->fetch(2);
        $departments = Department::select()
            ->where('company','=',(int)$companyId)
            ->query()->fetchAll(2);
        return View::render('correspondent/users/add', [
            'company' => $company,
            'departments' => $departments
        ]);
    }

    public function showAll() {
        $companyId = User::select(['company'])
            ->where('id','=',(int)$this->request->session('user'))
            ->query()->fetch()[0];
        $company = Company::select(['id','name'])
            ->where('id','=',(int)$companyId)
            ->query()->fetch(2);
        $users = User::select(['u.login','u.id','surname','u.name','patronym','department','departmentName'=>'d.name'])
            ->leftJoin('departments')->onA('u.department','=','d.id')
            ->where('u.company','=',(int)$companyId)
            ->query()->fetchAll(2);

        return View::render('correspondent/users/users',[
            'company' => $company,
            'users' => $users
        ]);
    }

    public function showEdit($id) {
        $companyId = User::select(['company'])
            ->where('id','=',(int)$this->request->session('user'))
            ->query()->fetch()[0];
        $company = Company::select(['id','name'])
            ->where('id','=',(int)$companyId)
            ->query()->fetch(2);
        $departments = Department::select()
            ->where('company','=',(int)$companyId)
            ->query()->fetchAll(2);
        $user = User::select()
            ->where('id','=',(int)$id)
            ->query()->fetch(2);

        return View::render('correspondent/users/edit',[
            'company' => $company,
            'departments' => $departments,
            'user' => $user
        ]);
    }

    public function showAjax($id) {
        $user = User::getById($id);
        unset($user['password']);

        return View::json(['user' => $user]);
    }
}
