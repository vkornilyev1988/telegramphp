<?php

namespace Nekrida\Core;

/**
 * 
 */
class Controller
{
	protected $request;
	
	function __construct(Request $request) {
		$this->request = $request;
	}
}