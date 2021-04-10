<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Http;
use App\Jobs\dispatchMessageToSubscribers;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allMessages = Message::all();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Messages returned successfully',
            'data' => $allMessages

        ], 200);
    }

    public function validateParameters(Request $request, $topic)
    {
        $targetTopic = Topic::find($topic);

        if ($targetTopic) {

            $validator = Validator::make($request->all(), [
                'message' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => '403',
                    'status' => 'error',
                    'message' => [
                        'Fix the following parameter error(s) and retry',
                        $validator->errors()
                    ]
                    ],403);
            }
            (object) $payload = ([
                'topic' => $targetTopic->topic,
                'message' => $request->message,
            ]);
            $payloadObject = (object) $payload; // convert payload array to object

            return $this->publish($targetTopic, $payloadObject); // Proceed to store message and publish to topic subscribers

        } else {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Topic not found'
            ], 404);
        }
    }

    // method to save the message and publish to subscribers
    public function publish($targetTopic, $payload)
    {
        try {
            $addNewMessageToTopic = Message::create([
                'message' => $payload->message,
                'topic_id' => $targetTopic->id
            ]); // basic storage implementation

            if ($addNewMessageToTopic) {

                 // check for zero (0) subscribers for the target topic

                 $totalNumberOfSubscribers = $targetTopic->subscribers->count();
                 if ($totalNumberOfSubscribers == 0) {
                    return response()->json([
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Meassge added successfully but the topic has no subscribers',
                        'data' => [
                            'topic' => $payload->topic,
                            'message' => $payload->message
                        ]
                    ], 200);
                }
                // I Used database queue for this application,
                // I recommend Redis or a third party service for large - enterprice Applications
                // Dispatch using queues as a scalable approach

                dispatchMessageToSubscribers::dispatch($targetTopic, $payload);

                    return response()->json([
                        'code' => 201,
                        'status' => 'success',
                        'message' => 'Massge added successfully and published to all subscribers',
                        'data' => [
                            'topic' => $payload->topic,
                            'message' => $payload->message
                        ]
                    ], 201);

            } else {
                return response()->json([
                    'code' => 501 ,
                    'status' => 'error',
                    'message' => 'Message was not created'
                ], 501 );
            }
        } catch (\Exception $err) { // catch and return unhandled exceptions
            return response()->json([
                'code' => 501 ,
                'status' => 'error',
                'message' =>  $err
            ],501 );
        }
    }
}
