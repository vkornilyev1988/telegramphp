<?php


namespace App\Middleware;


use App\Modules\Telegram;
use Nekrida\Core\Middleware;

class Telegrams extends Middleware
{
    public function exists() {
        $telegram = $this->request->input('id');
        if (!$telegram)
            return false;
        $row = Telegram::select('id')->where('id','=',(int)$telegram)->query()->fetch();
        return !!$row;
    }

    public function possible() {
        $telegram = $this->request->input('id');
        if ($telegram === '0')
            return true;
        if (!$telegram)
            return false;
        $row = Telegram::select('id')->where('id','=',(int)$telegram)->query()->fetch();
        return !!$row;
    }
}