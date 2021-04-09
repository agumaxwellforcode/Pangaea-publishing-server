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


    public function publish(Request $request, $topic)
    {
        $targetTopic = Topic::find($topic);
        // return response()->json( $request);
        if ($targetTopic) {

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




            // return response()->json($targetTopic->subscribers);
















            try {
                $addNewMessageToTopic = Message::create([
                    'message' => $request->message,
                    'topic_id' => $topic,
                ]); // basic storage implementation

                if ($addNewMessageToTopic) {

                        $totalNumberOfSubscribers = $targetTopic -> subscribers->count();
                        // return response()->json($totalNumberOfSubscribers);

                        $totalNumberOfSubscribersProcessed = 0;

                        foreach ($targetTopic -> subscribers as $subscriber)
                        {
                            Http::post($subscriber->url.'/recieve-message', [
                                'topic' => $targetTopic->topic,
                                'data' => $request->message,
                            ]);
                            $totalNumberOfSubscribersProcessed += 1;

                        }
                        if ($totalNumberOfSubscribersProcessed == $totalNumberOfSubscribers) {
                            return response()->json([
                            'code' => 201,
                            'status' => 'success',
                            'message' => 'Meassge added and successfully but was not published to all '.$totalNumberOfSubscribersProcessed.' subscribers',
                            'data' => [
                                'topic' => $targetTopic->topic,
                                'message' => $request->message
                            ]
                        ], 201);
                        } else {
                            return response()->json([
                                'code' => 500,
                                'status' => 'error',
                                'message' => 'Meassge added and successfully but was not published to all  subscribers',
                                'data' => [
                                    'topic' => $targetTopic->topic,
                                    'message' => $request->message
                                ]
                            ], 500);
                        }


                } else {

                    return response()->json([
                        'code' => 500,
                        'status' => 'error',
                        'message' => 'Message was not created'
                    ], 500);
                }
            } catch (\Exception $err) {

                return response()->json([
                    'code' => 500,
                    'status' => 'error',
                    'message' => $err
                ], 500);
            }
        } else {

            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Topic not found'
            ], 404);
        }
    }


 }
