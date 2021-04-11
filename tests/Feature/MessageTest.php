<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Topic;
use App\Models\Message;
use App\Models\Subscriber;

class MessageTest extends TestCase
{




    public function testForMessageCanSuccessfullyBeDispatchedToAllSubscribersOfTheTargetTopic()
    {

        $topic = Topic::create(
            [
                'topic' => 'I love Pangaea'
            ]
        );
        $subscriber = Subscriber::create(
            [
                 'url' => 'http://localhost:4000',
                 'topic' => 'I love tests'
            ]
        );
        $payload = [
            'message' => 'Dispatch test message',
        ];


        $this->json('POST', '/api/publish/'.$topic->id,$payload, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
