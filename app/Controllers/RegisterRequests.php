<?php
namespace App\Controllers;


use App\Modules\Company;
use Nekrida\Auth\User;
use Nekrida\Core\Storage;
use Nekrida\Locale\View;

class RegisterRequests extends Controller
{
    public function showAll() {
        $companies = Company::select()->whereA('confirmed','IS NOT','true')->query()->fetchAll(2);
        foreach ($companies as $key => $item) {
            $companies[$key]['decision_doc'] = Storage::link($companies[$key]['decision_doc']);
            $companies[$key]['const_doc'] = Storage::link($companies[$key]['const_doc']);
        }

        return View::render('admin/requests/companies', [
            'companies' => $companies
        ]);
    }

    public function accept($id) {
        Company::updateSet(['confirmed' => 't'])
            ->where('id','=',(int)$id)
			->query();

        $this->request->redirectByUrl('/requests/register');
    }

    public function reject($id) {
        Company::deleteById($id);
        $this->request->redirectByUrl('/requests/register');
    }
}
