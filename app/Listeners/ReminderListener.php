<?php

namespace App\Listeners;

use App\Events\Reminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReminderListener extends MailcowEventListener implements ShouldQueue
{
    use InteractsWithQueue;
}