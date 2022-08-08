<?php

namespace App\Jobs;

use App\Models\Twit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SendTwitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private Twit $twit;

    /**
     * Создание твита
     *
     * @param Twit $twit
     */
    public function __construct(Twit $twit)
    {
        $this->queue = 'send_twits';
        $this->twit = $twit;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $this->twit->created_at = now();
            $this->twit->push();

            Redis::publish('twits', json_encode($this->twit));
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
    }
}
