<?php

namespace App\Http\Controllers;

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
        // Log::info(json_encode($request));
        // $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        // $out->writeln("Hello from Terminal");
        // return view('test')->with($request);

        return redirect()->route('/test', [$request]);
    }
}
