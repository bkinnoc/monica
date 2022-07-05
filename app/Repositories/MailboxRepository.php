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

    public function createForUser(User $user)
    {
        // $password = Crypt::encryptString($user->uuid);
        $password = md5($user->uuid);
        $config = Configuration::getDefaultConfiguration()
            ->setHost(env('MAILCOW_HOST'))
            ->setDebug((bool)env('MAILCOW_DEBUG'))
            ->setApiKey('X-API-Key', env('MAILCOW_API_KEY'));
        $localPart = explode('@', $user->email)[0];
        $domain = env('MAILCOW_DOMAIN');
        $body = new CreateMailboxRequest(
            [
                'domain' => $domain,
                'name' => "{$user->first_name} {$user->last_name}",
                'localPart' => $localPart,
                'password' => $password,
                'password2' => $password,
                'active' => 1,
                "quota" => env('MAILCOW_QUOTA', 1024),
                "force_pw_update" => "0",
                "tls_enforce_in" => "1",
                "tls_enforce_out" => "1"
            ]
        );
        $api = new MailboxesApi(new \GuzzleHttp\Client(['verify' => false]), $config);
        $api->createMailbox($body);
        $user->mailbox_key = "{$localPart}@{$domain}";
        $user->save();
        return $user->mailbox()->first();
    }
}