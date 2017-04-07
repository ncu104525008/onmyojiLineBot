<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $channel_access_token = env('LINE_TOKEN', '');

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

            $url = "https://api.line.me/v2/bot/message/reply";
            $data = ["replyToken" => $receive->events[0]->replyToken, "messages" => array(["type" => "text", "text" => $text])];
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
            if (count($id) == 0)
            {
                return '查無名稱或線索為'  . $order[1] . '的式神';
            }
            else if (count($id) != 1)
            {
                $str = '共有' . count($id) . '筆資料' . PHP_EOL . PHP_EOL;
                foreach ($id as $item) {
                    if (isset($order[2]) && ($order[2] == '全部' || $order[2] == '全' || $order[2] == '詳細' || $order[2] == '詳'))
                        $str = $str . $this->getMonsterAllPlace($item) . PHP_EOL . PHP_EOL;
                    else
                        $str = $str . $this->getMonsterPlace($item) . PHP_EOL . PHP_EOL;

                }

                return $str;
            }
            else
            {
                if (isset($order[2]) && ($order[2] == '全部' || $order[2] == '全' || $order[2] == '詳細' || $order[2] == '詳'))
                    return $this->getMonsterAllPlace($id[0]);
                else
                    return $this->getMonsterPlace($id[0]);
            }
        }

        return '';
    }

    public function getMonsterId($name)
    {
        $count = Monster::where('name', '=', $name)->count();
        switch ($count)
        {
            case 0:
                // 在式神中，沒有完全符合條件的結果
                // 判斷線索中是否有完全符合條件的結果
                $count = MonsterClue::where('clue', '=', $name)->count();
                switch ($count)
                {
                    case 0:
                        // 判斷式神中，部分符合者
                        $count = Monster::where('name', 'LIKE', '%' . $name . '%')->count();
                        switch ($count)
                        {
                            case 0:
                                // 判斷線索中，部分符合者
                                $count = MonsterClue::where('clue', 'LIKE', '%' . $name . '%')->count();
                                switch ($count)
                                {
                                    case 0:
                                        return array();
                                        break;
                                    case 1:
                                        $id = MonsterClue::where('clue', 'LIKE', '%' . $name . '%')->first()->monsterId;
                                        return array($id);
                                        break;
                                    default :
                                        return array();
                                        break;
                                }
                                break;
                            case 1:
                                $id = Monster::where('name', 'LIKE', '%' . $name . '%')->first()->id;
                                return array($id);
                                break;
                            default :
                                $monsters = Monster::where('name', 'LIKE', '%' . $name . '%')->get();
                                $list = array();
                                foreach ($monsters as $monster)
                                {
                                    array_push($list, $monster->id);
                                }
                                return $list;
                                break;
                        }
                        break;
                    case 1:
                        $id = MonsterClue::where('clue', '=', $name)->first()->monsterId;
                        return array($id);
                        break;
                }
                break;
            case 1:
                // 完全符合條件的結果
                $id = Monster::where('name', '=', $name)->first()->id;
                return array($id);
                break;
        }
    }

    public function getMonsterPlace($monsterId)
    {
        $max_number = MonsterDetail::where('monsterId', '=', $monsterId)->orderBy('number', 'desc')->first()->number;
        $datas = DB::table('monster_details')
            ->join('stage_details', 'monster_details.stageDetailId', '=', 'stage_details.id')
            ->join('stages', 'stage_details.stageId', '=', 'stages.id')
            ->select(DB::raw('stages.`name` AS stageName'), DB::raw('stage_details.`name` AS stageDetailName'), DB::raw('MAX(stages.`grade`) AS maxGrade'), DB::raw('COUNT(stages.`grade`) AS countGrade'), DB::raw('monster_details.`number` AS number'))
            ->where('monster_details.monsterId', '=', $monsterId)
            ->where('number', '=', $max_number)
	    ->where('number', '!=', '0')
            ->groupBy('stages.name', 'stage_details.name', 'monster_details.number')
            ->orderBy('stages.id')
            ->get();

        $monsterName = Monster::where('id', '=', $monsterId)->first()->name;
        $str = '查詢  「' . $monsterName . '」 的結果為：';

        foreach ($datas as $data)
        {
            $str = $str . PHP_EOL . $data->stageName;
            if ($data->countGrade == 2)
            {
                $str = $str . '（普+困）';
            }
            else if ($data->maxGrade == 0)
            {
                $str = $str . '（普）';
            }
            else if ($data->maxGrade == 1)
            {
                $str = $str . '（困）';
            }

            $str = $str . ' ' . $data->stageDetailName . ' 數量' . $data->number;
        }

        return $str;
    }

    public function getMonsterAllPlace($monsterId)
    {
        $datas = DB::table('monster_details')
            ->join('stage_details', 'monster_details.stageDetailId', '=', 'stage_details.id')
            ->join('stages', 'stage_details.stageId', '=', 'stages.id')
            ->select(DB::raw('stages.`name` AS stageName'), DB::raw('stage_details.`name` AS stageDetailName'), DB::raw('MAX(stages.`grade`) AS maxGrade'), DB::raw('COUNT(stages.`grade`) AS countGrade'), DB::raw('monster_details.`number` AS number'))
            ->where('monster_details.monsterId', '=', $monsterId)
	    ->where('number', '!=', '0')
            ->groupBy('stages.name', 'stage_details.name', 'monster_details.number')
            ->orderBy('stages.id')
            ->get();

        $monsterName = Monster::where('id', '=', $monsterId)->first()->name;
        $str = '查詢  「' . $monsterName . '」 的結果為：';

        foreach ($datas as $data)
        {
            $str = $str . PHP_EOL . $data->stageName;
            if ($data->countGrade == 2)
            {
                $str = $str . '（普+困）';
            }
            else if ($data->maxGrade == 0)
            {
                $str = $str . '（普）';
            }
            else if ($data->maxGrade == 1)
            {
                $str = $str . '（困）';
            }

            $str = $str . ' ' . $data->stageDetailName . ' 數量' . $data->number;
        }

        return $str;
    }

    public function log($type, $userId)
    {
            $log = new Log;
            $log->type = $type;
            $log->userId = $userId;
            $log->save();
    }

    public function test()
    {

        $channel_access_token = 'aDAamp2Wbzrxk9mCVDTwPETjHilixPqhrBSnrrcLM5gGCy0aG3PvR6OH5K6SkMMcwOEndSy9jnhUsRv8iY/grgm+ZbPCFFTb0epHt5zQam+hwssdzuO8RSH0/51ljjb68cGDrkilW+5aaV0rxrM+TQdB04t89/1O/w1cDnyilFU=';

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

        $order = explode(' ', $text);
        if ($order[0] == '懸賞')
        {
            $text = '妖刀姐姐換帳號囉~現在可以加新的妖刀姐姐當好友了！' . PHP_EOL
                . '之後就以新帳號為主，這邊的4/15就會關閉囉！' . PHP_EOL
                . '附上新的妖刀姐姐的QR CODE給大家掃描～';

            // 準備Post回Line伺服器的資料
            $header = ["Content-Type: application/json", "Authorization: Bearer {" . $channel_access_token . "}"];

            $url = "https://api.line.me/v2/bot/message/push";
            $data = ["to" => $from, "messages" => array(["type" => "text", "text" => $text])];
            $context = stream_context_create(array(
                "http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
            ));
            file_get_contents($url, false, $context);

            $data = ["to" => $from, "messages" => array(["type" => "image", "originalContentUrl" => 'https://i.imgur.com/f5o6MC7.jpg', "previewImageUrl" => 'https://i.imgur.com/6fBtnON.jpg'])];
            $context = stream_context_create(array(
                "http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
            ));
            file_get_contents($url, false, $context);
        }

        return '';
    }
}
