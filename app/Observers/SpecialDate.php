<?php

namespace App\Observers;

use App\Events\SpecialDate;

class SpecialDate extends BaseObserver
{
    public function created($model)
    {
        event(new SpecialDate($model));
    }
}