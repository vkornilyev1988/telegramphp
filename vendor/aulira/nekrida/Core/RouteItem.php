<?php

namespace Nekrida\Core;

/**
 * 
 */
class RouteItem
{
	/** @var string */
	protected $url;
	/** @var callable  */
	protected $callback;
	/** @var string */
	protected $name = '';
	/** @var array */
	protected $methods;
	/** @var string */
	protected $domain = '';
	/** @var array */
	protected $restrictions = [];
	/** @var string */
	protected $namespace = '';

	protected $middleware = [];

	protected $afterMiddleware = [];

	protected $view = '';

	public function __construct($methods,$url, $callback, $middleware = []) {
		$this->url = $url;
		$this->callback = $callback;
		$this->methods = $methods;
		$this->middleware = $middleware;
		$this->name = random_bytes(10);
	}

	//SETTERS

	public function after($middleware) {
		if (is_array($middleware))
			foreach ($middleware as $item) {
				$this->afterMiddleware[] = $middleware;
			}
		elseif (!empty($middleware))
			$this->afterMiddleware[] = $middleware;
		return $this;
	}

	public function domain($domain) {
		$this->domain = $domain;
		return $this;
	}

	public function middleware($middleware) {
		if (is_array($middleware)) {
			foreach ($middleware as $item) {
				$this->middleware[] = $item;
			}
		}
		elseif (!empty($middleware))
			$this->middleware[] = $middleware;
		return $this;
	}

	public function namespace($namespace) {
	    if (is_string($this->callback) && strpos($this->callback,'\\') === 0) return $this;
		$this->namespace = $namespace;
		return $this;
	}

	public function name($name) {
		if (!empty($name))
			Route::nameRoute($this,$name);
		return $this;
	}

	public function prefix($prefix) {
		$this->url = $prefix . $this->url;
		return $this;
	}

	public function where($restrictions) {
		foreach ($restrictions as $key => $value) {
			$this->restrictions[$key] = $value;
		}
		return $this;
	}

	public function setView($view) {$this->view = $view; return $this;}

	//GETTERS

	public function getUrl() {return $this->url;}

	public function getCallback() {return $this->callback;}

	public function getName() {return $this->name;}

	public function getMethods() {return $this->methods;}

	public function getDomain() {return $this->domain;}

	public function getRestrictions() {return $this->restrictions;}

	public function getNamespace() {return $this->namespace;}

	public function getMiddlewares() {return $this->middleware;}

	public function getAfter() {return $this->afterMiddleware;}

	public function getView() {return $this->view;}
}