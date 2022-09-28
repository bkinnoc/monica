<?php

namespace App\Listeners;

use App\Events\ReminderOutbox;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReminderOutboxListener extends MailcowEventListener implements ShouldQueue
{
    use InteractsWithQueue;
}