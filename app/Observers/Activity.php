<?php

namespace App\Observers;

use App\Events\Activity as Event;

class Activity extends BaseObserver
{
    public function created($model)
    {
        event(new Event($model));
    }
}