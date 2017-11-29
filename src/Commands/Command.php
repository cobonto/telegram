<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 11/29/2017
 * Time: 12:45 PM
 */

namespace Cobonto\Telegram\Commands;


abstract class Command extends \Longman\TelegramBot\Commands\Command
{
    public function sendMessage($chat_id,$message){
       return \Telegram::sendMessage([
            'chat_id'=>$chat_id,
            'text'=>$message,
        ]);
    }
}