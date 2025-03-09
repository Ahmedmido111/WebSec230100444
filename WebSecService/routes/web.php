<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YourController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;

Route::middleware(['web'])->group(function () {
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
    Route::resource('grades', GradeController::class);

    // Add route for test controller
    Route::get('/test', [TestController::class, 'index']);

    // Authentication Routes...
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Auth::routes();

    Route::get('/users/profile', [UserController::class, 'showProfile'])->name('users.profile');
    Route::put('/users/update-password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
