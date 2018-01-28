<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 12/9/2017
 * Time: 4:43 PM
 */

namespace Cobonto\Telegram\Callbacks;


use Cobonto\Telegram\Commands\Command;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;

abstract class CallbackQuery extends Command
{
    /**
     * @var CallbackQuery
     */
    protected  $callback;

    protected $callback_id ;
    /**
     * @var array $callback_data
     */
    protected $callback_data;
    public function __construct(Telegram $telegram, Update $update = null,\Longman\TelegramBot\Entities\CallbackQuery $callbackQuery)
    {
        parent::__construct($telegram, $update);
        $this->callback = $callbackQuery;
        $this->callback_id   = $callbackQuery->getId();
        $this->callback_data = explode('|',$callbackQuery->getData());
    }
}