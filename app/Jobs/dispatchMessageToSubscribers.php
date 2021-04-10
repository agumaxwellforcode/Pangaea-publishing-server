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


class dispatchMessageToSubscribers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private  $targetTopic;
    private  $payload;


    public function __construct($targetTopic, $payload)
    {
        $this->targetTopic = $targetTopic;
        $this->payload = $payload;
    }


    public function handle()
    {
        $targetTopic = $this->targetTopic;
        $payload = $this->payload;

        // Trigger send message job
        try {
            foreach ($targetTopic->subscribers as $subscriber) {

                // http post request to subscriber server using guzzle http cient
                // Basic post request without authentication/validation

                Http::post($subscriber->url . '/recieve-message', [
                    'topic' => $payload->topic,
                    'data' => $payload->message,
                ]);
            }
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
