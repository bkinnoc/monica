<?php

namespace App\Listeners;

use App\Events\Activity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ActivityListener extends MailcowEventListener implements ShouldQueue
{
    use InteractsWithQueue;
}