<?php

namespace App\Auth;

use App\Team;
use Illuminate\Support\Str;
use MadWeb\SocialAuth\Models\SocialProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use MadWeb\SocialAuth\Events\SocialUserCreated;
use MadWeb\SocialAuth\Events\SocialUserAttached;
use App\Http\Controllers\Auth\RegisterController;
use Laravel\Socialite\Contracts\User as SocialUser;
use MadWeb\SocialAuth\Contracts\SocialAuthenticatable;
use MadWeb\SocialAuth\SocialProviderManager as BaseSocialProviderManager;

class SocialProviderManager extends BaseSocialProviderManager
{
    /**
     * @param string $key
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function socialTeamQuery(string $key)
    {
        return $this->social->teams()->wherePivot(config('social-auth.foreign_keys.socials'), $key);
    }

    /**
     * Gets user by unique social identifier.
     *
     * @param string $key
     * @return mixed
     */
    public function getTeamByKey(string $key)
    {
        return $this->socialTeamQuery($key)->first();
    }

    /**
     * @param SocialAuthenticatable $user
     * @param SocialUser $socialUser
     */
    public function attachTeam(Team $team, SocialUser $socialUser, $offlineToken = null)
    {
        $team->attachSocialCustom(
            $this->social,
            $socialUser->getId(),
            $socialUser->token,
            $offlineToken,
            $socialUser->expiresIn ?? request()->input('expires_in')
        );
    }

    /**
     * @param SocialAuthenticatable $user
     * @param SocialUser $socialUser
     */
    public function attachUser(SocialAuthenticatable $user, SocialUser $socialUser, $offlineToken = null)
    {
        $user->attachSocialCustom(
            $this->social,
            $socialUser->getId(),
            $socialUser->token,
            $offlineToken,
            $socialUser->expiresIn ?? request()->input('expires_in')
        );

        event(new SocialUserAttached($user, $this->social, $socialUser));
    }

    /**
     * Create new system user by social user data.
     *
     * @param Authenticatable $userModel
     * @param SocialProvider $social
     * @param SocialUser $socialUser
     * @return Authenticatable
     */
    public function createNewUser(
        Authenticatable $userModel,
        SocialProvider $social,
        SocialUser $socialUser
    ): Authenticatable {
        $data = $userModel->mapSocialData($socialUser);

        list($firstName, $lastName) = explode(' ', $data['name']);
        $NewUser = app(RegisterController::class)->create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $data['email'],
            'lang' => 'en',
            'password' => Str::random(12)
        ]);

        $NewUser->attachSocial(
            $social,
            $socialUser->getId(),
            $socialUser->token,
            $socialUser->expiresIn ?? null
        );

        event(new SocialUserCreated($NewUser));

        return $NewUser;
    }
}