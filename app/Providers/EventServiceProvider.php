<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Illuminate\Auth\Events\Registered::class => [
            \Illuminate\Auth\Listeners\SendEmailVerificationNotification::class,
        ],
        \Illuminate\Auth\Events\PasswordReset::class => [
            \App\Listeners\LogoutUserDevices::class,
        ],

        /**
         * Custom mailcow event listeners
         */
        \App\Events\Activity::class => [
            \App\Listeners\ActivityListener::class
        ],
        \App\Events\LifeEvent::class => [
            \App\Listeners\LifeEventListener::class
        ],
        \App\Events\SpecialDate::class => [
            \App\Listeners\SpecialDateListener::class
        ],
        \App\Events\Task::class => [
            \App\Listeners\TaskListener::class
        ],
        \App\Events\Reminder::class => [
            \App\Listeners\ReminderListener::class
        ],
        \App\Events\ReminderOutbox::class => [
            \App\Listeners\ReminderOutboxListener::class
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        \App\Listeners\LoginListener::class,
    ];
}