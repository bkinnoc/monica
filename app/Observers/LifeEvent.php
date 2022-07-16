<?php

namespace App\Observers;

use App\Events\NewReaction;

class LifeEvent extends BaseObserver
{
    public function created($model)
    {
        event(new LifeEvent($model));
    }
}