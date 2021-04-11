<?php

namespace Tests\Feature;


use Tests\TestCase;
use Illuminate\Http\Response;
use App\Models\Topic;


class TopicTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testToLoadHomePageSuccessfully()
    {
        $response = $this->get('/');

        $response ->assertStatus(200);
    }

    public function testRequiredFieldsForTopicCreation()
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
    public function testsTopicsAreCreatedCorrectly()
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

    public function testsTopicsAreCreatedCorrectly2()
    {
        $response = $this->post('/api/topics', ['topic' => 'I love Pangaea']);
        $response->assertStatus(201)
                ->assertJson([
                    'code' => 201,
                    'status' => 'success',
                    'message' => 'Topic created successfully',
                    'data' => ['topic' => 'I love Pangaea']
                ]);
    }

    public function testsEachTopicSearchReturnsCorrectly()
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
        // ->assertJson(
        //     [
        //         'code'=> 200,
        //         'status'=> 'success',
        //         'message'=> 'Topic returned successfully',
        //         'data'=> [
        //             'id'=> 1,
        //             'topic'=> 'I love Pangaea',
        //             'created_at'=> '2021-04-11T13:59:03.000000Z',
        //             'updated_at'=> '2021-04-11T13:59:03.000000Z',

        //         ]
        //     ]
        // );
    }



}
