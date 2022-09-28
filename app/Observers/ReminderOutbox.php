<?php

namespace App\Observers;

use App\Events\ReminderOutbox as Event;

class ReminderOutbox extends BaseObserver
{
    public function created($model)
    {
        event(new Event($model));
    }
}