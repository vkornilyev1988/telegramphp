<?php

namespace Nekrida\Core;

use Nekrida\Locale\View as LocaleView;
/**
 *
 */
class Route
{
	protected static $routes = [];

	/** @var RouteItem */
	protected static $currentRoute;

	// For RouteItems

    /**
     * @param RouteItem $route
     * @return RouteItem
     */
    public static function addRoute(RouteItem $route)
	{
		self::$routes[$route->getName()] = $route;
		return $route;
	}

    /**
     * @param RouteItem $route
     * @param $name
     * @return RouteItem
     */
    public static function nameRoute(RouteItem $route, $name) {
		self::$routes[$name] = $route;
		unset(self::$routes[$route->getName()]);
		return $route;
	}

		//GET, POST, PATCH etc.

    /**
     * @param $url
     * @param $callback
     * @return RouteItem
     */
    public static function delete($url, $callback) {
		return self::addRoute(new RouteItem(['delete'],$url,$callback));
	}

    /**
     * @param $url
     * @param $callback
     * @return RouteItem
     */
    public static function get($url, $callback) {
		return self::addRoute(new RouteItem(['get'],$url,$callback));
	}

    /**
     * @param $url
     * @param $callback
     * @return RouteItem
     */
    public static function patch($url, $callback) {
		return self::addRoute(new RouteItem(['patch'],$url,$callback));
	}

    /**
     * @param $url
     * @param $callback
     * @return RouteItem
     */
    public static function post($url, $callback) {
		return self::addRoute(new RouteItem(['post'],$url,$callback));
	}

    /**
     * @param $url
     * @param $callback
     * @return RouteItem
     */
    public static function put($url, $callback) {
		return self::addRoute(new RouteItem(['put'],$url,$callback));
	}

    /**
     * @param $methods array array of methods
     * @param $url
     * @param $callback
     * @return RouteItem
     */
    public static function match($methods, $url, $callback) {
		return self::addRoute(new RouteItem($methods,$url,$callback));
	}

	public static function any($url,$callback) {
        return self::addRoute(new RouteItem(['get','post','put','head','delete','patch'],$url,$callback));
    }

	public static function redirect($methods,$url,$target,$status) {
	}

	public static function localeRender($url,$view) {
        $a = new RouteItem(['get'],$url,'\\'.__NAMESPACE__.'\\Route::renderLocaleView');
        $a->setView($view);
        return self::addRoute($a);
    }

	public static function view($url,$view) {
        $a = new RouteItem(['get'],$url,'\\'.__NAMESPACE__.'\\Route::drawView');
        $a->setView($view);
        return self::addRoute($a);
	}

	public static function localeView($url,$view) {
        $a = new RouteItem(['get'],$url,'\\'.__NAMESPACE__.'\\Route::drawLocaleView');
        $a->setView($view);
        return self::addRoute($a);
    }

	// For RouteGroups

    /**
     * @param $domain
     * @return RouteGroup
     */
    public static function domain($domain) {
		$group = new RouteGroup();
		return $group->domain($domain);
	}

    /**
     * @param $middleware
     * @return RouteGroup
     */
    public static function middleware($middleware) {
		$group = new RouteGroup();
		return $group->middleware($middleware);
	}

    /**
     * @param $namespace
     * @return RouteGroup
     */
    public static function namespace($namespace) {
		$group = new RouteGroup();
		return $group->namespace($namespace);
	}

    /**
     * @param $prefix
     * @return RouteGroup
     */
    public static function prefix($prefix) {
		$group = new RouteGroup();
		return $group->prefix($prefix);
	}

	//For Router
	public static function gatherFromCache() {

	}

	public static function gatherRoutes($files) {

	}

	public static function gatherFromDirectory($files) {
		$filesList = glob($files);
		foreach ($filesList as $file) {
			include $file;
		}
	}

	public static function gatherFromFiles($files) {
		foreach ($files as $file) {
			include $file;
		}
	}

	public static function route($name = false) {
		if ($name)
			return isset(self::$routes[$name]) ? self::$routes[$name] : null;
		else
			return self::$currentRoute;
	}

    /**
     * @param RouteItem $currentRoute
     */
    public static function setCurrentRoute(RouteItem $currentRoute)
    {
        self::$currentRoute = $currentRoute;
    }

    /**
     * @return RouteItem[]
     */
	public static function getAll() {
		return self::$routes;
	}


	public static function ade($route,$id,$controller) {
		return [
			self::addRoute(new RouteItem(['get'],$route.'/add',$controller.'@showAdd')),
			self::addRoute(new RouteItem(['put'],$route.'/add',$controller.'@add')),
			self::addRoute(new RouteItem(['post'],$route.'/add',$controller.'@add')),
			self::addRoute(new RouteItem(['get'],$route.'/'.$id.'/delete',$controller.'@showDelete')),
			self::addRoute(new RouteItem(['delete'],$route.($route[strlen($route)-1] == 's' ? '' : 's'),$controller.'@delete')),
			self::addRoute(new RouteItem(['get'],$route.'/'.$id.'/edit',$controller.'@showEdit')),
			self::addRoute(new RouteItem(['patch'],$route.'/'.$id,$controller.'@edit'))
		];
	}

	public static function url($url,$parameters) {
		$counter = 0;
		$url = preg_replace_callback('/{([A-Za-z]*?)}/', function ($matches) use ($parameters, &$counter) {
				$inputName = $matches[1];
				return isset($parameters[$inputName]) ? $parameters[$inputName] : $parameters[$counter++];
			}, $url);
		return $url;
	}


	//View, redirect etc.

    public static function drawView($param) {
	    return View::view(self::$currentRoute->getView(),View::getRequest()->input());
    }

    public static function drawLocaleView($param) {
        return LocaleView::view(self::$currentRoute->getView(),View::getRequest()->input());
    }

    public static function renderLocaleView($param) {
        return LocaleView::render(self::$currentRoute->getView(),View::getRequest()->input());
    }


}
