<?php

namespace Tests\Feature;


use Tests\TestCase;
use Illuminate\Http\Response;
use App\Models\Topic;


class TopicTest extends TestCase
{
   
    public function testToLoadHomePageSuccessfully()
    {
        $response = $this->get('/');

        $response ->assertStatus(200);
    }

    public function testForRequiredFieldsForTopicCreation()
    {
        $payload = [
            'topic' => ''
        ];
        $this->json('POST', '/api/topics',$payload, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'code'=> 422,
                'status'=> 'error',
                "message"=> [
                    "Fix the following parameter error(s) and retry",
                    [
                        'topic'=> [
                            "The topic field is required."
                        ]
                    ]
                ]
            ]);
    }
    public function testForTopicCanBeCreatedSuccessfully()
    {

        $payload = [
            'topic' => 'I love Pangaea'
        ];

        $this->json('POST', '/api/topics', $payload)
            ->assertStatus(201)
            ->assertJson([
                'code' => 201,
                'status' => 'success',
                'message' => 'Topic created successfully',
                'data' => ['topic' => 'I love Pangaea']
            ]);
    }


    public function testForEachTopicSearchReturnsCorrectly()
    {

        $topic = Topic::create(
            [
                'topic' => 'I love Pangaea'
            ]
        );

       $this->json('get', "api/topics/$topic->id")
        ->assertStatus(200)
        ->assertJsonFragment( [
            'code'=> 200,
            'status'=> 'success',
            'message'=> 'Topic returned successfully',
            'data'=> [
                'id'=> $topic->id,
                'topic'=>  $topic->topic,
                'created_at'=>  $topic->created_at,
                'updated_at'=>  $topic->updated_at,

            ]
        ]);
    }



}
