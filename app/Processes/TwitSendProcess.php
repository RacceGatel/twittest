<?php

namespace App\Processes;
use App\Models\Twit;
use Hhxsv5\LaravelS\Swoole\Process\CustomProcessInterface;
use Hhxsv5\LaravelS\Swoole\Task\Task;
use Illuminate\Support\Facades\Redis;
use Swoole\Coroutine;
use Swoole\Http\Server;
use Swoole\Process;
use Swoole\Table;

class TwitSendProcess implements CustomProcessInterface
{
    /**
     * @var bool Quit tag for Reload updates
     */
    private static $quit = false;

    public static function callback(Server $swoole, Process $process)
    {
        // The callback method cannot exit. Once exited, Manager process will automatically create the process
        while (!self::$quit) {
            Redis::subscribe(['twits'], function ($event) use ($swoole) {
                $json = json_decode($event, true);

                /** @var Table $ws_table */
                $ws_table = $swoole->cat_fdTable;

                $cat_key = "cat_id:{$json['category_id']}_";

                foreach ($ws_table as $key => $row) {
                    if (strpos($key, $cat_key) === 0) {
                        try {
                            $swoole->push($row['value'], json_encode([
                                'new_twit' => $json
                            ], JSON_UNESCAPED_UNICODE));
                        } catch (\Throwable $throwable) {
                            $ws_table->del($key);
                        }
                    }
                }
            });

            Coroutine::sleep(1);
        }
    }
    // Requirements: LaravelS >= v3.4.0 & callback() must be async non-blocking program.
    public static function onReload(Server $swoole, Process $process)
    {
        self::$quit = true;
    }
    // Requirements: LaravelS >= v3.7.4 & callback() must be async non-blocking program.
    public static function onStop(Server $swoole, Process $process)
    {
        self::$quit = true;
    }
}