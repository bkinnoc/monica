<?php

namespace App\Observers;

use App\Events\Reminder as Event;

class Reminder extends BaseObserver
{
    public function created($model)
    {
        event(new Event($model));
    }
}