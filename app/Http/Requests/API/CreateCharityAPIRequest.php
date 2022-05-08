<?php

namespace App\Http\Requests\API;

use App\Models\User;
use App\Models\Charity;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class CreateCharityAPIRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(User $user, $model = null)
    {
        return parent::authorize($user, $model);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Charity::$rules;
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
