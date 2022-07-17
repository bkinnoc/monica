<?php

namespace App\Observers;

use App\Events\NewSignup as Event;

class NewSignup extends BaseObserver
{
    public function created($model)
    {
        event(new Event($model));
    }
}