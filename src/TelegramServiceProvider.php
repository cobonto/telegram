<?php

namespace Cobonto\Telegram;

use Illuminate\Support\ServiceProvider;

class TelegramServiceProvider extends ServiceProvider
{
    public $defer = true;
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $configPath = __DIR__ . '/../config/telegram.php';
        $publishPath = config_path('telegram.php');
        $this->publishes([$configPath => $publishPath], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('telegram',function (){
           return new Telegram($this->app['config']->get('telegram.api_key'),$this->app['config']->get('telegram.bot_username'));
        });
    }

    public function provides()
    {
        return ['telegram'];
    }
}