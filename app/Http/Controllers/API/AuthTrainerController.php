<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Validator;
use App\TrainerUser as MyModel;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\Factory;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

class AuthTrainerController extends ApiController {

    public function Login(Request $request) {
        $rules = ['email' => 'required', 'password' => 'required'];
        $rules = array_merge($this->requiredParams, $rules);
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
//            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            if (Auth::guard('trainer')->attempt($request->only('email', 'password'))) {
                $user = MyModel::find(Auth::guard('trainer')->user()->id);
                if ($user->status == '0')
                    return parent::error("Account is not approved yet, Kindly Contact Admin for more detail.");
                $token = $user->createToken('netscape')->accessToken;
                parent::addTrainerUserDeviceData($user, $request);
                return parent::success(['message' => 'Login Successfully', 'token' => $token, 'user' => $user]);
            } else {
                return parent::error("User credentials doesn't matched");
            }
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function resetPassword(Request $request, Factory $view) {


        //Validating attributes
        $rules = ['email' => 'required|exists:trainer_users,email'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        $view->composer('emails.auth.password', function($view) {
            $view->with([
                'title' => trans('front/password.email-title'),
                'intro' => trans('front/password.email-intro'),
                'link' => trans('front/password.email-link'),
                'expire' => trans('front/password.email-expire'),
                'minutes' => trans('front/password.minutes'),
            ]);
        });
//        dd($request->only('email'));
        $response = Password::broker('trainer')->sendResetLink($request->only('email'), function (Message $message) {
            $message->subject(trans('front/password.reset'));
        });

//        dd($response);
        switch ($response) {
            case Password::RESET_LINK_SENT:
                return parent::successCreated('Password reset link sent, please check inbox');
            case Password::INVALID_USER:
                return parent::error(trans($response));
            default :
                return parent::error(trans($response));
                break;
        }

        return parent::error('Something Went');
    }

    public function logout(Request $request) {
        $rules = [];

        $validateAttributes = parent::validateAttributes($request, 'GET', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
//            $user = \App\TrainerUser::findOrFail(\Auth::id());
//            $user->is_login = '0';
//            $user->save();
            $device = \App\TrainerUserDevice::where('trainer_user_id', \Auth::id())->get();
//            dd($device);
            if ($device->isEmpty() === false)
                \App\TrainerUserDevice::destroy($device->first()->id);

            return parent::successCreated('Logout Successfully');
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
