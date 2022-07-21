<?php

namespace App\Helpers;

use Illuminate\Validation\Rules\Password as PasswordRules;

class AppHelper
{
    public static function getPasswordRules()
    {
        return app()->environment('testing') ? PasswordRules::defaults() : PasswordRules::min(
            config('app.password_main', 8)
        )
            ->mixedCase()
            ->numbers()
            ->symbols()
            ->uncompromised();
    }
}