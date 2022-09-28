<?php

namespace App\Listeners;

use App\Events\Event;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Helpers\MailcowHelper;
use App\Events\MailcowCaldavEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class MailcowEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    protected $event;

    /**
     * Handle the event.
     *
     * @param  App\Events\MailcowCaldavEvent  $event
     * @return void
     */
    public function handle(MailcowCaldavEvent $event)
    {
        $this->ensureUserLoggedIn($event);
        switch ($event->action) {
            case 'created':
                $this->createEntry($event);
                break;

            case 'updated':
                $this->updateEntry($event);
                break;

            case 'deleted':
                $this->deleteEntry($event);
                break;
        }
    }

    /**
     * Create the entry using the mailcow caldav API
     *
     * @return void
     */
    protected function createEntry(MailcowCaldavEvent $event)
    {
        $user = $this->getUser($event);
        // URL Format: /SOGo/so/malcolm@getcustomers.company/Calendar/personal/3E-6332E780-7-44BE1300.ics/saveAsAppointment
        $id = Str::uuid() . '.ics';
        $result = MailcowHelper::request($user, "{$user->mailbox_key}/Calendar/personal/{$id}/saveAsAppointment", [
            'classification' => 'public',
            'destinationCalendar' => 'personal',
            'pid' => 'personal',
            'summary' => $event->model->getMailcowCaldavEventSummary(),
            'start' => $event->model->getMailcowCaldavEventStart(),
            'startDate' => $event->model->getMailcowCaldavEventStart()->format('YYYY-MM-DD'),
            'startTime' => $event->model->getMailcowCaldavEventStart()->format('HH:ss'),
            'end' => $event->model->getMailcowCaldavEventEnd(),
            'endDate' => $event->model->getMailcowCaldavEventEnd()->format('YYYY-MM-DD'),
            'endTime' => $event->model->getMailcowCaldavEventEnd()->format('HH:ss'),
            'type' => 'appointment',
            'id' => $id,
            'delta' => 60,
            'isNew' => true,
        ]);
        $attribute = $event->model->mailcowCaldavIdAttribute;
        $event->model->$attribute = $id;
        $event->model->save();
        return $result;
    }

    /**
     * Update the entry using the mailcow caldav API
     *
     * @return void
     */
    protected function updateEntry(MailcowCaldavEvent $event)
    {
        $id = $event->model->getMailcowCaldavId();
        if ($id) {
            $user = $this->getUser($event);
            // URL Format: /SOGo/so/malcolm@getcustomers.company/Calendar/personal/3E-6332E780-7-44BE1300.ics/save
            $result = MailcowHelper::request($user, "{$user->mailbox_key}/Calendar/personal/{$id}/save", [
                'id' => $id,
                'summary' => $event->model->getMailcowCaldavEventSummary(),
                'start' => $event->model->getMailcowCaldavEventStart(),
                'startDate' => $event->model->getMailcowCaldavEventStart()->format('YYYY-MM-DD'),
                'startTime' => $event->model->getMailcowCaldavEventStart()->format('HH:ss'),
                'end' => $event->model->getMailcowCaldavEventEnd(),
                'endDate' => $event->model->getMailcowCaldavEventEnd()->format('YYYY-MM-DD'),
                'endTime' => $event->model->getMailcowCaldavEventEnd()->format('HH:ss'),
            ]);
            return $result;
        }
        return false;
    }

    /**
     * Delete the entry using the mailcow caldav API
     *
     * @return void
     */
    protected function deleteEntry(MailcowCaldavEvent $event)
    {
        $id = $event->model->getMailcowCaldavId();
        if ($id) {
            $user = $this->getUser($event);
            // URL Format: /SOGo/so/malcolm@getcustomers.company/Calendar/personal/3E-6332E780-7-44BE1300.ics/delete
            $result = MailcowHelper::request($user, "{$user->mailbox_key}/Calendar/personal/{$id}/delete", [], 'get');
            return $result;
        }
        return false;
    }

    /**
     * Ensure the user is logged in
     *
     * @param  mixed $event
     * @return bool
     */
    protected function ensureUserLoggedIn(MailcowCaldavEvent $event): bool
    {
        list($loginRequest, $params) = MailcowHelper::login($this->getUser($event));
        return true;
    }

    /**
     * Get the user from the acccount
     *
     * @param  mixed $event
     * @return User
     */
    protected function getUser(MailcowCaldavEvent $event): User
    {
        // Only use the first user for the account given the nature of the system
        $user = $event->model->account->users()->first();
        return $user;
    }
}