<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /**
     * Max Attempts
     *
     * @var undefined
     */
    protected $maxAttempts = 5;
    /**
     * Decay Minutes. Default is 3 hours
     *
     * @var int
     */
    protected $decayMinutes = 180;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

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
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function showLoginOrRegister()
    {
        $first = !InstanceHelper::hasAtLeastOneAccount();
        if ($first) {
            return redirect()->route('register');
        }

        return $this->showLoginForm();
    }
}