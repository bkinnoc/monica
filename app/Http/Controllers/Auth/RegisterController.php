<?php

namespace App\Http\Controllers\Auth;

use App\Models\Charity;
use App\Models\User\User;
use App\Helpers\AppHelper;
use App\Rules\ValidateTag;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Helpers\LocaleHelper;
use App\Models\AbandonedCart;
use App\Helpers\RequestHelper;
use App\Jobs\SendNewUserAlert;
use Illuminate\Support\Carbon;
use App\Helpers\InstanceHelper;
use App\Models\Account\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\MailboxRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rules\Password as PasswordRules;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm(Request $request)
    {
        $first = !InstanceHelper::hasAtLeastOneAccount();
        if (config('monica.disable_signup') == 'true' && !$first) {
            abort(403, trans('auth.signup_disabled'));
        }

        return view('auth.register')
            ->withFirst($first)
            ->withLocales(LocaleHelper::getLocaleList()->sortByCollator('lang'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return AppHelper::getRegistrationValidationRules($data);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User|null
     */
    public function create(array $data): ?User
    {
        $first = !InstanceHelper::hasAtLeastOneAccount();
        if (config('monica.disable_signup') == 'true' && !$first) {
            abort(403, trans('auth.signup_disabled'));
        }

        try {
            $account = Account::createDefault(
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['password'],
                RequestHelper::ip(),
                Arr::get($data, 'lang', 'en')
            );
            /** @var User */
            $user = $account->users()->first();
            app(MailboxRepository::class)->createForUser($user, Arr::get($data, 'mailbox_key', null));
            $user->save();

            // Update the abandoned carts
            AbandonedCart::whereEmail($user->email)->update(
                [
                    'signed_up_at' => Carbon::now(),
                    'user_id' => $user->id
                ]
            );

            $userCharityPreference = Arr::get($data, 'charity_preference');
            $charityPreference = !$userCharityPreference ? Charity::inRandomOrder()->first()->id : $userCharityPreference;
            $user->userCharities()->create([
                'charity_id' => $charityPreference,
                'percent' => nova_get_setting('charitable_percentage', 30)
            ]);

            if (!$first && $user instanceof User) {
                // send me an alert
                SendNewUserAlert::dispatch($user);
            }

            return $user;
        } catch (\Exception $e) {
            Log::error($e);

            throw $e;

            abort(500, trans('auth.signup_error'));
        }
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        if (!is_null($user)) {
            /** @var int $count */
            $count = Account::count();
            if (!config('monica.signup_double_optin') || $count == 1) {
                // if signup_double_optin is disabled, skip the confirm email part
                $user->markEmailAsVerified();
            }
        }
    }
}