<?php
namespace App\Controllers;

use Nekrida\Core\Config;
use Nekrida\Locale\View;
use Nekrida\Core\Database as DB;

class Database extends Controller
{
    const TABLES = [
        'bills_companies',
        'companies',
        'departments',
        'destinations',
        'destinations_groups',
        'destinations_telegrams',
        'logs_telegrams',
        'roles',
        'telegrams',
        'transactions',
        'users'
    ];
    private $path;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->path = str_replace(
            '{root}',
            Config::dir(),
            Config::get('storage/local/backups/root')
        );
    }

    public function showAll()
    {
        $backups = [];
        foreach (glob($this->path."/*.backup") as $filename) {
            $backups[] = [
                'name'=>basename($filename),
                'time'=>filectime($filename)
            ];
        }

        return View::render('/admin/databases',['backups'=>$backups]);
    }
    public function create()
    {
        $database = DB::getInstance();
        $zip = new \ZipArchive();
        $zip->open($this->path.'/'.date("dmYHis").".backup",\ZipArchive::CREATE);
        foreach(self::TABLES as $table){
            $database->pgsqlCopyToFile($table, $this->path.'/'.$table.".backup");
            $zip->addFile($this->path.'/'.$table.".backup",$table.".backup");
        }
        $zip->close();
        foreach(self::TABLES as $table) unlink($this->path.'/'.$table.".backup");
        $this->request->redirectByUrl('/database');
    }
    public function restore($name)
    {
        $database = DB::getInstance();
        $zip = new \ZipArchive();
        $zip->open($this->path.'/'.$name);
        $backup = explode(".",$name);
        $backup = $backup[0];
        mkdir($this->path.'/'.$backup);
        $zip->extractTo($this->path.'/'.$backup);
        foreach(glob($this->path.'/'.$backup."/*.backup") as $file){
            $table = explode(".",basename($file));
            $database->query("ALTER TABLE ".$table[0]." DISABLE TRIGGER ALL;");
            $database->query("DELETE FROM ".$table[0]);
            $database->pgsqlCopyFromFile($table[0], $file);
            $database->query("ALTER TABLE ".$table[0]." ENABLE TRIGGER ALL;");
            unlink($file);
        }
        rmdir($this->path.'/'.$backup);
        $this->request->redirectByUrl('/database');
    }
    public function delete($name)
    {
        unlink($this->path.'/'.basename($name));
        $this->request->redirectByUrl('/database');
    }
    public function upload()
    {
        $backup = $this->request->files()['backup'];
        $name = date("dmYHis").".backup";
        move_uploaded_file($backup['tmp_name'],$this->path.'/'.$name);
        $this->request->redirectByUrl('/database');
        return $name;
    }
    public function uploadAndRestore()
    {
        $name = $this->upload();
        $this->restore($name);
    }
    public function download($name)
    {
        $name = basename($name);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header("Content-Type: text/plain");

        header("Content-Disposition: attachment; filename=\"".basename($name)."\";" );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($this->path."/".$name));
        readfile($this->path."/".$name);
    }

}
