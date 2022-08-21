<?php

namespace App\Http\Controllers;

use App\Models\User\User;
use App\Helpers\DateHelper;
use App\Models\Contact\Debt;
use Illuminate\Http\Request;
use App\Models\MailcowMailbox;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\MailboxRepository;
use App\Http\Resources\Debt\Debt as DebtResource;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        // dd(config('database.connections.mailcow'));
        $user    = auth()->user();
        $mailbox = $user->mailbox()
            ->first();


        if (!$mailbox) {
            app(MailboxRepository::class)->createForUser($user);
            $user->load('mailbox');
            $mailbox = $user->mailbox;
        }
        // dd("Mailbox", $user->mailbox_key, $mailbox, \Nitm\Helpers\DbHelper::getQueryWithBindings($user->mailbox()), MailcowMailbox::all()->toArray());

        // $password = Crypt::encryptString($user->uuid);
        $url = config('mailcow.url');
        $password = md5($user->uuid);
        $domain = config('mailcow.domain');
        $params = [
            'url' => "{$url}/SOGo/so",
            'authBasic' => "{$user->mailbox_key}:{$password}",
            'authString' => htmlentities(rawurlencode("{$user->mailbox_key}:{$password}")),
            'authStringEncoded' => base64_encode("{$user->mailbox_key}:{$password}"),
            'userName' => $user->mailbox_key,
            'password' => $password,
        ];

        $loginRequest = Http::withHeaders([
            // 'Authorization' => "Basic {$params['authBasic']}"
        ])
            ->withOptions([
                'verify' => false,
            ])->post("{$url}/SOGo/connect", $params);

        $response = response(view('mailbox.index', $params));

        foreach ($loginRequest->cookies() as $cookie) {
            $response->withCookie(cookie(
                $cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpires() ?: 7200,
                $cookie->getPath(),
                $cookie->getDomain(),
                $cookie->getSecure(),
                $cookie->getHttpOnly(),
                true
            ));
        }

        return $response;
    }

    /**
     * Get calls for the dashboard.
     *
     * @return Collection
     */
    public function calls()
    {
        $callsCollection = collect([]);
        $calls           = auth()->user()->account->calls()
            ->get()
            ->reject(function ($call) {
                return is_null($call->contact);
            })
            ->take(15);

        foreach ($calls as $call) {
            $data = [
                'id'         => $call->id,
                'called_at'  => DateHelper::getShortDate($call->called_at),
                'name'       => $call->contact->getIncompleteName(),
                'contact_id' => $call->contact->hashID(),
            ];
            $callsCollection->push($data);
        }

        return $callsCollection;
    }

    /**
     * Get notes for the dashboard.
     *
     * @return Collection
     */
    public function notes()
    {
        $notesCollection = collect([]);
        $notes           = auth()->user()->account->notes()->favorited()->get();

        foreach ($notes as $note) {
            $data = [
                'id'         => $note->id,
                'body'       => $note->body,
                'created_at' => DateHelper::getShortDate($note->created_at),
                'name'       => $note->contact->getIncompleteName(),
                'contact'    => [
                    'id'                   => $note->contact->hashID(),
                    'has_avatar'           => $note->contact->has_avatar,
                    'avatar_url'           => $note->contact->getAvatarURL(),
                    'initials'             => $note->contact->getInitials(),
                    'default_avatar_color' => $note->contact->default_avatar_color,
                    'complete_name'        => $note->contact->name,
                ],
            ];
            $notesCollection->push($data);
        }

        return $notesCollection;
    }

    /**
     * Get debts for the dashboard.
     *
     * @return Collection
     */
    public function debts()
    {
        $debtsCollection = collect([]);
        $debts           = auth()->user()->account->debts()->get();

        foreach ($debts as $debt) {
            $debtsCollection->push(new DebtResource($debt));
        }

        return $debtsCollection;
    }

    /**
     * Save the current active tab to the User table.
     */
    public function setTab(Request $request)
    {
        auth()->user()->dashboard_active_tab = $request->input('tab');
        auth()->user()->save();
    }
}