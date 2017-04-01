<?php
	/* 輸入申請的Line Developers 資料  */
	$channel_id = "1508301515";
	$channel_secret = "a057af36d50df3edeb5f590fe8e5871d";
	$channel_access_token = "aDAamp2Wbzrxk9mCVDTwPETjHilixPqhrBSnrrcLM5gGCy0aG3PvR6OH5K6SkMMcwOEndSy9jnhUsRv8iY/grgm+ZbPCFFTb0epHt5zQam+hwssdzuO8RSH0/51ljjb68cGDrkilW+5aaV0rxrM+TQdB04t89/1O/w1cDnyilFU=";
	$myURL = "https://sang0.nctu.me/line/callback";

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

    // 讀取訊息的型態 [Text, Image, Video, Audio, Location, Sticker]
    $content_type = $receive->events[0]->message->type;

    // 準備Post回Line伺服器的資料
    $header = ["Content-Type: application/json", "Authorization: Bearer {" . $channel_access_token . "}"];

    // 回覆訊息
    reply($text, $from, $header);

    function reply($message, $from, $header) {
        $url = "https://api.line.me/v2/bot/message/push";

        if ($message == '聖滄是誰') {
            $message = 'RO的ID為「水水倉」，是東海大學楊朝棟的研究生，明諺的前男友。';
        } else {
            $message = '彭倧輢（ㄧˇ）是白癡...';
        }

        $data = ["to" => $from, "messages" => array(["type" => "text", "text" => $message])];;
        $context = stream_context_create(array(
                "http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
        ));
        file_get_contents($url, false, $context);
    }
?>
