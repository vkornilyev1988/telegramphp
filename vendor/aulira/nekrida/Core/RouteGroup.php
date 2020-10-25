<?php

namespace Nekrida\Core;

/**
 * 
 */
class RouteGroup
{
	/** @var string */
	protected $namespace = '';
	/** @var string */
	protected $domain = '';
	/** @var string */
	protected $prefix = '';
	/** @var array */
	protected $middleware = [];

	protected $restrictions = [];

	public function domain($domain) {
		$this->domain = $domain;
		return $this;
	}

	/**
	 * @param $routes RouteItem[]
	 * @return RouteItem[]
	 */
	public function group($routes) {
		$routes = $this->multiArrayToSimple($routes);

		foreach ($routes as $route) {
			$route->namespace($this->namespace)
			->domain($this->domain)
			->prefix($this->prefix)
			->middleware($this->middleware)
			->where($this->restrictions);
		}
		return $routes;
	}

	public function middleware($middleware) {
		if (!empty($middleware))
			$this->middleware[] = $middleware;
		return $this;
	}

	public function namespace($namespace) {
		$this->namespace = $namespace;
		return $this;
	}

	public function prefix($prefix) {
		$this->prefix = $prefix;
		return $this;
	}

	public function where($restrictions) {
		foreach ($restrictions as $key => $value)
			$this->restrictions[$key] = $value;
		return $this;
	}

	/**
	 * Converts multidimensional array into simple one dimensional array
	 * @param $array
	 * @return array
	 */
	protected function multiArrayToSimple ($array) {
		$arr2 = [];
		foreach ($array as $item)
			if (is_array($item))
				foreach ($item as $subItem)
					$arr2[] = $subItem;
			else
				$arr2[] = $item;
		return $arr2;
	}
}