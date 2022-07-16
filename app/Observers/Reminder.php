<?php

namespace App\Observers;

use App\Events\NewReaction;

class Reminder extends BaseObserver
{
    public function created($model)
    {
        event(new Reminder($model));
    }
}