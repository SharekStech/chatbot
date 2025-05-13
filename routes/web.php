<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/chat', [ChatController::class, 'chat']);
Route::get('/chat-ui', function () {
    return view('chat');
});

