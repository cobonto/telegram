<?php

namespace Cobonto\Telegram;

use Longman\TelegramBot\Request;

class Telegram extends \Longman\TelegramBot\Telegram
{
    /**
     * @var string view resource file name
     */
    protected $view_file;
    /**
     * @var \View
     */
    protected $view;
    /**
     * @var array $data;
     */
    protected $view_data = [];
    /**
     * Send message to one or multiple chat ids that specified
     * @param $message
     * @param array $chat_ids
     * @param array $additional_data;
     */
    public function sendMessage(array $chat_ids,$message=null,$additional_data=[])
    {
        if($message==null)
            $message = $this->renderView();
        foreach($chat_ids as $chat_id)
            $response = Request::sendMessage(array_merge(['chat_id' => $chat_id, 'text' => $message],$additional_data));
    }

    public function __call($name, $arguments)
    {
        if(method_exists(Request::class,$name))
            return Request::{$name}($arguments);
    }


    public function setViewFile($view,$data=[]){
        $this->view_file = $view;
        $this->view_data = $data;
        return $this;
    }
    public function renderView()
    {
        if($this->view_file)
           $this->view = view($this->view,$this->view_data)->render();
    }
}
