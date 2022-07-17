<?php

namespace App\Observers;

use App\Events\LifeEvent as Event;

class LifeEvent extends BaseObserver
{
    public function created($model)
    {
        event(new Event($model));
    }
}