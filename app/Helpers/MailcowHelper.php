<?php

namespace App\Helpers;

use App\Models\User\User;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use App\Repositories\MailboxRepository;
use Illuminate\Support\Facades\Validator;
use OptimistDigital\NovaSettings\Models\Settings;
use Illuminate\Validation\Rules\Password as PasswordRules;

class MailcowHelper
{
    /**
     * Log the user into their mailcow account/mailbox
     *
     * @param  mixed $user
     * @return array
     */
    public static function login(User $user): array
    {
        $mailbox = $user->mailbox()
            ->first();
        if (!$mailbox) {
            app(MailboxRepository::class)->createForUser($user);
            $user->load('mailbox');
            $mailbox = $user->mailbox;
        }

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

        foreach ($loginRequest->cookies() as $cookie) {
            Cookie::queue(cookie(
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

        return [$loginRequest, $params];
    }

    /**
     * Log the user into their mailcow account/mailbox
     *
     * @param  mixed $user
     * @return Request
     */
    public static function request(User $user, string $endpoint, array $payload, string $type = 'post'): Response
    {
        if (empty($authCookie = Cookie::get('Mailcow-0xHIGHFLYxSOGo'))) {
            list($loginRequest, $params) = static::login($user);
            $cookies = collect($loginRequest->cookies()->toArray())->map(function ($cookie) {
                // $name, $value, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = true, $raw = false, $sameSite = null
                return cookie('Mailcow-' . $cookie['Name'], $cookie['Value'], 2628000);
            })->all();
            // dd($user->mailbox_key, $loginRequest, $loginRequest->cookies(), $params);
        } else {
            $cookies = [
                cookie('Mailcow-0xHIGHFLYxSOGo', Cookie::get('0xHIGHFLYxSOGo'), 2628000),
                cookie('Mailcow-XRSF-Token', Cookie::get('XRSF-Token'), 2628000)
            ];
        }
        // Make sure the user is logged in
        // $password = Crypt::encryptString($user->uuid);
        $url = rtrim(config('mailcow.url'), '/') . '/SOGo/so/' . ltrim($endpoint, '/');
        $domain = config('mailcow.domain');
        // 0xHIGHFLYxSOGo is the SoGo auth cookie name
        // dd($cookies, $type, $url, $domain);
        $request = Http::withCookies($cookies, $domain)
            ->withOptions([
                'verify' => false,
            ])->$type($url, $payload);

        return $request;
    }
}