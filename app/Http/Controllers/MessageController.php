<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{
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
        // Get the topic
        // if ($topic->length()) {
        //     # code...
        // } else {
        //     # code...
        // }

        // $checkForId = Topic::where('id','=',$topic);
        $targetTopic = Topic::find($topic);

        if ($targetTopic) {
            // Validate the request payload
            $validator = Validator::make($request->all(), [
                'message' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => [
                        'Fix the following parameter error(s) and retry',
                        $validator->errors()
                    ]
                ]);
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

                $totalNumberOfSubscribers = $targetTopic->subscribers->count(); // count number of subscribers subscribed to the topic / Target audience
                // return response()->json($totalNumberOfSubscribers);

                $totalNumberOfSubscribersProcessed = 0; // initialize subscriber count

                // NB: The following block implements a synchronouse dispatch of message to each subscriber
                // This is only suitable for very small applications such as this but very ineffecient and
                // costly(speed and processing resources) for medium - large - enterprise applications with many subscribers/clients/users
                // Hence the need for asynchroneous dispatch using queues (database. redis, Beanstalkd, ...) becomes a very effecient and scalable approach
                // I'll create another instance (Branch == asynchronous-approach) and implement the dispatch using queues as a scalable approach

                foreach ($targetTopic->subscribers as $subscriber) {

                    $url = $subscriber->url . '/recieve-message';

                    // http post request to subscriber server using guzzle http cient
                    // Basic post request without authentication/validation

                    $sendMessage = Http::post($url, [
                        'topic' => $payload->topic,
                        'message' => $payload->message,
                    ]);
                    if ($sendMessage->status() == 200) {
                        // increment processsed subscriber count by one after every successfull request
                        $totalNumberOfSubscribersProcessed += 1;
                    }
                }

                //check if all subscribers have recieved the message and return corresponding messages to the client

                if ($totalNumberOfSubscribersProcessed == $totalNumberOfSubscribers) {
                    return response()->json([
                        'code' => 201,
                        'status' => 'success',
                        'message' => 'Meassge added and successfully and published to all ' . $totalNumberOfSubscribersProcessed . ' subscribers',
                        'data' => [
                            'topic' => $payload->topic,
                            'message' => $payload->message
                        ]
                    ], 201);
                } else {
                    return response()->json([
                        'code' => 500,
                        'status' => 'error',
                        'message' => 'Meassge added and successfully but was not published to all  subscribers',
                        'data' => [
                            'topic' => $payload->topic,
                            'message' => $payload->message
                        ]
                    ], 500);
                }
            } else {
                return response()->json([
                    'code' => 501,
                    'status' => 'error',
                    'message' => 'Message was not created'
                ], 501);
            }
        } catch (\Exception $err) { // catch and return unhandled exceptions


            return response()->json([
                'Possible error' => 'Meassge was added successfully and published to active subscribers (Live), while others who are inactive could not be sent to ',
                'other errors' => $err
             ], 500);
        }
    }
}
