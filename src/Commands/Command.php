<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 11/29/2017
 * Time: 12:45 PM
 */

namespace Cobonto\Telegram\Commands;

use Longman\TelegramBot\Request;
abstract class Command extends \Longman\TelegramBot\Commands\Command
{
    /**
     * @param $chat_id
     * @param $message
     * @param array $options
     * @return mixed
     */
    public function sendMessage($chat_id,$message,$options=[]){

        $data = array_merge(['chat_id'=>$chat_id,
            'text'=>$message],$options);

       return \Telegram::sendMessage($data);
    }
    public function answerInlineQuery($chat_id,$message,$options=[]){

        $data = array_merge(['chat_id'=>$chat_id,
            'text'=>$message],$options);

        return \Telegram::answerInlineQuery($data);
    }
    public function answerCallBackQuery($callback_id,$text,$options=[]){

        $data = array_merge(['callback_query_id'=>$callback_id,
            'text'=>$text],$options);

        return \Telegram::answerCallBackQuery($data);
    }

    /**
     * @param $chat_id
     * @param string $file_id can be file id or url link
     */
    public function sendDocument($chat_id,$file_id,$options=[])
    {
        $data = array_merge(['chat_id'=>$chat_id,
            'document'=>Request::encodeFile($file_id)],$options);
        return \Telegram::sendDocument($data);
    }
}