<?php


namespace Cobonto\Telegram\Controllers;


use Illuminate\Routing\Controller;
use Longman\TelegramBot\Exception\TelegramException;

class WebHookController extends Controller
{
    /**
     * Set up webhook url
     */
    public function setUp(){
        try{
            $result = \Telegram::setWebhook(route(config('telegram.webhook.url')));
            return $result->getDescription();
        }
        catch (TelegramException $e){
            return $e;
        }

    }

    /**
     * unset webhoook url
     */
    public function tearDown()
    {
        try{
            $result = \Telegram::deleteWebhook();
            return $result->getDescription();
        }
        catch (TelegramException $e){
            return $e;
        }
    }


    public function handle(){
            \Telegram::handle();
    }
}