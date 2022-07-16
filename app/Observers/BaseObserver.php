<?php

namespace App\Observers;

use Model;
use Monooso\Unobserve\CanMute;

abstract class BaseObserver
{
    use CanMute;

    protected $title;

    /**
     * The model being observed.
     *
     * @var Model model
     */
    protected $model;

    /**
     * The action that is currently being observed.
     *
     * @var [type]
     */
    protected $action;

    /**
     * The object that is acting.
     *
     * @var [type]
     */
    protected $object;

    /**
     * The object that is being acted upon.
     *
     * @var [type]
     */
    protected $target;

    /**
     * The actor that this activity occurred under.
     *
     * @var [type]
     */
    protected $actor;

    protected $_isAdminAction;

    protected static function getUser()
    {
        return auth()->user();
    }

    public function created($model)
    {
        // $this->setupActivity('create', $model)->recordActivity();
    }

    public function updated($model)
    {
        // $this->setupActivity('update', $model)->recordActivity();
    }

    public function deleted($model)
    {
        // $this->setupActivity('delete', $model)->recordActivity();
    }

    public function setupActivity($action, $object, $actor = null, $target = null)
    {
        $this->action = $action;
        $this->model = $object;
        $this->actor = $this->formatActor($actor ?: static::getUser());
        $this->object = $this->formatObject($object);
        if ($target) {
            $this->target = $this->formatTarget($target);
        }

        return $this;
    }
}
