<?php

namespace App\Observers;

use App\Events\NewReaction;

class NewSignup extends BaseObserver
{
    public function created($model)
    {
        event(new NewSignup($model));
    }
}