<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password as PasswordRules;

class PasswordChangeRequest extends AuthorizedRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password_current' => 'required',
            'password' => [
                'required',
                'confirmed',
                'max:' . config('app.password_max', 32),
                PasswordRules::min(config('app.password_main', 8))
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ];
    }
}