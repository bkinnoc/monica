<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as PasswordRules;

class AppHelper
{
    /**
     * Get Password Rules
     *
     * @param  mixed $forceProduction
     * @return void
     */
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

    /**
     * Get Registration Validation Rules
     *
     * @param  mixed $data
     * @return void
     */
    public static function getRegistrationValidationRules($data)
    {
        $beforePeriod = nova_get_setting('minimum_age', 13) . ' years ago';
        return Validator::make($data, array_filter([
            'last_name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'email' => ['required', 'email', 'max:255', 'unique:users', new \App\Rules\BadWord],
            'charity_preference' => 'nullable|exists:charities,id',
            'password' => [
                'required', 'confirmed',
                'max:' . config('app.password_max', 32),
                static::getPasswordRules(true)
            ],
            'mailbox_key' => app()->environment('testing') ? null : 'sometimes|unique:mailcow.mailbox,username',
            'policy' => 'required',
            'dob' => "sometimes|before:{$beforePeriod}"
        ]));
    }
}