<?php


namespace App\Modules;


use Nekrida\Database\Query;

class Telegram extends Query
{
    const STATUS_ACCEPTED = 8;
    const STATUS_CONFIRMED = 12;
    const STATUS_DELETED = 3;
    const STATUS_ON_SIGN = 2;
    const STATUS_RECALLED = 4;
    const STATUS_REJECTED = 9;
    const STATUS_RETURNED = 5;
    const STATUS_SAVED = 1;
    const STATUS_SENT = 11;
    const STATUS_SENT_TO_TELEGRAPHIST = 7;
    const STATUS_SIGNED = 6;
    public const TABLE_NAME = 'telegrams';
}