<?php

return [
    /**
     * if environment in local or develop you can use some of features like set and unset and testing chats id
     * this config prevent third party person to change your system like run set and unset link for your bot
     */
    'debug'=>true,
    /**
     * api key that you get from @botfather in telegram
     */
    'api_key'=>'',
    /**
     * username of your bot
     */
    'bot_username'=>'',

    /**
     * @var array test bot chats for develop
     */
    'admins'=>[
        '',
    ],
    /**
     * Command directory path
     */
    'commands'=>[
        'paths'=>[
            //   app_path('Telegram/Commands'),
            // telegrambot-core predefined commands
            base_path('vendor/longman/telegrambot-core/src/Commands')
        ],
        /**
         * namespace of your custom command path
         * not need to add app namespace
         */
        'namespace'=>'Telegram\\Commands\\',
    ],

    'webhook'=>[
        /**
         * type name of your routes that handle these urls
         */
        'url_set'=>'telegram.webhook.set',
        /**
         * route for unset
         */
        'url_unset'=>'telegram.webhook.unset',
        /**
         * handle webhook in application
         */
        'url'=>'telegram.webhook.handle',
    ],

];