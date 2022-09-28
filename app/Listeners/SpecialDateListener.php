<?php

namespace App\Listeners;

use App\Events\SpecialDate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SpecialDateListener extends MailcowEventListener implements ShouldQueue
{
    use InteractsWithQueue;
}