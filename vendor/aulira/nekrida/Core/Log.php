<?php

namespace Nekrida\Core;

use Nekrida\Log\Web;

/**
 * 
 */
class Log
{
    protected static $request;
	//Log levels
	public static function emergency($message)
	{
        static::log('emergency',$message);
	}

	public static function alert($message)
	{
        static::log('alert',$message);
	}

	public static function critical($message)
	{
        static::log('critical',$message);
	}

	public static function error($message)
	{
        static::log('error',$message);
	}

	public static function warning($message)
	{
        static::log('warning',$message);
	}

	public static function notice($message)
	{
        static::log('notice',$message);
	}

	public static function info($message)
	{
        static::log('info',$message);
	}

	public static function debug($message)
	{
		static::log('debug',$message);
	}

	    //NOT STANDARD
	public static function success($message) {
        static::log('success',$message);
    }

	//END LOG LEVELS

    public static function log($level,$message) {
        //FIXME
        foreach (Config::get('log') as $logger) {
            if (in_array($level,$logger['levels'])) {
                if (method_exists($logger,'init'))
                    call_user_func([$logger['class'],'init',]);
                call_user_func([$logger['class'], 'log'], $level, $message, static::$request);
            }
        }
        //Web::log(static::$request,$level,$message);
    }

	//Factories
	public static function init($request) {
        static::$request = $request;
	}

	public static function channel($channel)
	{
		# code...
	}
}