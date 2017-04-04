<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Stage;
use App\StageDetail;
use App\Monster;
use App\MonsterDetail;
use App\MonsterClue;
use App\Log;

class LineController extends Controller
{
    public function callback()
    {
        $channel_access_token = "aDAamp2Wbzrxk9mCVDTwPETjHilixPqhrBSnrrcLM5gGCy0aG3PvR6OH5K6SkMMcwOEndSy9jnhUsRv8iY/grgm+ZbPCFFTb0epHt5zQam+hwssdzuO8RSH0/51ljjb68cGDrkilW+5aaV0rxrM+TQdB04t89/1O/w1cDnyilFU=";

        // 將收到的資料整理至變數
        $receive = json_decode(file_get_contents("php://input"));

        // 讀取收到的訊息內容
        $text = $receive->events[0]->message->text;

        // 讀取訊息來源的類型 	[user, group, room]
        $type = $receive->events[0]->source->type;

        // 由於新版的Messaging Api可以讓Bot帳號加入多人聊天和群組當中
        // 所以在這裡先判斷訊息的來源
        if ($type == "room")
        {
            // 多人聊天 讀取房間id
            $from = $receive->events[0]->source->roomId;
        }
        else if ($type == "group")
        {
            // 群組 讀取群組id
            $from = $receive->events[0]->source->groupId;
        }
        else
        {
            // 一對一聊天 讀取使用者id
            $from = $receive->events[0]->source->userId;
        }

        $this->log($type, $from);
        $text = $this->getReturnMessage($text);

        if (strlen($text) > 0)
        {
            // 準備Post回Line伺服器的資料
            $header = ["Content-Type: application/json", "Authorization: Bearer {" . $channel_access_token . "}"];

            $url = "https://api.line.me/v2/bot/message/push";
            $data = ["to" => $from, "messages" => array(["type" => "text", "text" => $text])];;
            $context = stream_context_create(array(
                "http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
            ));
            file_get_contents($url, false, $context);
        }
        return '';
    }

    public function getReturnMessage($text)
    {
        $order = explode(' ', $text);
        if ($order[0] == '懸賞')
        {
            $id = $this->getMonsterId($order[1]);
            if ($id == -1)
            {
                return '查無名稱或線索為'  . $order[1] . '的式神';
            }
            else
            {
                if (isset($order[2]) && ($order[2] == '全部' || $order[2] == '全' || $order[2] == '詳細' || $order[2] == '詳'))
                    return $this->getMonsterAllPlace($id);
                else
                    return $this->getMonsterPlace($id);
            }
        }

        return '';
    }

    public function getMonsterId($name)
    {
        $count = Monster::where('name', 'LIKE', '%' . $name . '%')->count();

        if ($count == 1)
        {
            $id = Monster::where('name', 'LIKE', '%' . $name . '%')->first()->id;
            return $id;
        }
        else
        {
            $count = MonsterClue::where('clue', '=', $name)->count();
            if ($count == 1)
            {
                $id = MonsterClue::where('clue', '=', $name)->first()->monsterId;
                return $id;
            }
            return -1;
        }
    }

    public function getMonsterPlace($monsterId)
    {
        $max_number = MonsterDetail::where('monsterId', '=', $monsterId)->orderBy('number', 'desc')->first()->number;
        $datas = MonsterDetail::where('monsterId', '=', $monsterId)->where('number', '=', $max_number)->get();

        $monsterName = Monster::where('id', '=', $monsterId)->first()->name;

        $str = '查詢  「' . $monsterName . '」 的結果為：';
        $arr = array();
        foreach ($datas as $data)
        {
            $id = $data->stageDetailId;
            $detail = StageDetail::where('id', '=', $id)->first();
            $stageName = Stage::where('id', '=', $detail->stageId)->first()->name;

            if (strlen($str) > 0)
            {
                array_push($arr, array('stageName' => $stageName, 'detailName' => $detail->name, 'number' => $data->number));
//                $str = $str . PHP_EOL . $stageName . ' ' . $detail->name . ' 數量 ' . $data->number;
            }
        }

        $arr2 = array();
        $count_arr = array();
        foreach ($arr as $data)
        {
            $stageName = explode('-', $data['stageName']);

            if (strpos($data['stageName'], '-') > -1 && $stageName[1] == '困難')
            {
                $count = 0;
                $flag = true;
                foreach ($arr2 as $data2)
                {
                    if (strpos($data2['stageName'], $stageName[0]) > -1 && $data['detailName'] == $data2['detailName'])
                    {
                        $flag = false;
                        array_push($count_arr, $count);
                        break;
                    }
                    $count++;
                }
                if ($flag == true)
                {
                    array_push($arr2, array('stageName' => $data['stageName'], 'detailName' => $data['detailName'], 'number' => $data['number']));
                }
            }
            else
            {
                array_push($arr2, array('stageName' => $data['stageName'], 'detailName' => $data['detailName'], 'number' => $data['number']));
            }
        }

        foreach ($count_arr as $count)
        {
            $stageName = explode('-', $arr2[$count]['stageName']);
            $arr2[$count]['stageName'] = $stageName[0] . '-(簡+困)';
        }

        foreach ($arr2 as $data)
        {
            $str = $str . PHP_EOL . $data['stageName'] . ' ' . $data['detailName'] . ' 數量' . $data['number'];
        }
        return $str;
    }

    public function getMonsterAllPlace($monsterId)
    {
        $datas = MonsterDetail::where('monsterId', '=', $monsterId)->get();

        $str = '';
        foreach ($datas as $data)
        {
            $id = $data->stageDetailId;
            $detail = StageDetail::where('id', '=', $id)->first();
            $stageName = Stage::where('id', '=', $detail->stageId)->first()->name;
            if (strlen($str) > 0)
                $str = $str . PHP_EOL . $stageName . ' ' . $detail->name . ' 數量 ' . $data->number;
            else
                $str = $stageName . ' ' . $detail->name . ' 數量 ' . $data->number;
        }
        return $str;
    }

    public function log($type, $userId)
    {
       // $count = Log::where('type', '=' , $type)->where('userId', '=', $userId)->count();
       // if ($count == 0)
       // {
            $log = new Log;
            $log->type = $type;
            $log->userId = $userId;
            $log->save();
       // }
    }
}
