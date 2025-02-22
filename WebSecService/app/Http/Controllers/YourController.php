<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YourController extends Controller
{
    public function showWelcome()
    {
        $j = 5;
        return view('welcome', compact('j'));
    }
}
