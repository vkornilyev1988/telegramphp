<?php
namespace App\Modules;


use Nekrida\Database\Query;

class TelegramLog extends Query
{
    public const TABLE_NAME = 'logs_telegrams';

    public static function log($user,$action,$telegram,$reason = '') {
        return self::insertSet([
            '"user"' => (int)$user,
            'action' => (int)$action,
            'telegram' => (int)$telegram,
            'reason' => $reason,
        ])->query();
    }
}
