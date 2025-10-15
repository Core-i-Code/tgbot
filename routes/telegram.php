<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use SergiX44\Nutgram\Nutgram;
use App\Telegram\Middleware\CheckUserRegister;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

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

    $mainMenuKeyboard = InlineKeyboardMarkup::make()
        ->addRow(
            InlineKeyboardButton::make('👤 پروفایل')->callbackData('profile'),
            InlineKeyboardButton::make('ℹ️ راهنما')->callbackData('help'),
        );

    if ($user) {
        $message = "سلام {$user->full_name}! 👋\n";
        $message .= "به ربات خوش آمدید!";

        $bot->sendMessage($message, reply_markup: $mainMenuKeyboard);
    } else {
        $bot->sendMessage('خطا در ثبت‌نام کاربر!', reply_markup: $mainMenuKeyboard);
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

// Inline keyboard callbacks
$bot->onCallbackQueryData('profile', function (Nutgram $bot) {
    $bot->answerCallbackQuery();

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

        $backKeyboard = InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make('⬅️ بازگشت')->callbackData('back_main')
            );

        $bot->editMessageText($message, reply_markup: $backKeyboard);
    } else {
        $bot->answerCallbackQuery(text: 'خطا در دریافت اطلاعات کاربر!', show_alert: true);
    }
});

$bot->onCallbackQueryData('help', function (Nutgram $bot) {
    $bot->answerCallbackQuery();

    $message = "ℹ️ راهنما:\n\n";
    $message .= "از دکمه‌های زیر برای ناوبری استفاده کنید.\n";
    $message .= "برای شروع دوباره، از دکمه بازگشت استفاده کنید.";

    $backKeyboard = InlineKeyboardMarkup::make()
        ->addRow(
            InlineKeyboardButton::make('⬅️ بازگشت')->callbackData('back_main')
        );

    $bot->editMessageText($message, reply_markup: $backKeyboard);
});

$bot->onCallbackQueryData('back_main', function (Nutgram $bot) {
    $bot->answerCallbackQuery();

    $user = $bot->getGlobalData('user');
    $message = $user
        ? "سلام {$user->full_name}! 👋\nبه ربات خوش آمدید!"
        : 'به ربات خوش آمدید!';

    $mainMenuKeyboard = InlineKeyboardMarkup::make()
        ->addRow(
            InlineKeyboardButton::make('👤 پروفایل')->callbackData('profile'),
            InlineKeyboardButton::make('ℹ️ راهنما')->callbackData('help'),
        );

    $bot->editMessageText($message, reply_markup: $mainMenuKeyboard);
});


$bot->onGroupChatCreated(function (Nutgram $bot) {
    $bot->sendMessage('test');
});


// $bot->onMessage(function (Nutgram $bot) {

//     $bot->sendMessage("echo :".$bot->message()->getText());


// });
