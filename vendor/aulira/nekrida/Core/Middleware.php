<?php

namespace Nekrida\Core;

/**
 * 
 */
class Middleware
{
	protected $request;
	
	function __construct(Request $request) {
		$this->request = $request;
	}
}