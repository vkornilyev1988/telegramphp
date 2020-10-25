<?php

namespace Nekrida\Core;

/**
 * 
 */
class View
{
	protected static $defaultNamespace = '\App\Controllers';

	protected static $layoutController = '\\App\\Controllers\\Header';

	/** @var Request */
	protected static $request;

    /** @override
     * @param $total
     * @return mixed
     */
	protected static function postRender($total) {
		return $total;
	}

	public static function init($request,$layoutController) {
		self::$request = $request;
		self::$layoutController = $layoutController;
	}

	//
    public static function apiError($code,$message) {
        self::$request->setHeader('Content-Type','application/json');
        $json = ['status'=>false,'error-code'=>$code,'error-message'=>$message];
        return forward_static_call('static::postRender', json_encode($json), self::$request);
    }
    //

	public static function json($parameters = []) {
		self::$request->setHeader('Content-Type','application/json');
		return forward_static_call('static::postRender', json_encode($parameters), self::$request);
	}

	public static function load($controller, $method, $parameters = []) {
		//IF we don't have namespace set in controller 
		//THEN set our default (shorten the writing in views)
		if (strpos($controller, '\\') !== 0)
			$controller = self::$defaultNamespace.'\\'.$controller;
		$call = new $controller(self::$request);
		return $call->$method($parameters);
	}


	public static function render($view,$parameters = []) {
		extract($parameters);

		//Include view
		ob_start();
		include self::getView($view);
		$content = ob_get_clean();

		//Include layout if exists
        $header = new self::$layoutController(self::$request);
		$total = $header->header($content,$parameters);
		if (empty($total)) $total = $content;

		//Process the total
		return forward_static_call('static::postRender',$total,self::$request);
	}

	public static function view($view, $parameters = []) {
		extract($parameters);

		//Include view
		ob_start();
		include self::getView($view);
		$total = ob_get_clean();
		return forward_static_call('static::postRender',$total,self::$request);
	}

	protected static function getView($view) {
		$theme = self::$request->getTheme();
        $urls = Config::get('config/viewsUrls');

		foreach ($urls as $url) {
		    $uri = str_replace(['{root}','{theme}','{view}'],[Config::dir(),$theme,$view],$url);
            if (file_exists($uri))
                return $uri;
        }
	}

	public static function getRequest() {
		return self::$request;
	}

	/**
	 * @param Request $request
	 */
	public static function setRequest(Request $request) {
		self::$request = $request;
	}

	public static function setDefaultNamespace($namespace) {
		self::$defaultNamespace = $namespace;
	}
}