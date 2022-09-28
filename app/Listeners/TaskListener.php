<?php

namespace App\Listeners;

use App\Events\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TaskListener extends MailcowEventListener implements ShouldQueue
{
    use InteractsWithQueue;
}