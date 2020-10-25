<?php

namespace Nekrida\Core;

/**
 * 
 */
class Validator
{
	/** @var Request */
	protected static $request;

	protected static $lastMessage;

	public static function getLastMessage() {return self::$lastMessage;}

	public static function setRequest($request) {self::$request = $request;}

	//IF NOT VALID
	public static function failed($message,$type) {
	    self::$lastMessage = $message;
		Log::$type($message);
	}

	public static function validate(...$array) {
		foreach ($array as $value) {
			if (!$value['status']) {
				Validator::failed($value['message'],$value['type']);
				return false;
			}
		}
		return true;
	}

	public static function required($value,$message) {
		if (!self::$request->post($value) && !self::$request->get($value))
			return ['status' => false, 'message' => $message,'type'=>'error'];
		else
			return ['status' => true];
	}

	public static function equals($item1,$item2,$message) {
		$a = self::$request->post($item1) ?? self::$request->get($item1);
		$b = self::$request->post($item2) ?? self::$request->get($item2);
		return ($a == $b) ? ['status' => true] : ['status' => false, 'message' => $message,'type'=>'error'];
	}

	public static function isArrayOfNumerics($value,$message) {
		foreach ($value as $item) {
			if (!is_numeric(self::$request->post($value)))
			return ['status' => false, 'message' => $message,'type'=>'error'];
		}
		return ['status' => true];
	}

	public static function isNumeric($value,$message) {
		if (!is_numeric(self::$request->post($value)))
			return ['status' => false, 'message' => $message,'type'=>'error'];
		return ['status' => true];
	}
}