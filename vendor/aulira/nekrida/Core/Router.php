<?php

namespace Nekrida\Core;

/**
 * 
 */
class Router
{
    /** @var Request */
	protected $request;
	
	function __construct($request)
	{
		$this->request = $request;
	}

	public function run() {
		//TODO: Rewrite to gathering from config
		Route::gatherFromDirectory(Config::dir().'/routes/*.php');
		if (!$this->handle())
			$this->sendNotFound();
	}

	public function handle() {
		foreach (Route::getAll() as $route) {
			if (!in_array($this->request->method(),$route->getMethods()))
				continue;
			//Save inputs names
			$inputNames = [];

			$pattern = preg_replace_callback('/{([\w]*?)}/', function ($matches) use (&$inputNames) {
				$inputNames[] = $matches[1];
				return '([^\/]+?)';
			}, $route->getUrl());

			//Process the URL
			if (preg_match_all('#^'.$pattern.'$#', $this->request->url(), $matches,PREG_OFFSET_CAPTURE)) {
				$matches = array_slice($matches, 1);
				$param = [];
				for($i = 0; $i < count($matches); $i++) {
					$param[$inputNames[$i]] = $matches[$i][0][0];
				}
				//Check for restrictions

				if (!$this->checkRestrictions($param,$route->getRestrictions())) {

					continue;
				}
				$this->request->setInput($param);

				//Process Before Middlewares
				if (!$this->handleMiddleware($route->getMiddlewares(),$param,Config::get('config/middlewareNamespace')))
					continue;

				//Process Controller
                Route::setCurrentRoute($route);
				//ob_start();
				$res = $this->invoke($route->getCallback(),$this->request->input(),$route->getNamespace());
				//$pre = ob_get_clean();
				/*foreach ($this->request->header() as $key => $value)
					$_SERVER['__SRV']->header($key.': '.$value);*/
				//echo $this->request->printHeaders();
				echo $res;
				//TODO: draw $pre if debug_mode ON
				//echo $pre;

				//Process After Middlewares
				$this->handleMiddleware($route->getAfter(),$this->request->input(),Config::get('config/middlewareNamespace'));

				return true;
			}
		}
		return false;
	}

	protected function checkRestrictions($values,$restrictions) {
		foreach ($restrictions as $key => $value) {
			if (isset($values[$key]) && !preg_match('#^'.$value.'$#',$values[$key]))
				return false;
		}
		return true;
	}

	protected function handleMiddleware($middlewares,$param,$namespace) {
		foreach ($middlewares as $middleware) {
			if ($namespace)
				$middleware = $namespace.'\\'.$middleware;
			if (!$this->invoke($middleware,$param))
				return false;
		}
		return true;
	}

	protected function invoke($func,$param,$namespace = '') {
		if ($namespace)
			$func = $namespace .'\\'.$func;
		if (is_callable($func))
			if (strpos($func,'::') === false)
				//Non-Static
				return call_user_func_array($func, $param);
			else {
				//Static
				$param['request'] = $this->request;
				return call_user_func_array($func, $param);
			}
			
		elseif (stripos($func,'@') !== false) {
			list($controller,$method) = explode('@',$func);
			/*if ($namespace)
				$controller = $namespace .'\\'.$controller;*/
			if (class_exists($controller)) {
				$res = call_user_func_array([new $controller($this->request), $method], $param);
				return $res;
			} else {
				$this->sendError($controller);
				return false;
			}
		} elseif (class_exists(($namespace ? $namespace.'\\' : '') . $func)) {
			$func = ($namespace ? $namespace.'\\' : '') . $func;
			$res = new $func($param);
			return $res;
		}
		$this->sendError($func);
	}

	//Error 500
	protected function sendError($item) {
		http_response_code(500);
		echo View::render('errors/500',['item' => $item]);
	}

	//Error 404
	protected function sendNotFound() {
		http_response_code(404);
		echo View::render('errors/404');
	}
}