<?php

namespace Cobonto\Telegram;

use Longman\TelegramBot\DB;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
class Telegram extends \Longman\TelegramBot\Telegram
{
    /**
     * @var string view resource file name
     */
    protected $view_file = false;
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
    public function sendMessageToAdmins(array $chat_ids,$message=null,$additional_data=[])
    {
        if($message==null)
            $message = $this->renderView();
        foreach($chat_ids as $chat_id)
            $response = Request::sendMessage(array_merge(['chat_id' => $chat_id, 'text' => $message],$additional_data));
    }
    public function sendMessage($data)
    {
        return Request::sendMessage($data);
    }
    public function answerInlineQuery($data)
    {
        return Request::answerInlineQuery($data);
    }
    public function answerCallBackQuery($data)
    {
        return Request::answerCallbackQuery($data);
    }
    public function sendDocument($data)
    {
        return Request::sendDocument($data);
    }
    public function __call($name, $arguments)
    {
        if(method_exists(Request::class,$name))
            return Request::{$name}($arguments);
    }

    public function getChats()
    {
        $chats = [];
        $response = Request::getUpdates(
            [
                'offset'  => null,
                'limit'   => null,
                'timeout' => null,
                'allowed_updates'=>['message'],
            ]
        );

        if($response->isOk()){
            foreach($response->getResult() as $result){
                $chats[] = ($result->message['chat']['id']);

            }
        }
       return $chats;
    }
    public function setViewFile($view,$data=[]){
        $this->view_file = $view;
        $this->view_data = $data;
        return $this;
    }
    public function renderView()
    {
        if($this->view_file)
          return view($this->view_file,$this->view_data)->render();
    }

    /**
     * Get an object instance of the passed command
     *
     * @param string $command
     *
     * @return \Longman\TelegramBot\Commands\Command|null
     */
    public function getCommandObject($command)
    {
        $which = ['System'];
        $this->isAdmin() && $which[] = 'Admin';
        $which[] = 'User';
        foreach ($which as $auth) {
            $base_command_namespace =   '\\Longman\\TelegramBot\\Commands\\' . $auth . 'Commands\\' . $this->ucfirstUnicode($command) . 'Command';
            $app_command_namespace =   app()->getNamespace().config('telegram.commands.namespace'). $auth . 'Commands\\' . $this->ucfirstUnicode($command) . 'Command';
            if(class_exists($app_command_namespace)){
                return new $app_command_namespace($this, $this->update);
            }
            /**elseif (class_exists($base_command_namespace)) {
                return new $base_command_namespace($this, $this->update);
            }*/
        }

        return null;
    }

    /**
     * Process bot Update request
     *
     * @param \Longman\TelegramBot\Entities\Update $update
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function processUpdate(Update $update)
    {
        $this->update = $update;

        //If all else fails, it's a generic message.
        $command = 'genericmessage';

        $update_type = $this->update->getUpdateType();
        if ($update_type === 'message') {
            $message = $this->update->getMessage();

            //Load admin commands
            if ($this->isAdmin()) {
                $this->addCommandsPath(BASE_COMMANDS_PATH . '/AdminCommands', false);
            }

            $type = $message->getType();
            if ($type === 'command') {
                $command = $message->getCommand();
            } elseif (in_array($type, [
                'new_chat_members',
                'left_chat_member',
                'new_chat_title',
                'new_chat_photo',
                'delete_chat_photo',
                'group_chat_created',
                'supergroup_chat_created',
                'channel_chat_created',
                'migrate_to_chat_id',
                'migrate_from_chat_id',
                'pinned_message',
                'invoice',
                'successful_payment',
            ], true)
            ) {
                $command = $this->getCommandFromType($type);
            }
        } else {
            $command = $this->getCommandFromType($update_type);
        }
        //Make sure we have an up-to-date command list
        //This is necessary to "require" all the necessary command files!
        //@todo i dont get it what is it and if in laravel we do that we have fatal Error exception
        // $this->getCommandsList();

        DB::insertRequest($this->update);

        return $this->executeCommand($command);
    }

    // create cache for manage GenericmessageCommand
    /**
     * @param int $chat_id
     * @param null|string $command
     * @param array $data // contain controller name and method name
     */
    public function cacheLastCommand($chat_id,$command,array $data=[])
    {
        \Cache::forget('last_telegram_command_'.$chat_id);
       return \Cache::remember('last_telegram_command_'.$chat_id,30000,function() use($command,$data){
            return [
                'command'=>$command,
                'data'=>$data,
            ];
        });
    }
}