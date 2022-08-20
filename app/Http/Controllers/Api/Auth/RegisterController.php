<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User\User;
use App\Helpers\AppHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AbandonedCart;
use function Safe\json_decode;
use App\Models\Account\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\JsonRespondController;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Foundation\Auth\RegistersUsers;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Auth\RegisterController as BaseRegisterController;

class RegisterController extends Controller
{
    use JsonRespondController;
    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest', 'oauth'], ['except' => ['logout']]);
    }

    /**
     * Validate the given step and parameters
     *
     * @param  mixed $request
     * @param  mixed $step
     * @return JsonResponse
     */
    public function validateStep(Request $request, $step = 0)
    {
        switch ($step) {
            case 0:
                $params = [
                    'last_name' => 'required|max:255',
                    'first_name' => 'required|max:255',
                    'email' => ['required', 'email', 'max:255', 'unique:users', new \App\Rules\BadWord],
                    'mailbox_key' => app()->environment('testing') ? [new \App\Rules\BadWord] : [
                        'unique:mailcow.mailbox,username',
                        new \App\Rules\BadWord
                    ],
                ];
                break;
            case 1:
                $params = [
                    'charity_preference' => 'nullable|exists:charities,id',
                ];
                break;
            case 2:
                $beforePeriod = nova_get_setting('minimum_age', 13) . ' years ago';
                $params = [
                    'password' => [
                        'required', 'confirmed',
                        'max:' . config('app.password_max', 32),
                        AppHelper::getPasswordRules(true)
                    ],
                    'dob' => "sometimes|before:{$beforePeriod}"
                ];
                break;
            case 3:
                $params = [
                    'policy' => 'required',
                ];
                break;
        }

        $validator = Validator::make($request->all(), array_filter($params));

        return new JsonResponse($validator->fails() ? $validator->errors() : true);
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
     * Records the abandoned cart into
     *
     * @param  mixed $request
     * @return void
     */
    public function recordForAbandonedCart(Request $request)
    {
        $params = $request->all();
        $record = [];
        if (isset($params['email']) && !AbandonedCart::where(Arr::only($params, ['email'], ['mailbox_key']))->exists()) {
            $record = AbandonedCart::firstOrCreate(['email' => $params['email']]);
            $record->fill($params);
            $record->save();
        }
        return new JsonResponse($record);
    }

    /**
     * Register using the API
     *
     * @param  mixed $request
     * @return void
     */
    public function create($data): ?User
    {
        $result = app(BaseRegisterController::class)->create($data);
        return $result;
    }

    /**
     * Register using the API
     *
     * @param  mixed $request
     * @return void
     */
    protected function registered(Request $request, $user)
    {
        if (!is_null($user)) {
            $this->guard()->login($user);
            /** @var int $count */
            $count = Account::count();
            if (!config('monica.signup_double_optin') || $count == 1) {
                // if signup_double_optin is disabled, skip the confirm email part
                $user->markEmailAsVerified();
            }
        }
    }

    /**
     * Custom register handler
     * @param  mixed $request
     * @return void
     */
    public function registerCustom(Request $request)
    {
        $this->register($request);
        return new JsonResponse(auth()->user());
    }
}