<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Stage;

class AdminController extends Controller
{
    public function main()
    {
        return view('admin.main');
    }

    public function stage()
    {
        $stages = Stage::all();

        return view('admin.stage', compact('stages'));
    }

    public function addStage()
    {
        $name = Input::get('name');

        $stage = new Stage;
        $stage->name = $name;
        $stage->save();

        return $name;
    }
}
