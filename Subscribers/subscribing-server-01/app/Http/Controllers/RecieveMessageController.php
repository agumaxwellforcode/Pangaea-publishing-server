<?php

namespace App\Http\Controllers;

use App\Models\recievedMessage;
use App\Events\RealTimeMessage;
use  App\Notifications\RealTimeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use function Psy\info;

class RecieveMessageController extends Controller
{

    // function that recieves the request from the publisher
    // and dispatches the notification event
    public function recieveMessageFromPublisher(Request $request){

        $message = [
            'topic' => $request->topic,
            'message' => $request->message,
        ] ;

        // register the event trigger user
        $user = User::first();
        $user->notify(new RealTimeNotification($message));
        return;
    }

}
