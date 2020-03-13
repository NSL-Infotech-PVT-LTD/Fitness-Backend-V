<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Validator;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\Factory;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;


class AuthController extends ApiController {

    public function Register(Request $request) {
//        dd(implode(',',\App\Currency::get()->pluck('id')->toArray()));
        $rules = ['name' => 'required', 'email' => 'required|email|unique:users', 'password' => 'required', 'mobile' => 'required|unique:users', 'location' => 'required','dob'=>'required'];
        $rules = array_merge($this->requiredParams, $rules);
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
            $input['password'] = Hash::make($request->password);
//            $input['is_notify'] = '1';
//            $input['is_login'] = '1';
            $user = \App\User::create($input);
            //Assign role to created user[1=>10,2=>20,]
            $user->assignRole(\App\Role::where('id', 2)->first()->name);
            // create user token for authorization
            $token = $user->createToken('netscape')->accessToken;

//            testing comment
            // Add user device details for firbase
            parent::addUserDeviceData($user, $request);
           return parent::successCreated(['message' => 'Created Successfully', 'token' => $token, 'user' => $user]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

 
    public function Login(Request $request) {
        try {
            $rules = ['email' => 'required', 'password' => 'required'];
            $rules = array_merge($this->requiredParams, $rules);
            $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);
            if ($validateAttributes):
                return $validateAttributes;
            endif;

            //parent::addUserDeviceData($user, $request);
            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])):
               
                $user = \App\User::find(Auth::user()->id);
              
                $user->save();
                $token = $user->createToken('netscape')->accessToken;


                parent::addUserDeviceData($user, $request);


//                $user = $user->with('roles');
                // Add user device details for firbase


              

                return parent::successCreated(['message' => 'Login Successfully', 'token' => $token, 'user' => $user]);
            else:
                return parent::error("User credentials doesn't matched");
            endif;
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

   

}