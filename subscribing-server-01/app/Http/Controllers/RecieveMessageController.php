<?php

namespace App\Http\Controllers;

use App\Models\recievedMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Psy\info;

class RecieveMessageController extends Controller
{

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recieveMessageFromPublisher(Request $request){
        recievedMessage::create([
            'topic' =>  $request->topic,
            'message' =>  $request->message,
        ]);

        // recievedMessage::create([
        //     'topic' => 'test',
        //     'message' => 'message'
        // ]);
        return;



    }

    public function recieveMessageFromPublisherTest(){
        recievedMessage::create([
            'topic' => 'test',
            'message' => 'message'
        ]);

        return;

    }
}
