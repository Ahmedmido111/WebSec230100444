<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YourController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcome', [YourController::class, 'showWelcome']);

Route::get('/multiplication-table', function () {
    $j = 5; // or any other value you want to pass
    return view('welcome', compact('j'));
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/multable', function () {
    return view('multable');
});

// Add route for prime numbers
Route::get('/prime', function () {
    return view('prime');
});

Route::resource('users', UserController::class);

// Add route for test controller
Route::get('/test', [TestController::class, 'index']);
