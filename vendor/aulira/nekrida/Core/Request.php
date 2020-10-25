<?php

namespace Nekrida\Core;

/**
 *
 */
class Request
{
	protected $url;

	protected $domain;

	protected $guid;

	protected $get;
	protected $post;
	protected $server;
	protected $session;
	protected $cookie;
	protected $files;
	protected $headers = [];

	protected $theme;

	protected $input;

	private $cache = [];

	//GETTERS
	public function get($key = NULL) { return is_null($key) ? $this->get : $this->getValueByKey('get', $key);}

	public function post($key = NULL) { return is_null($key) ? $this->post : $this->getValueByKey('post', $key);}

	public function server($key = NULL) {return is_null($key) ? $this->server : $this->getValueByKey('server', $key);}

	public function session($key = NULL) {return is_null($key) ? $this->session : $this->getValueByKey('session',$key);}

	public function cookie($key = NULL) {return is_null($key) ? $this->cookie : $this->getValueByKey('cookie', $key);}

	public function files($key = NULL) {return is_null($key) ? $this->files : $this->getValueByKey('files', $key);}

	public function header($key = NULL) {return is_null($key) ? $this->headers : $this->getValueByKey('headers', $key);}

	public function input($key = NULL) {return is_null($key) ? $this->input : $this->getValueByKey('input', $key);}

	public function cache($key = NULL) {return is_null($key) ? $this->cache : $this->getValueByKey('cache', $key);}

	public function printHeaders() {
		$str = '';
		foreach ($this->headers as $key => $value) {
			$str = $key.': '.$value.PHP_EOL;
		}
		return $str;
	}

	public function url() {return $this->url;}

    public function guid() {return $this->guid;}

	public function domain() {return $this->domain;}

	public function method() {
		if (isset($this->server['REQUEST_METHOD']))
			if ($this->server['REQUEST_METHOD'] == 'POST')
				if (isset($this->post['_method']))
					return strtolower($this->post['_method']);
				else return 'post';
			else
				return strtolower($this->server['REQUEST_METHOD']);
		else {
			if (empty($this->post))
				return 'get';
			elseif (isset($this->post['_method']))
				return strtolower($this->post['_method']);
			else return 'post';
		}
	}

	public function getUser() {
		return [ 'login' =>	$this->session('login'),
				'group' => $this->session('group'),
                'id' => $this->session('userId')
			];
	}

	public function getTheme() {
		if (empty($this->theme)) {
			$this->theme = $this->session['theme'] ?? $this->cookie['theme'] ?? 'default';
		}
		return $this->theme;
	}

	//SETTERS
	public function setGet($get) {$this->get = $get; return $this; }

	public function setPost($post) {$this->post = $post; return $this; }

	public function setServer($server) {$this->server = $server; return $this; }

	public function setSessionArray($session) {$this->session = $session; return $this; }

	public function setCookieArray($cookie) {$this->cookie = $cookie; return $this; }

	public function setFiles($files) {$this->files = $files; return $this; }

	public function setUrl($url) {$this->url = parse_url($url,PHP_URL_PATH); return $this;}

	public function setInput($input) {$this->input = $input; return $this;}

	public function unsetInput($key) {unset($this->input[$key]); return $this;}

	public function setDomain($domain) {$this->domain = $domain; return $this;}

	public function setGUID($guid) {$this->guid = $guid; return $this;}

	public function setCache($key,$value) {$this->cache[$key] = $value; return $this;}

	//One item

	public function clearSession() {$_SESSION = []; $this->session = [];}

	public function setSession($key,$value) {$_SESSION[$key] = $value; $this->session[$key] = $value;}

	public function unsetSession($key) {unset($_SESSION[$key]); unset($this->session[$key]);}

	public function setCookie(string $key , $value = "", $expire = 0, string $path = "", string $domain = "", bool $secure = FALSE, bool $httponly = FALSE) {
		setcookie($key,$value,$expire,$path,$domain,$secure,$httponly);
		$this->cookie[$key] = $value;
	}

	public function setHeader($key, $value) {
		header($key.':'.$value);
		$this->headers[$key] = $value;
	}

	//VALIDATION

	public function validate(... $array) {
		//['id' => 'required|unique:posts|max:255|numeric']
		//['title' => ['required','unique:posts','max:255','ascii']]

		/*
		Validator::setRequest($request);
		FirewallValidator::validate(
			Validator::required('name','Name is empty'),
			Validator::required('id','Id is empty'),
			Validator::numeric('id','Id is not a number'),
			FirewallValidator::isRule('rule'),
		);

		$this->request->validate(
			Validator::required('name','Name is empty'),
			Validator::required('id','Id is empty'),
			Validator::numeric('id','Id is not a number'),
			FirewallValidator::isRule('rule'),
		);
		*/

		foreach ($array as $value) {
			if ($value['status'] == 'failed') {
				Validator::failed($value['message'],$value['type']);
				return false;
			}
		}
		return true;
	}

	public function redirectByUrl($url,$parameters = []) {
		if (empty($parameters))
			$this->setHeader('Location',$url);
		else
			$this->setHeader('Location',Route::url($url,$parameters));
	}

	public function redirectByRoute($route,$parameters) {
		$redirectRoute = Route::route($route);
		$url = Route::url($redirectRoute->getUrl(),$parameters);
		$this->setHeader('Location',$url);
	}

	//PROTECTED

	protected function getValueByKey($array, $key) {
		// $key = 'rules/10/item';
		$key_array = explode('/', $key);
		if (isset($this->$array[$key_array[0]]))
			$x = $this->$array[$key_array[0]];
		else
			return NULL;
		for ($i = 1; $i < count($key_array); $i++)
			if (isset ($x[$key_array[$i]]))
				$x = $x[$key_array[$i]];
			else
				return NULL;
		return $x;
	}

}
