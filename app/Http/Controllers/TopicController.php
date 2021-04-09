<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;




class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|unique:topics|max:255'
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'Fix the following parameter error(s) and retry',
                    $validator->errors()
                ]
            ]);
        }

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
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        if ($topic) {
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Topic returned successfully',
                'data' => $topic->subscribers()
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
