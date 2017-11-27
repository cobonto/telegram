<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 11/27/2017
 * Time: 11:01 AM
 */

namespace Cobonto\Telegram\Facades;


use Illuminate\Support\Facades\Facade;

class Telegram extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'telegram';
    }
}