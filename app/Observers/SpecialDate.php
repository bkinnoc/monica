<?php

namespace App\Observers;

use App\Events\SpecialDate as Event;

class SpecialDate extends BaseObserver
{
    public function created($model)
    {
        event(new Event($model));
    }
}