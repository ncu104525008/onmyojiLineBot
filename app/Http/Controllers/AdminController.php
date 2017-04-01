<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Session;

use App\Stage;
use App\StageDetail;
use App\Monster;
use App\MonsterDetail;

class AdminController extends Controller
{
    public function main()
    {
        Session::flush();
        return view('admin.main');
    }

    public function stage()
    {
        if (Session::has('id'))
        {
            $stages = Stage::all();

            return view('admin.stage', compact('stages'));
        }
        else
        {
            return view('admin.main');
        }
    }

    public function stageDetail()
    {
        if (Session::has('id'))
        {
            $stage_list = Stage::all();
            $stages = array();
            foreach ($stage_list as $item)
            {
                $stages[$item->id] = $item->name;
            }

            $details = StageDetail::all();
            foreach ($details as $detail)
            {
                $stageName = Stage::where('id', $detail->stageId)->first()->name;
                $detail->stageName = $stageName;
            }

            return view('admin.stage_detail', compact('stages', 'details'));
        }
        else
        {
            return view('admin.main');
        }
    }

    public function monster()
    {
        if (Session::has('id'))
        {
            $monsters = Monster::all();

            return view('admin.monster', compact('monsters'));
        }
        else
        {
            return view('admin.main');
        }
    }

    public function monsterDetail()
    {
        if (Session::has('id'))
        {
            $stage_detail_list = StageDetail::all();
            $stage_details = array();
            foreach ($stage_detail_list as $item)
            {
                $name = Stage::where('id', $item->stageId)->first()->name;
                $stage_details[$item->id] = $name . '-' . $item->name;
            }

            $monster_list = Monster::all();
            $monsters = array();
            foreach ($monster_list as $item)
            {
                $monsters[$item->id] = $item->name;
            }

            $monster_detail_list = MonsterDetail::all();
            foreach ($monster_detail_list as $item) {
                $item->stageDetail = $stage_details[$item->stageDetailId];
                $item->monster = $monsters[$item->monsterId];
            }

            return view('admin.monster_detail', compact('stage_details', 'monsters', 'monster_detail_list'));
        }
        else
        {
            return view('admin.main');
        }
    }

    public function addStage()
    {
        if (Session::has('id'))
        {
            $name = Input::get('name');

            if (strlen($name) > 0) {
                $stage = new Stage;
                $stage->name = $name;
                $stage->save();
            }
        }
    }

    public function addStageDetail()
    {
        if (Session::has('id'))
        {
            $stageId = Input::get('stageId');
            $name = Input::get('name');

            if (strlen($name) > 0) {
                $detail = new StageDetail;
                $detail->stageId = $stageId;
                $detail->name = $name;
                $detail->save();
            }
        }
    }

    public function addMonster()
    {
        if (Session::has('id'))
        {
            $name = Input::get('name');

            if (strlen($name) > 0) {
                $monster = new Monster;
                $monster->name = $name;
                $monster->save();
            }
        }
    }

    public function addMonsterDetail()
    {
        if (Session::has('id'))
        {
            $stageDetailId = Input::get('stageDetailId');
            $monsterId = Input::get("monsterId");
            $number = Input::get('number');

            if ($number > 0) {
                $detail = new MonsterDetail;
                $detail->stageDetailId = $stageDetailId;
                $detail->monsterId = $monsterId;
                $detail->number = $number;
                $detail->save();
            }
        }
    }
}
