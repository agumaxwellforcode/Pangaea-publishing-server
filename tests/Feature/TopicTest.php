<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Topic;

class TopicTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
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
            // ->assertJson(['id' => 1, 'topic' => 'I love Pangaea']);
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

        $response->assertStatus(201);
    }



}
