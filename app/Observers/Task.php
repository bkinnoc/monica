<?php

namespace App\Observers;

use App\Events\Task as Event;

class Task extends BaseObserver
{
    public function created($model)
    {
        event(new Event($model));
    }
}