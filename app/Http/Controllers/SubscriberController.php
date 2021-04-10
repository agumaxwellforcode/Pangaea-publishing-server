<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class SubscriberController extends Controller
{
    public function subscribe(Request $request, $topic)
    {

        // lets validate if the post exists

        $targetTopic = Topic::find($topic);
        if ($targetTopic) {

            // Lets validate the subscriber's url structure using rejex

            // Apply for production level servers (https)
            // $regex = '/^https:\/\/\w+(\.\w+)*(:[0-9]+)?\/?$/';

            // Apply this when server is on local machine (http)

            $regex = '/^http:\/\/\w+(\.\w+)*(:[0-9]+)?\/?$/';

            $validator = Validator::make($request->all(), [
                'url' => 'required|regex:' . $regex
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



            try {
                $createNewSubscription = Subscriber::create([
                    'url' => $request->url,
                    'topic_id' => $topic,
                ]); // basic storage implementation

                if ($createNewSubscription) {

                    return response()->json([
                        'code' => 201,
                        'status' => 'success',
                        'message' => 'Subcriber added successfully',
                        'data' => [
                            'topic' => $targetTopic->topic,
                            'url' => $request->url
                        ]
                    ], 201);
                } else {

                    return response()->json([
                        'code' => 500,
                        'status' => 'error',
                        'message' => 'Subcriber was not created'
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
