<?php

namespace App\Traits;

use App\Models\User\User;

trait SupportsMailcowCaldav
{
    /**
     * Boot SupportsMailcowCaldav
     *
     * @return void
     */
    public static function bootSupportsMailcowCaldav()
    {
        static::created(function ($model) {
            // Shoot the create caldav event
            static::dispatchMailcowCaldavEvent($model, 'created');
        });
        static::updated(function ($model) {
            // Shoot the update caldav event
            static::dispatchMailcowCaldavEvent($model, 'updated');
        });
        static::deleted(function ($model) {
            // Shoot the delete caldav event
            static::dispatchMailcowCaldavEvent($model, 'deleted');
        });
    }

    /**
     * Dispatch the mailcow caldav event
     *
     * @param  mixed $action
     * @return void
     */
    protected static function dispatchMailcowCaldavEvent($model, $action)
    {
        $class = static::getMailcowCaldavEvent();
        event(new $class($model, $action));
    }

    /**
     * Get MailcowCaldavEvent
     *
     * @return string
     */
    protected static function getMailcowCaldavEvent(): string
    {
        $modelName = \class_basename(static::class);
        $class = "\\App\\Events\\{$modelName}";
        if (class_exists($class)) {
            return $class;
        }
        throw new \InvalidArgumentException("Mailcow event class: {$class} doesn't exist");
    }

    /**
     * Check to see if the model has a caldav id
     *
     * @return bool
     */
    public function hasMailcowCalDavId(): bool
    {
        $attribute = $this->mailcowCaldavIdAttribute;
        return $this->$attribute !== null;
    }

    /**
     * Check to see if the model has a caldav id
     *
     * @return bool
     */
    public function hasMailcowCalDavDate(): bool
    {
        $attribute = $this->mailcowCaldavDateAttribute;
        return $this->$attribute !== null;
    }

    /**
     * Get the mailcow caldav date
     *
     * @return string|\DateTimeInterface
     */
    public function getMailcowCaldavDate()
    {
        $attribute = $this->mailcowCaldavDateAttribute;
        return $this->$attribute;
    }

    /**
     * Get the event start
     *
     * @return void
     */
    public function getMailcowCaldavEventStart()
    {
        return $this->getMailcowCaldavDate();
    }

    /**
     * Get the event end
     *
     * @return void
     */
    public function getMailcowCaldavEventEnd()
    {
        return $this->getMailcowCaldavDate()->add(1, 'hour');
    }

    /**
     * Get the mailcow caldav id
     *
     * @return string|int
     */
    public function getMailcowCaldavId()
    {
        $attribute = $this->mailcowCaldavIdAttribute;
        return $this->$attribute;
    }

    /**
     * Get the mailcow cal dav id attribute
     *
     * @return string
     */
    public function getMailcowCaldavIdAttributeAttribute()
    {
        return 'mailcow_caldav_id';
    }

    /**
     * Get the event summary.
     *
     * @return string|\DateTimeInterface
     */
    public function getMailcowCaldavEventSummary()
    {
        return $this->note ?: $this->description ?: '';
    }
}