<?php

namespace App\Events;

use App\Interfaces\MailcowCaldavSupport;

abstract class MailcowCaldavEvent
{
    /**
     * The model
     *
     * @var MailcowCaldavSupport
     */
    public $model;

    /**
     * The action
     *
     * @var mixed
     */
    public $action;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MailcowCaldavSupport $model, $action = 'created')
    {
        $this->model = $model;
        $this->action = $action;
    }

    /**
     * Get Model
     *
     * @return void
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . (auth()->id() ?: 0));
    }
}