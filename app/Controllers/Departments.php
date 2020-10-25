<?php
namespace App\Controllers;


use Nekrida\Auth\User;
use Nekrida\Core\Database;
use Nekrida\Locale\View;
use App\Modules\Company;
use App\Modules\Department;

class Departments extends Controller
{
    public function add() {
        $companyId = User::select(['company'])
            ->where('id','=',(int)$this->request->session('user'))
            ->query()->fetch()[0];

        $id = Department::insertSet([
            'name' => $this->request->post('name'),
            'company' => (int)$companyId
        ])->query()->lastInsertId();

        $users = $this->request->post('users');

        User::updateSet(['department' => $id])
            ->whereA('id','IN','('.implode(',',$users).')')
            ->query();

        $this->request->redirectByUrl('/departments');
    }

    public function delete() {
        $id = $this->request->post('id');
        Department::deleteById($id);

        return $this->showAll();
    }

    public function edit($id) {
        Department::updateSet([
            'name' => $this->request->post('name')
        ])->where('id','=',(int)$id)->query();

        User::updateSet(['department'=>null])
            ->where('department','=',(int)$id)
            ->query();

        $users = $this->request->post('users');

        User::updateSet(['department' => $id])
            ->whereA('id','IN','('.implode(',',$users).')')
            ->query();

        $this->request->redirectByUrl('/departments');
    }

    public function moveBalance() {
        $from = $this->request->post('from');
        $fromCompany = strpos($from,'c') !== false;
        if ($fromCompany)
            $from = substr($from,1);

        $to = $this->request->post('to');
        $toCompany = strpos($to,'c') !== false;
        if ($toCompany)
            $to = substr($to,1);

        $sum = $this->request->post('sum');
        //TODO Validate sum on numeric
        if (!is_numeric($sum)) return false;

        Database::getInstance(Department::DATABASE_NAME)->beginTransaction();
        if ($fromCompany)
            Company::updateSet([])
                ->setRaw('balance','balance - '.$sum)
                ->where('id','=',(int)$from)
                ->query();
        else
            Department::updateSet([])
                ->setRaw('balance','balance - '.$sum)
                ->where('id','=',(int)$from)
                ->query();

        if ($toCompany)
            Company::updateSet([])
                ->setRaw('balance','balance + '.$sum)
                ->where('id','=',(int)$to)
                ->query();
        else
            Department::updateSet([])
                ->setRaw('balance','balance + '.$sum)
                ->where('id','=',(int)$to)
                ->query();
        Database::getInstance(Department::DATABASE_NAME)->commit();

        $this->request->redirectByUrl('/departments');
    }

    //SHOW FUNCTIONS

    public function showAdd() {
        $companyId = User::select(['company'])
            ->where('id','=',(int)$this->request->session('user'))
            ->query()->fetch()[0];
        $company = Company::select(['id','name'])
            ->where('id','=',(int)$companyId)
            ->query()->fetch(2);
        $users = User::select()
            ->where('company','=',(int)$companyId)
            ->where('department','IS',NULL)
            ->query()->fetchAll(2);

        return View::render('correspondent/departments/add', [
            'company' => $company,
            'users' => $users
        ]);
    }

    public function showAll() {
        $me = User::select()->where('id','=',(int)$this->request->session('user'))->query()->fetch(2);
        $companyId = User::select(['company'])
            ->where('id','=',(int)$this->request->session('user'))
            ->query()->fetch()[0];

        $company = Company::select(['c.id','c.name',
            'userIds' => 'json_agg(u.id)',
            'userNames' => 'json_agg(u.name)',
            'userSurnames' => 'json_agg(u.surname)',
            'userDepartments' => 'json_agg(u.department)'
        ])->leftJoin('users')->onA('u.company','=','c.id')
            ->where('u.company','=',(int)$companyId)
            ->groupBy(['c.id','c.name'])
            ->query()->fetch(2);

        $departments = [];

        foreach (Department::select(['id','name'])
                     ->where('company','=',(int)$companyId)
                     ->query()->fetchAll(2) as $department)
            $departments[$department['id']] = ['name' => $department['name']];

        $userIds = json_decode($company['userIds']);
        $userDepartments = json_decode($company['userDepartments']);
        $userSurnames = json_decode($company['userSurnames']);
        $userNames = json_decode($company['userNames']);

        for ($i = 0; $i < count($userIds); $i++) {
            $dep = $userDepartments[$i] ?: 0;
            $departments[$dep]['users'][] = [
                'id' => $userIds[$i],
                'name' => $userNames[$i],
                'surname' => $userSurnames[$i],
            ];
        }
        $users = $departments[0]['users'];
        unset($departments[0]);

        return View::render('correspondent/departments/departments',[
            'company' => $company,
            'departments' => $departments,
            'users' => $users,
            'me' => $me,
        ]);
    }

    public function showEdit($id) {
        $companyId = User::select(['company'])
            ->where('id','=',(int)$this->request->session('user'))
            ->query()->fetch()[0];
        $company = Company::select(['id','name'])
            ->where('id','=',(int)$companyId)
            ->query()->fetch(2);
        $department = Department::select()
            ->where('id','=',(int)$id)
            ->query()->fetch(2);
        $users = User::select(['id','surname','name','patronym','department'])
            ->where('company','=',(int)$companyId)
            ->whereRaw('(department IS NULL OR department = '.(int)$department['id'].')')
            ->query()->fetchAll(2);

        return View::render('correspondent/departments/edit',[
            'company'=>$company,
            'department' => $department,
            'users' => $users
        ]);
    }

    public function showMoveBalance() {
        $companyId = User::select(['company'])
            ->where('id','=',(int)$this->request->session('user'))
            ->query()->fetch()[0];
        $company = Company::select(['id','name','balance'])
            ->where('id','=',(int)$companyId)
            ->query()->fetch(2);
        $departments = Department::select(['id','name','balance'])
            ->where('company','=',(int)$companyId)
            ->query()->fetchAll(2);
        return View::render('correspondent/departments/moveBalance',[
            'company' => $company,
            'departments' => $departments
        ]);
    }

    public function showAjax($id) {
        $department = Department::select()
            ->where('id','=',(int)$id)
            ->query()->fetch(2);

        return View::view('correspondent/users/userDeps',[
            'department' => $department
        ]);
    }
}
