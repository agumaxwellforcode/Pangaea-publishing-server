<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;





// Route for Topic controllers
Route::apiResource('topics', 'App\Http\Controllers\TopicController');


Route::post('subscribe/{topic}', 'App\Http\Controllers\SubscriberController@subscribe')->name('subscribe');


Route::post('publish/{topic}', 'App\Http\Controllers\MessageController@publish')->name('publish');

