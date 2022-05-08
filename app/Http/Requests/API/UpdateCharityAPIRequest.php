<?php

namespace App\Http\Requests\API;

use App\Models\User;
use App\Models\Charity;
use Illuminate\Validation\Rule;

class UpdateCharityAPIRequest extends CreateCharityAPIRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Charity::$rules;
        
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     * In the {{field}}.{{rule}} format
     *
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }
}
