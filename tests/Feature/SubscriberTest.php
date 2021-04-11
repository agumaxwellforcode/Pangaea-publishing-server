<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Models\Subscriber;
use App\Models\Topic;

class SubscriberTest extends TestCase
{


    public function testForSubscriberCanSuccessfullySubscribeToATopic()
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
            'url' => 'http://localhost:4000',
        ];


        $this->json('POST', '/api/subscribe/'.$topic->id,$payload, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                'code'=> 201,
                'status'=> 'success',
                'message'=> 'Subcriber added successfully',
                'data'=> [
                    'topic'=>  $topic->topic,
                    'url'=>  $subscriber->url,
                ]
            ]);
    }
}
