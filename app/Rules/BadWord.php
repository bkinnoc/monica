<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Helpers\ProfanityFilter;

class BadWord implements Rule
{
    protected $badWord;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->badWord = null;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $badWords = cache()->remember('bad-words', app()->environment('testing') ? 0 : 3600, function () {
            return \App\Models\BadWord::pluck('word');
        });
        $profanityFilter =  new ProfanityFilter(config('profanity'), $badWords->all());
        $this->badWord = $profanityFilter->noProfanity($value);
        return $this->badWord === true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans("The [:attribute] value [:input] contains illegal words: :badWord", [
            'badWord' => $this->badWord
        ]);
    }
}