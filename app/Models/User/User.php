<?php

namespace App\Models\User;

use Carbon\Carbon;
use App\Traits\HasUuid;
use App\Helpers\FormHelper;
use App\Jobs\SendVerifyEmail;
use App\Models\Settings\Term;
use App\Models\MailcowMailbox;
use App\Models\Account\Account;
use App\Models\Contact\Contact;
use App\Helpers\ComplianceHelper;
use App\Models\Settings\Currency;
use Laravel\Passport\HasApiTokens;
use Nitm\Content\Traits\CustomWith;
use Spatie\Permission\Traits\HasRoles;
use Nitm\Content\Traits\SyncsRelations;
use Illuminate\Notifications\Notifiable;
use MadWeb\SocialAuth\Traits\UserSocialite;
use Spatie\Activitylog\Traits\LogsActivity;
use Nitm\Content\Traits\Model as ModelTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MadWeb\SocialAuth\Contracts\SocialAuthenticatable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference, SocialAuthenticatable
{
    use Notifiable, HasApiTokens, HasUuid, HasRoles, SyncsRelations, CustomWith, ModelTrait;
    use LogsActivity;
    use UserSocialite;

    const ROLE_USER        = 'User';
    const ROLE_ADMIN       = 'Web Admin';
    const ROLE_DEVELOPER   = 'Developer';
    const ROLE_SUPER_ADMIN = 'Super Admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'timezone',
        'locale',
        'currency_id',
        'fluid_container',
        'temperature_scale',
        'name_order',
        'google2fa_secret',
        'dob',
        'mailbox_key'
    ];

    /**
     * Eager load account with every user.
     */
    protected $with = ['account'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'google2fa_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'profile_new_life_event_badge_seen' => 'boolean',
        'admin' => 'boolean',
        'fluid_container' => 'boolean',
        'dob' => 'date'
    ];

    /**
     * Available names order.
     *
     * @var array
     */
    public const NAMES_ORDER = [
        'firstname_lastname',
        'lastname_firstname',
        'firstname_lastname_nickname',
        'firstname_nickname_lastname',
        'lastname_firstname_nickname',
        'lastname_nickname_firstname',
        'nickname_firstname_lastname',
        'nickname_lastname_firstname',
        'nickname',
    ];

    /**
     * Get the account record associated with the user.
     *
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the contact record associated with the 'me' contact.
     *
     * @return HasOne
     */
    public function me()
    {
        return $this->hasOne(Contact::class, 'id', 'me_contact_id');
    }

    /**
     * Get the term records associated with the user.
     *
     * @return BelongsToMany
     */
    public function terms()
    {
        return $this->belongsToMany(Term::class)->withPivot('ip_address')->withTimestamps();
    }

    /**
     * Get the recovery codes associated with the user.
     *
     * @return HasMany
     */
    public function recoveryCodes()
    {
        return $this->hasMany(RecoveryCode::class);
    }

    /**
     * Gets the currency for this user.
     *
     * @return BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * This function returns a collection of charities that belong to the user
     *
     * @return A collection of charities that the user wants to donate to.
     */
    public function charities()
    {
        return $this->belongsToMany(\App\Models\Charity::class, 'user_charities', 'user_id', 'charity_id')
            ->withPivot(['charity_id', 'user_id', 'percent']);
    }

    /**
     * This function returns a collection of charities that belong to the user
     *
     * @return A collection of charities that the user wants to donate to.
     */
    public function charity()
    {
        return $this->hasOneThrough(\App\Models\Charity::class, \App\Models\UserCharity::class, 'user_id', 'id', 'id', 'charity_id');
    }

    /**
     * This function returns a collection of charities that belong to the user
     *
     * @return A collection of charities that the user wants to donate to.
     */
    public function userCharities()
    {
        return $this->hasMany(\App\Models\UserCharity::class, 'user_id');
    }

    /**
     * > This function returns the MailCowMailbox model that has the same username as the email of the
     * current model
     *
     * @return A MailCowMailbox model instance.
     */
    public function mailbox()
    {
        return $this->hasOne(MailcowMailbox::class, 'username', 'mailbox_key');
    }

    /**
     * Assigns a default value just in case the sort order is empty.
     *
     * @param  string  $value
     * @return string
     */
    public function getContactsSortOrderAttribute($value): string
    {
        return !empty($value) ? $value : 'firstnameAZ';
    }

    /**
     * Indicates if the layout is fluid or not for the UI.
     *
     * @return string
     */
    public function getFluidLayout(): string
    {
        if ($this->fluid_container) {
            return 'container-fluid';
        } else {
            return 'container';
        }
    }

    /**
     * getMailboxUsernameAttribute
     *
     * @return void
     */
    public function getMailboxUsernameAttribute()
    {
        return explode('@', $this->email)[0];
    }

    /**
     * Get users's full name. The name is formatted according to the user's
     * preference, either "Firstname Lastname", or "Lastname Firstname".
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        $completeName = '';

        if (FormHelper::getNameOrderForForms($this) === 'firstname') {
            $completeName = $this->first_name;

            if ($this->last_name !== '') {
                $completeName = $completeName . ' ' . $this->last_name;
            }
        } else {
            if ($this->last_name !== '') {
                $completeName = $this->last_name;
            }

            $completeName = $completeName . ' ' . $this->first_name;
        }

        return $completeName;
    }

    /**
     * Ecrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return void
     */
    public function setGoogle2faSecretAttribute($value): void
    {
        $this->attributes['google2fa_secret'] = encrypt($value);
    }

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function getGoogle2faSecretAttribute($value): ?string
    {
        return is_null($value) ? null : decrypt($value);
    }

    /**
     * Indicate if the user has accepted the most current terms and privacy.
     *
     * @param  string|null  $value
     * @return bool
     */
    public function getPolicyCompliantAttribute($value): bool
    {
        return ComplianceHelper::isCompliantWithCurrentTerm($this);
    }

    /**
     * Indicate whether the user should be reminded at this time.
     * This is affected by the user settings regarding the hour of the day he
     * wants to be reminded.
     *
     * @param  Carbon|null  $date
     * @return bool
     */
    public function isTheRightTimeToBeReminded($date)
    {
        if (is_null($date)) {
            return false;
        }

        $now = now($this->timezone);
        $isTheRightTime = true;

        // compare date with current date for the user
        if (!$date->isSameDay($now)) {
            $isTheRightTime = false;
        }

        // compare current hour for the user with the hour they want to be
        // reminded as per the hour set on the profile
        if (!$now->isSameHour($this->account->default_time_reminder_is_sent)) {
            $isTheRightTime = false;
        }

        return $isTheRightTime;
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        /** @var int $count */
        $count = Account::count();
        if (config('monica.signup_double_optin') && $count > 1) {
            SendVerifyEmail::dispatch($this);
        }
    }

    /**
     * Get the preferred locale of the entity.
     *
     * @return string|null
     */
    public function preferredLocale()
    {
        return $this->locale;
    }

    /**
     * Try using a recovery code.
     *
     * @param  string  $recovery
     * @return bool
     */
    public function recoveryChallenge(string $recovery): bool
    {
        $recoveryCodes = $this->recoveryCodes()->unused()->get();

        foreach ($recoveryCodes as $recoveryCode) {
            if ($recoveryCode->recovery === $recovery) {
                $recoveryCode->used = true;
                $recoveryCode->save();

                return true;
            }
        }

        return false;
    }



    /**
     * Custom attachSocial for teams, taken from Mad Web Laravel Social Package
     * Attach social network provider to the user.
     *
     * @param SocialProvider $social
     * @param string         $socialId
     * @param string         $token
     * @param int            $expiresIn
     */
    public function attachSocialCustom($social, string $socialId, string $token, string $offlineToken = null, int $expiresIn = null)
    {
        $data = ['social_id' => $socialId, 'token' => $token, 'offline_token' => $offlineToken];

        // \Log::info(json_encode($data));

        $expiresIn = $expiresIn ?: 3600;
        $expiresIn = date_create('now')
            ->add(\DateInterval::createFromDateString($expiresIn . ' seconds'))
            ->format($this->getDateFormat());

        $data['expires_in'] = $expiresIn;

        $this->socials()->attach($social, $data);
    }

    /**
     * Properly set the mailbox key for mailcow
     *
     * @param  mixed $value
     * @return void
     */
    public function setMailboxKeyAttribute($value)
    {
        $this->attributes['mailbox_key'] = stripos($value, '@') === false ? $value . '@' . config('mailcow.domain') : $this->attributes['mailbox_key'] = $value;
    }
}
