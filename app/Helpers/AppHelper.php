<?php

namespace App\Helpers;

use Illuminate\Validation\Rules\Password as PasswordRules;

class AppHelper
{
    public static function getPasswordRules($forceProduction = false)
    {
        return app()->environment('testing') && !$forceProduction ? PasswordRules::defaults() : PasswordRules::min(
            config('app.password_main', 8)
        )
            ->mixedCase()
            ->numbers()
            ->symbols()
            ->uncompromised();
    }
}