<?php
if(config('telegram.debug')){
    Route::get('telegram/webhook/set/'.Config::get('telegram.api_key'),'\Cobonto\Telegram\Controllers\WebHookController@setUp')->name('telegram.webhook.set');
    Route::get('telegram/webhook/unset','\Cobonto\Telegram\Controllers\WebHookController@tearDown')->name('telegram.webhook.unset');
}
Route::any('telegram/webhook/handle','\Cobonto\Telegram\Controllers\WebHookController@handle')->name('telegram.webhook.handle');

