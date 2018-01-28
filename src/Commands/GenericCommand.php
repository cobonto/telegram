<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 12/9/2017
 * Time: 1:07 PM
 */

namespace Cobonto\Telegram\Commands;


use Longman\TelegramBot\Entities\CallbackQuery;

abstract class GenericCommand extends Command
{
    public function execute()
    {
        if($this->getCallbackQuery())
            return $this->handleCallBackQuery($this->getCallbackQuery());
        elseif($this->getInlineQuery())
            return $this->sendMessage($this->getInlineQuery()->getFrom()->getId(),$this->getInlineQuery());
    }

    protected function handleCallBackQuery(CallbackQuery $query){
        $name = explode('|',$query->getData());
        // validate that we have
        if($this->validate($name[0])){
            //
            $callback_class_name = app()->getNamespace().config('telegram.commands.namespace').'Callbacks\\'.studly_case($name[0]).'Callback';
            if(class_exists($callback_class_name)){
                $callback = new $callback_class_name($this->telegram,$this->update,$this->getCallbackQuery());
                return $callback->execute();
            }
            else
                return $this->answerCallBackQuery($query->getId(),'not found');

        }
        else{
            return $this->answerCallBackQuery($query->getId(),'not found');
        }
    }

    protected function validate($string){
        $validate = \Validator::make([
           'query'=>$string,
        ],[
            'query'=>'required|string',
        ]);
        return !$validate->fails();
    }
}
