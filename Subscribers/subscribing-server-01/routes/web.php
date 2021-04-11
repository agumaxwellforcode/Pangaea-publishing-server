<?php

use Illuminate\Support\Facades\Route;
use App\Events\RealTimeMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;




Route::get('/', function () {
    Auth::login(User::first());
    return view('welcome');
});

// route/ Endpoint to consumed by the publisher
Route::post('/recieve-message', 'App\Http\Controllers\RecieveMessageController@recieveMessageFromPublisher')->name('recieve-message');




