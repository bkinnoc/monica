<?php

namespace App\Listeners;

use App\Events\LifeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LifeEventListener extends MailcowEventListener implements ShouldQueue
{
    use InteractsWithQueue;
}