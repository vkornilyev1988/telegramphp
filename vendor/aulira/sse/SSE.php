<?php

namespace SSE;

require __DIR__ . '/../nekrida/Core/Core.php';

use Modules\Chat\Message;
use Nekrida\Auth\User;
use Nekrida\Core\Core;
use Nekrida\Database\Query;

class SSE
{
    public function run($room) {
        $app = new Core();
        $app->prepare(__DIR__.'/../../../');

        //TODO make fallback
        if ($_SERVER['HTTP_ACCEPT'] !== 'text/event-stream') {
            exit();
        }

        ignore_user_abort(true);

        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        header("Access-Control-Allow-Origin: *");

        //EventId = last Chat message id
        $lastEventId = intval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0);

        if ($lastEventId === 0) {
            $lastEventId = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : 0);
        }

        echo ":".str_repeat(" ", 2048)."\n";
        echo "retry: 3000\n";

        $ping = 0;
        $ticks = 0;
        while (true) {
            if (connection_aborted()) {
                exit();
            } else {
                //TODO replace with function
                $latestEventId = Query::select('id')
                    ->table('messages')
                    ->where('id','>','?')
                    ->limit(1)
                    ->query([$lastEventId])
                    ->fetchColumn();

                //If we have something to send to the client, send it. Else just ping, so he wouldn't break the connection.
                if (!empty($latestEventId) && $lastEventId < $latestEventId) {
                    $data = [];
                    $st = Message::get(['u.name', 'author', 'm.id', 'm.text','m.time'])
                        ->join(User::class)->onA('author','=','u.id')
                        ->where('room','=','?')
                        ->where('m.id','>=','?')->orderBy(['m.id'])->query([$room,$latestEventId]);
                    foreach ($st->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                        $data[] = $row;
                        $lastEventId = $row['id'];
                    }
                    //Event is what event your js is listening via function "on". Default: message;
                    echo "event: message\n";
                    echo "data: ".json_encode($data)."\n";
                    //Double \n means end of the message.
                    echo "id: ". $lastEventId . "\n\n";
                } else {

                    if ($ping % 10 == 0) {
                        $ping = 0;
                        echo "event: ping\n\n";
                    }
                    $ping++;
                }
            }
            ob_flush();
            flush();

            sleep(1);

            //Reset connection every 6 hours
            if ($ticks > 21460)
                exit();

            $ticks++;
        }
    }
}