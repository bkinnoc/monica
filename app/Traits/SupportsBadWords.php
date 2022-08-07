<?php

namespace App\Traits;

use App\Models\User\User;

trait SupportsBadWords
{
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = str_replace(['fuck', 'shit', 'bitch'], '****', $value);
    }
}