<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller {
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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct() {
//        $this->middleware('guest')->except('logout');
//    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        dd('s');
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Login the admin.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request) {
//        $this->validator($request);
//        //check if the user has too many login attempts.
//        if ($this->hasTooManyLoginAttempts($request)) {
//            //Fire the lockout event.
//            $this->fireLockoutEvent($request);
//
//            //redirect the user back after lockout.
//            return $this->sendLockoutResponse($request);
//        }
        //attempt login.
        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            //Authenticated
            return redirect()
                            ->intended(route('home'))
                            ->with('status', 'You are Logged in as Admin!');
        }
//        dd('s');
        //keep track of login attempts from the user.
//        $this->incrementLoginAttempts($request);
        //Authentication failed
        return redirect()->back()->withErrors(['Provided Credentials Does`t match the records']);
//        return $this->loginFailed();
    }

}
