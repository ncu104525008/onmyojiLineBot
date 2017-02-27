<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Stage;
use App\Monster;

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

    public function monster()
    {
        $monsters = Monster::all();

        return view('admin.monster', compact('monsters'));
    }

    public function addStage()
    {
        $name = Input::get('name');

        if (strlen($name) > 0) {
            $stage = new Stage;
            $stage->name = $name;
            $stage->save();
        }
    }

    public function addMonster()
    {
        $name = Input::get('name');

        if (strlen($name) > 0) {
            $monster = new Monster;
            $monster->name = $name;
            $monster->save();
        }
    }
}
