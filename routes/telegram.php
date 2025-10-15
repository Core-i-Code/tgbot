<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use SergiX44\Nutgram\Nutgram;
use App\Telegram\Middleware\CheckUserRegister;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

$bot->middleware(CheckUserRegister::class);

$bot->onCommand('start', function (Nutgram $bot) {
    $user = $bot->getGlobalData('user');

    if ($user) {
        $message = "سلام {$user->full_name}! 👋\n";
        $message .= "به ربات خوش آمدید!";

        $bot->sendMessage($message);
    } else {
        $bot->sendMessage('خطا در ثبت‌نام کاربر!');
    }
})->description('The start command!');

$bot->onText('profile', function (Nutgram $bot) {
    $user = $bot->getGlobalData('user');

    if ($user) {
        $message = "👤 اطلاعات پروفایل:\n\n";
        $message .= "نام: {$user->first_name}\n";
        $message .= "نام خانوادگی: {$user->last_name}\n";
        $message .= "نام کامل: {$user->full_name}\n";
        $message .= "آیدی تلگرام: {$user->telegram_id}\n";

        if ($user->data && isset($user->data['username'])) {
            $message .= "نام کاربری: @{$user->data['username']}\n";
        }

        $bot->sendMessage($message);
    } else {
        $bot->sendMessage('خطا در دریافت اطلاعات کاربر!');
    }
});


$bot->onGroupChatCreated(function (Nutgram $bot) {
    $bot->sendMessage('test');
});


// $bot->onMessage(function (Nutgram $bot) {

//     $bot->sendMessage("echo :".$bot->message()->getText());


// });
