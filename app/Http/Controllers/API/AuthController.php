<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use App\User;
use \App\Role;
use Illuminate\Support\Facades\Mail;
use Hash;
use App;
use Illuminate\View\Factory;
use Illuminate\Support\Facades\Password;

class AuthController extends ApiController {

    public $successStatus = 200;

    public function formatValidator($validator) {
        $messages = $validator->getMessageBag();
        foreach ($messages->keys() as $key) {
            $errors[] = $messages->get($key)['0'];
        }
        return $errors[0];
    }

    public function getMetaContent(Request $request) {

        $keyword = $request->get('search');

        if (!empty($keyword)) {
            $metaContent = \App\Metum::where('name', 'LIKE', "%$keyword%")->get();
        } else {
            $metaContent = \App\Metum::get();
        }


        if (!$metaContent->isEmpty()) {
            return parent::success($metaContent, 200);
        } else {
            return parent::error('No Meta Content Found', 500);
        }
    }

// Phase 2 Starts here


    public function login(Request $request) {
        //Validating attributes
        $rules = ['email' => 'required', 'password' => 'required'];
        $rules = array_merge($this->requiredParams, $rules);
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = \App\User::find(Auth::user()->id);
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user'] = $user;
            if ($user->status != 1) {
                return parent::error('Please contact admin to activate your account', 200);
            }
            // Add user device details for firbase
            parent::addUserDeviceData($user, $request);
            return parent::success($success, $this->successStatus);
        } else {
            return parent::error('Wrong Username or Password', 200);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $rules = ['name' => 'required', 'email' => 'required|email|unique:users', 'password' => 'required', 'c_password' => 'required|same:password'];
        $rules = array_merge($this->requiredParams, $rules);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = self::formatValidator($validator);
            return parent::error($errors, 200);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
       
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['user'] = $user;

        $lastId = $user->id;
        
        $selectClientRole = Role::where('name', 'Customer')->first();
        $assignRole = DB::table('role_user')->insert(
                ['user_id' => $lastId, 'role_id' => $selectClientRole->id]
        );

        // Add user device details for firbase
        parent::addUserDeviceData($user, $request);
        if ($user->status != 1) {
            return parent::error('Please contact admin to activate your account', 200);
        }
        return parent::success($success, $this->successStatus);
    }
    
    
    public function service_provider(Request $request) {
        $rules = ['name' => 'required', 'email' => 'required|email|unique:users', 'password' => 'required', 'c_password' => 'required|same:password'];
        $rules = array_merge($this->requiredParams, $rules);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = self::formatValidator($validator);
            return parent::error($errors, 200);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
       
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['user'] = $user;

        $lastId = $user->id;
        
        $selectClientRole = Role::where('name', 'Service-provider')->first();
        $assignRole = DB::table('role_user')->insert(
                ['user_id' => $lastId, 'role_id' => $selectClientRole->id]
        );

        // Add user device details for firbase
        parent::addUserDeviceData($user, $request);
        if ($user->status != 1) {
            return parent::error('Please contact admin to activate your account', 200);
        }
        return parent::success($success, $this->successStatus);
    }
    
    
     public function changePassword(Request $request) {
        $rules = ['old_password' => 'required', 'password' => 'required', 'password_confirmation' => 'required|same:password'];
       
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            if (\Hash::check($request->old_password, \Auth::User()->password)):
                $model = \App\User::find(\Auth::id());
                $model->password = \Hash::make($request->password);
                $model->save();
                return parent::success('Password Changed Successfully');
            else:
                return parent::error('Please use valid old password');
            endif;
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
    

    public function getDirectories(Request $request) {
        $model = new App\Directory;
        if ($model->get()->isEmpty() === false) {
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search))
                $model = $model->Where('name', 'LIKE', "%$request->search%");
            $model = $model->orderBy('id','desc');
            return parent::success($model->paginate($perPage));
//            return parent::success($directories, $this->successStatus);
        } else {
            return parent::error('No Directories Found', 200);
        }
    }

    public function getAlphaLinks(Request $request) {
        $model = new App\AlphaLink;
        if ($model->get()->isEmpty() === false) {
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search))
                $model = $model->Where('name', 'LIKE', "%$request->search%");
            $model = $model->orderBy('id','desc');
            return parent::success($model->paginate($perPage));
        } else {
            return parent::error('No Alpha Links Found', 200);
        }
    }

    public function getMeta(Request $request) {

        $validator = Validator::make($request->all(), [
                    'meta_name' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = self::formatValidator($validator);
            return parent::error($errors, 200);
        }
        $meta = App\Meta::where('meta_name', $request->input('meta_name'))->first();
        if ($meta) {
            return parent::success($meta, $this->successStatus);
        } else {
            return parent::error('No Meta Content Found', 200);
        }
    }

    public function resetPassword(Request $request, Factory $view) {
        //Validating attributes
        $rules = ['email' => 'required'];
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
        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject(trans('front/password.reset'));
                });
//        dd($response);
        switch ($response) {
            case Password::RESET_LINK_SENT:
                return parent::successCreated('Password reset link sent please check inbox');
            case Password::INVALID_USER:
                return parent::error(trans($response));
            default :
                return parent::error(trans($response));
                break;
        }
        return parent::error('Something Went');
    }

    public function getRegisterdUserDetails(Request $request) {
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'GET', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
//            dd(\Auth::id());
            $model = \App\User::whereId(\Auth::id());
            return parent::success($model->first());
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function userUpdate(Request $request) {
        $user = \App\User::findOrFail(\Auth::id());
        if ($user->get()->isEmpty())
            return parent::error('User Not found');
        $rules = ['name' => '', 'dob' => '', 'mobile' => '', 'profile_image' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
//            $input['password'] = Hash::make($request->password);
            if (isset($request->profile_image)):
                $input['profile_image'] = parent::__uploadImage($request->file('profile_image'), public_path('uploads/user/profile_image'));
            endif;
//            var_dump(json_decode($input['category_id']));
//            dd('s');
            $user->fill($input);
            $user->save();
            return parent::successCreated(['Message' => 'Updated Successfully', 'user' => $user]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function userUpdatePassword(Request $request) {
        $user = \App\User::findOrFail(\Auth::id());
        if ($user->get()->isEmpty())
            return parent::error('User Not found');
        $rules = ['password' => 'required|confirmed', 'password_confirmation' => ''];
//        dd($request->all());
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
            $input['password'] = Hash::make($request->password);
            $user->fill($input);
            $user->save();
            return parent::successCreated(['Message' => 'Password Updated Successfully']);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
