<?php
namespace Nekrida\Log;

use Nekrida\Core\Request;

class Web
{

    public static function log ($level,$message, $request) {
        $oldSession = $request->session('logs');
        $oldTypes = $request->session('log_types');
        $oldSession .= '|||'. $message;
        $oldTypes .= '|||'.$level;
        $request->setSession('logs',$oldSession);
        $request->setSession('log_types',$oldTypes);

        return true;
    }

    static $alertTypes = [
        'success' => 'success',
        'error' => 'danger',
        //TODO fill all
    ];

    public static function getMyAlerts(Request $request) {
        $messages = $request->session('logs');
        $levels = $request->session('log_types');
        if (!$messages)
            return [];
        $messages = explode('|||',$messages);
        $levels = explode('|||',$levels);

        $alerts = [];

        foreach ($messages as $key => $message) {
            if ($key !== 0)
                $alerts[] = ['message' => $message, 'type' => static::$alertTypes[$levels[$key]]];
        }

        $request->unsetSession('logs');
        $request->unsetSession('log_types');
        return $alerts;
    }

    public static function init()
    {

    }
}