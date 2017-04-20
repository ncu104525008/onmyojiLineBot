<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FBController extends Controller
{
    public function callback()
    {
        return $_GET['hub_challenge'];
//        return view('fb.callback');
    }
}
