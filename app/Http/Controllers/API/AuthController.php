<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Validator;
use App\User;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\Factory;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

class AuthController extends ApiController {

   

    private static function getRequestByRK($data, $rk) {
        $return = [];
//        $rk = '_1';
        foreach ($data as $key => $value):
            if (strpos($key, $rk) == true):
                $return[str_replace($rk, '', $key)] = $value;
                if (str_replace($rk, '', $key) == 'password')
                    $return[str_replace($rk, '', $key)] = Hash::make($value);
            endif;
        endforeach;
//        dd($return);
        return $return;
    }

    public function Register(Request $request) {
//        dd(implode(',',\App\Currency::get()->pluck('id')->toArray()));
        $rules = ['first_name' => 'required|alpha', 'middle_name' => '', 'last_name' => 'required|alpha', 'child' => '', 'mobile' => 'required|numeric', 'emergency_contact_no' => '', 'email' => 'required|string|max:255|email|unique:users', 'password' => 'required', 'birth_date' => 'required|date_format:Y-m-d|before:today', 'designation' => '', 'emirates_id' => '', 'address' => '', 'role_id' => 'required|exists:roles,id', 'role_plan_id' => '','gender'=>'required|in:male,female','city'=>'required'];


        $rules = array_merge($this->requiredParams, $rules);
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
//      $role = \App\Role::whereId($request->role_id)->first();
        $checkRole = \DB::table('roles')->whereId($request->role_id)->first();
//        dd($request->all(), $checkRole);
        if ($checkRole->type == 'user'):
//            dd($checkRole->category);            
            if (in_array($checkRole->category, ['couple', 'family_with_2', 'family_with_1'])):
                $rules = ['first_name' => 'required', 'middle_name' => '', 'last_name' => 'required', 'mobile' => 'required|numeric', 'email' => 'required|string|max:255|email|unique:users,email','gender'=>'required|in:male,female'];
                $finalRules = [];
                foreach ($rules as $key => $rule):
                    $finalRules[$key . '_1'] = $rule;
                    if ($checkRole->category == 'family_with_1')
                        $finalRules[$key . '_2'] = $rule;
                    if ($checkRole->category == 'family_with_2'):
                        $finalRules[$key . '_2'] = $rule;
                        $finalRules[$key . '_3'] = $rule;
                    endif;
                endforeach;
//                dd($request->all(),$finalRules);
                $validateAttributes = parent::validateAttributes($request, 'POST', $finalRules, array_keys($finalRules), false);
                if ($validateAttributes):
                    return $validateAttributes;
                endif;
            endif;
        endif;
//        self::getRequestByRK($request->all(), '_1');
//        dd(array_merge(self::getRequestByRK($request->all(), '_1'), ['parent_id' => 121]));
        try {
            $input = $request->all();
            $input['password'] = Hash::make($request->password);
            if (isset($request->image))
                $input['image'] = parent::__uploadImage($request->file('image'), public_path('uploads/image'), true);
//            dd(\App\Role::whereId($request->role_id)->first()->name);
            $user = \App\User::create($input);
            //Assign role to created user
            $user->assignRole($request->role_id, 'id');
            if (isset($request->role_plan_id))
                \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $user->id)->update(['role_plan_id' => $request->role_plan_id]);
//            dd('s');
            if (isset($request->role_plan_id))
                \App\Http\Controllers\Admin\UsersController::mailSend($input, $request);
            if ($checkRole->type == 'user'):
                if (in_array($checkRole->category, ['couple', 'family_with_2', 'family_with_1'])):
//                    dd(array_merge(self::getRequestByRK($request->all(), '_1'), ['parent_id' => $user->id]));
                    switch ($checkRole->category):
                        case'couple':
                            $m1 = \App\User::create(array_merge(self::getRequestByRK($request->all(), '_1'), ['parent_id' => $user->id]));
                            //Assign role to created user
                            $m1->assignRole($request->role_id, 'id');
                            if (isset($request->role_plan_id))
                                \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $m1->id)->update(['role_plan_id' => $request->role_plan_id]);
                            break;
                        case'family_with_1':
                            $m2 = \App\User::create(array_merge(self::getRequestByRK($request->all(), '_2'), ['parent_id' => $user->id]));
                            //Assign role to created user
                            $m2->assignRole($request->role_id, 'id');
                            if (isset($request->role_plan_id))
                                \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $m2->id)->update(['role_plan_id' => $request->role_plan_id]);
                            break;
                        case'family_with_2':
                            $m31 = \App\User::create(array_merge(self::getRequestByRK($request->all(), '_2'), ['parent_id' => $user->id]));
                            //Assign role to created user
                            $m31->assignRole($request->role_id, 'id');
                            if (isset($request->role_plan_id))
                                \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $m31->id)->update(['role_plan_id' => $request->role_plan_id]);

                            $m32 = \App\User::create(array_merge(self::getRequestByRK($request->all(), '_3'), ['parent_id' => $user->id]));
                            //Assign role to created user
                            $m32->assignRole($request->role_id, 'id');
                            if (isset($request->role_plan_id))
                                \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $m32->id)->update(['role_plan_id' => $request->role_plan_id]);
                            break;
                    endswitch;
                endif;
            endif;
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

    public function Update(Request $request) {

        $rules = ['first_name' => 'required|alpha', 'middle_name' => '', 'last_name' => 'required|alpha', 'child' => '', 'mobile' => '', 'emergency_contact_no' => '', 'birth_date' => 'required|date_format:Y-m-d|before:today', 'designation' => '', 'emirates_id' => '', 'address' => '', 'image' => '','city'=>''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
//            dd($input);
//            $input['sport_id']= json_encode($request->sport_id);
            if (isset($request->image))
                $input['image'] = parent::__uploadImage($request->file('image'), public_path('uploads/image'), true);

            $user = \App\User::findOrFail(\Auth::id());
            $user->fill($input);
            $user->save();

            $user = \App\User::whereId($user->id)->select('first_name', 'middle_name', 'last_name', 'mobile', 'emergency_contact_no', 'email', 'password', 'birth_date', 'marital_status', 'designation', 'emirates_id', 'address', 'status', 'image', 'parent_id','gender','city')->first();
            return parent::successCreated(['message' => 'Updated Successfully', 'user' => $user]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function Login(Request $request) {
        $rules = ['email' => 'required', 'password' => 'required'];
        $rules = array_merge($this->requiredParams, $rules);
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                $user = \App\User::find(Auth::user()->id);
                if ($user->status == '0')
                    return parent::error("Account is not approved yet, Kindly Contact Admin for more detail.");
                $token = $user->createToken('netscape')->accessToken;
                parent::addUserDeviceData($user, $request);
                return parent::success(['message' => 'Login Successfully', 'token' => $token, 'user' => $user]);
            } else {
                return parent::error("User credentials doesn't matched");
            }
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function getRoles(Request $request) {
        $rules = ['search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        // dd($category_id);
        try {
            $model = \App\Role::where('status', '1');
            $return = [];
            foreach ($model->select('name')->get()->flatten()->unique() as $data):
//                dd($data->name);
                $var = strtolower(str_replace(' ', '_', $data->name));
                $return[$var] = \App\Role::where('status', '1')->where('name', $data->name)->with('PlanDetail')->get();
//                $return[] = ['plan_name' => $data->name, 'data' => \App\Role::where('type', $request->type)->where('status', '1')->where('name', $data->name)->with('PlanDetail')->get()];
//                $return[] = [$data->name => \App\Role::where('type', $request->type)->where('status', '1')->where('name', $data->name)->with('PlanDetail')->get()];
            endforeach;
//            dd($return);
            return parent::success($return, 200, 'array');
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

//    public function getRoles(Request $request) {
//        $rules = ['search' => ''];
//        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
//        if ($validateAttributes):
//            return $validateAttributes;
//        endif;
//        // dd($category_id);
//        try {
//            $model = \App\Role::where('status', '1');
////            $perPage = isset($request->limit) ? $request->limit : 20;
//            if (isset($request->search)) {
//                $model = $model->where(function($query) use ($request) {
//                    $query->where('name', 'LIKE', "%$request->search%")
//                            ->orWhere('description', 'LIKE', "%$request->search%");
//                });
//            }
////            return parent::success($model->paginate($perPage));
//            return parent::success($model->get());
//        } catch (\Exception $ex) {
//            return parent::error($ex->getMessage());
//        }
//    }


    public function getProfile(Request $request) {
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = \App\User::select('id','first_name', 'middle_name', 'last_name', 'mobile', 'emergency_contact_no', 'email', 'password', 'birth_date', 'marital_status', 'designation', 'emirates_id', 'address', 'status', 'image', 'parent_id','gender','city')->where('id', \Auth::id());
            return parent::success(['user' => $model->first()]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function resetPassword(Request $request, Factory $view) {


        //Validating attributes
        $rules = ['email' => 'required|exists:users,email'];
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

            $user = \App\User::findOrFail(\Auth::id());
//            $user->is_login = '0';
//            $user->save();
            $device = \App\UserDevice::where('user_id', \Auth::id())->get();
//            dd($device);
            if ($device->isEmpty() === false)
                \App\UserDevice::destroy($device->first()->id);

            return parent::successCreated('Logout Successfully');
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
