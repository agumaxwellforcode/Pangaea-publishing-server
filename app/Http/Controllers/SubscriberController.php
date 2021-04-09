<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class SubscriberController extends Controller
{


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request, $topic)
    {

        // lets validate if the post exists

        $targetTopic = Topic::find($topic);
        if ($targetTopic) {

            // Lets validate the subscriber's url using rejex

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























































































































































    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'topic' => 'required|unique:topics|max:255',
            'body' => 'required',
        ])->validate();

        $createNewTopic = Topic::create($request->all()); // basic storage implementation
        // Return success message if topic was added successfully
        if ($createNewTopic) {
            return response()->json([
                'code' => 201,
                'status' => 'success',
                'message' => 'Topic created successfully',
                'data' => $request->all()
            ], 201);
        } else {
            return response()->json([
                'code' => 500,
                'status' => 'error',
                'message' => 'Topic was not created'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function show(Subscriber $subscriber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscriber $subscriber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscriber $subscriber)
    {
        //
    }
}
