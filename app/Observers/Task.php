<?php

namespace App\Observers;

use App\Events\NewReaction;

class Task extends BaseObserver
{
    public function created($model)
    {
        event(new Task($model));
    }
}