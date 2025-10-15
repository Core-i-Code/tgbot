<?php

namespace App\Telegram\Middleware;

use App\Models\User;
use SergiX44\Nutgram\Nutgram;

class CheckUserRegister
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $telegramUser = $bot->user();
        
        if (!$telegramUser) {
            return;
        }

        $telegramId = $telegramUser->id;
        
        // چک کردن وجود کاربر در دیتابیس
        $user = User::where('telegram_id', $telegramId)->first();
        
        if (!$user) {
            // ثبت‌نام کاربر جدید
            $user = User::create([
                'first_name' => $telegramUser->first_name ?? '',
                'last_name' => $telegramUser->last_name ?? '',
                'telegram_id' => $telegramId,
                'step' => 'started',
                'data' => [
                    'username' => $telegramUser->username,
                    'language_code' => $telegramUser->language_code,
                ],
            ]);
        }

        // ذخیره کاربر در context برای استفاده در handlers
        $bot->setGlobalData('user', $user);

        $next($bot);
    }
}
