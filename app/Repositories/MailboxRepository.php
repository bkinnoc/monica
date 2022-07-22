<?php

/**
 * Charity Repository
 */

namespace App\Repositories;

use App\Models\User\User;
use MailCow\Configuration;
use MailCow\Api\MailboxesApi;
use App\Models\MailcowMailbox;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Crypt;
use MailCow\Models\CreateMailboxRequest;

/**
 * Class CharityRepository
 * @package App\Repositories
 * @version May 7, 2022, 9:19 pm UTC
 */

class MailboxRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return MailcowMailbox::class;
    }

    public function createForUser(User $user, $mailboxKey = null)
    {
        // $password = Crypt::encryptString($user->uuid);
        $password = md5($user->uuid);
        $config = Configuration::getDefaultConfiguration()
            ->setHost(config('mailcow.host'))
            ->setDebug((bool)config('mailcow.debug'))
            ->setApiKey('X-API-Key', config('mailcow.api_key'));
        $localPart = $mailboxKey ?? explode('@', $user->email)[0];
        $domain = config('mailcow.domain');
        \Log::info(json_encode([
            'domain' => $domain,
            'name' => "{$user->first_name} {$user->last_name}",
            'localPart' => $localPart,
            'host' => config('mailcow.host')
        ], JSON_PRETTY_PRINT));
        $body = new CreateMailboxRequest(
            [
                'domain' => $domain,
                'name' => "{$user->first_name} {$user->last_name}",
                'localPart' => $localPart,
                'password' => $password,
                'password2' => $password,
                'active' => 1,
                "quota" => config('mailcow.quota'),
                "force_pw_update" => "0",
                "tls_enforce_in" => "1",
                "tls_enforce_out" => "1"
            ]
        );

        if (app()->environment('testing')) {
            $user->mailbox_key = "{$localPart}@{$domain}";
            $user->save();
        } else {
            $api = new MailboxesApi(new \GuzzleHttp\Client(['verify' => false]), $config);
            try {
                $api->createMailbox($body);
                $user->mailbox_key = "{$localPart}@{$domain}";
                $user->save();
            } catch (\Exception $e) {
                \Log::error($e);
            }
        }
        return $user->mailbox()->first();
    }
}
