<?php

namespace Nekrida\Core;

require_once __DIR__ . '/Autoloader.php';

/**
 * 
 */
class Core
{

    public function prepare($coreDirectory) {
        $autoLoader = new Autoloader();
        $autoLoader->register();
        $autoLoader->addNamespace('Nekrida',__DIR__.'/../');

        Config::init($coreDirectory);

        Config::importAll();

        foreach (Config::get('config/namespaces') as $namespace => $dir) {
            $autoLoader->addNamespace($namespace,str_replace('{root}', Config::dir(), $dir));
        }

        foreach (Config::get('databases') as $key => $value) {
            $value['name'] = $key;
            Database::setInstance($value);
        }
    }

	public function run($coreDirectory) {
        $this->prepare($coreDirectory);
		session_start();

		$nekro = new Request();
		$nekro->setUrl($_SERVER['REQUEST_URI'])
		->setDomain($_SERVER['SERVER_NAME'])
		->setGet($_GET)
		->setPOST($_POST)
		->setCookieArray($_COOKIE)
		->setServer($_SERVER)
		->setSessionArray($_SESSION)
		->setFiles($_FILES);

        Validator::setRequest($nekro);
        Log::init($nekro);
		Locale::init($nekro);
		View::setRequest($nekro);
		View::setDefaultNamespace('\App\Controllers');

		$router = new Router($nekro);
		$router->run();
	}
}