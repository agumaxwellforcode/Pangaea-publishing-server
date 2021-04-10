<?php

namespace App\Jobs;

use App\Models\Message;
use App\Models\Topic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;


class dispatchMessageToSubscriber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    private  $payload;
    private  $subscriber;


    public function __construct($subscriber, $payload)
    {

        $this->payload = $payload;
        $this->subscriber = $subscriber;
    }


    public function handle()
    {

        // Job to handle single subscriber message

        try {
            $response = Http::post($this->subscriber->url . '/recieve-message', [
                'topic' => $this->payload->topic,
                'message' => $this->payload->message,
            ]);

            if($response->status != 200) static::dispatch(
                $this->subscriber, $this->payload
             );
        } catch (\Throwable $exception) {
            if ($this->attempts() > 3) {
                // hard fail after 3 attempts
                throw $exception;
            }

            // requeue this job to be executed in 3 minutes (180 seconds) from now
            $this->release(180);
            return;
        }
    }
}
