<?php

namespace Cobonto\Telegram;

use Illuminate\Support\ServiceProvider;

class TelegramServiceProvider extends ServiceProvider
{
    public $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //
       // if (!$this->app->routesAreCached())

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
        {
            require __DIR__ . '/routes/webhook.php';
        }
        $this->app->singleton('telegram', function () {

            $telegram = new Telegram($this->app['config']->get('telegram.api_key'), $this->app['config']->get('telegram.bot_username'));
            $telegram->enableAdmins($this->app['config']->get('telegram.admins'));
            $telegram->addCommandsPaths($this->app['config']->get('telegram.commands.paths'));
            $telegram->enableLimiter();
            return $telegram;
        });
    }

    public function provides()
    {
        return ['telegram'];
    }
}