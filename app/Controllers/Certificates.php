<?php
namespace App\Controllers;

use App\Modules\Certificate;
use Nekrida\Core\Config;
use Nekrida\Locale\View;

class Certificates extends Controller
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
        $days = (int)$this->request->post('days');
        $options = [
            'countryName' => $this->request->post('country'),
            'organizationName' => $this->request->post('organization'),
            'commonName' => $this->request->post('name'),
            'emailAddress' => $this->request->post('email'),
            'serial' => 0,
        ];

        Certificate::updateSet([
            'status' => 'f'
        ])->where('status','=','t')
            ->query();

        Certificate::generateCA($options,$this->path,$days);
        //$this->request->redirectByUrl('/certificates');
    }

    public function recall($id) {
        Certificate::updateSet([
            'status' => 'f'
        ])->where('status','=','t')
            ->query();

        $this->request->redirectByUrl('/certificates');
    }

    public function showAdd() {
        return View::render('admin/certificates/addCA');
    }

    public function showAll()
    {
        $ca = Certificate::select()
            ->whereA('"user"', 'IS', 'NULL') //quotes around user, because of PostgreSQL
            ->query()->fetchAll(2);
        return View::render('admin/certificates/ca', ['certificates' => $ca]);
    }

}
