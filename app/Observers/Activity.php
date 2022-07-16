<?php

namespace App\Observers;

use App\Events\NewReaction;

class Activity extends BaseObserver
{
    public function created($model)
    {
        event(new Activity($model));
    }
}