<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class TopicController extends Controller
{

    public function index()
    {
        $allTopics = Topic::all();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Topics returned successfully',
            'data' => $allTopics

        ], 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|unique:topics|max:255'
        ]);

        if ($validator->fails()){
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => [
                    'Fix the following parameter error(s) and retry',
                    $validator->errors()
                ]
                ],422);
        }

        try {
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
                    'code' => 501,
                    'status' => 'error',
                    'message' => 'Topic was not created'
                ], 501);
            }
        } catch (\Exception $err) { // catch unhandled exceptions
            return response()->json([
                'code' => 501,
                'status' => 'error',
                'message' => 'Topic was not created'
            ], 501);
        }

    }

    public function show($topic)
    {
        $targetTopic = Topic::find($topic);


        if ($targetTopic) {
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Topic returned successfully',
                'data' => $targetTopic
            ], 200);
        } else {
            return response()->json([
                'code' => 404,
                'status' => 'success',
                'message' => 'Topic not found'
            ], 404);
        }

    }


}
