<?php

namespace App\Interfaces;

interface MailcowCaldavSupport
{
    /**
     * Get the date for the mailcow caldav item.
     *
     * @return string|int
     */
    public function getMailcowCaldavId();

    /**
     * Get the date for the mailcow caldav item.
     *
     * @return string|\DateTimeInterface
     */
    public function getMailcowCaldavDate();

    /**
     * Get the start date for the mailcow caldav item.
     *
     * @return string|\DateTimeInterface
     */
    public function getMailcowCaldavEventStart();

    /**
     * Get the end date for the mailcow caldav item.
     *
     * @return string|\DateTimeInterface
     */
    public function getMailcowCaldavEventEnd();

    /**
     * Get the event summary.
     *
     * @return string
     */
    public function getMailcowCaldavEventSummary();

    /**
     * Get the mailcow cal dav id attribute
     *
     * @return string
     */
    public function getMailcowCaldavIdAttributeAttribute();
    /**
     * Get the mailcow cal dav date attribute
     *
     * @return string
     */
    public function getMailcowCaldavDateAttributeAttribute();
}