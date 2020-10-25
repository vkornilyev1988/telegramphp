<?php


namespace Nekrida\Core;


interface LoggerInterface
{
    public static function init();

    public static function log($level,$message);
}