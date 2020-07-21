<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Validator;
use App\Tournament;
use App\User;
use App\EnrollTournaments;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\Factory;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use Stripe;

class AuthController extends ApiController {

    public static function imageUpload($file, $tournament_id, $enrollment_id, $type, $size) {


        $path = public_path('uploads/images');
        $name = time() . rand(1, 10) . $file->getClientOriginalName();
        $file->move($path, $name);
        $inputNew['images'] = $name;
        $inputNew['customer_id'] = \Auth::id();
        $inputNew['tournament_id'] = $tournament_id;
        $inputNew['enrollment_id'] = $enrollment_id;
        $inputNew['type'] = $type;
        $inputNew['size'] = $size;


        return $inputNew;
    }

    public function Register(Request $request) {
//        dd(implode(',',\App\Currency::get()->pluck('id')->toArray()));
        $rules = ['first_name' => 'required|alpha', 'middle_name' => '', 'last_name' => 'required|alpha', 'child' => '', 'mobile' => 'required|numeric', 'emergency_contact_no' => '', 'email' => 'required|string|max:255|email|unique:users', 'password' => 'required', 'birth_date' => 'required|date_format:Y-m-d|before:today', 'designation' => '', 'emirates_id' => '', 'address' => '', 'role_id' => 'required|exists:roles,id', 'role_plan_id' => ''];


        $rules = array_merge($this->requiredParams, $rules);
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
//      $role = \App\Role::whereId($request->role_id)->first();
        $role = \DB::table('roles')->whereId($request->role_id)->first();
//        dd($request->all(), $role);
        if ($role->type == 'user'):
//            dd($role->category);
            if (in_array($role->category, ['couple', 'family_with_2'])):
                $rules = ['first_name' => 'required|alpha', 'middle_name' => '', 'last_name' => 'required', 'mobile' => 'required|numeric', 'email' => 'required|string|max:255|email|unique:users'];
                $finalRules = [];
                foreach ($rules as $key => $rule):
                    $finalRules[$key . '_1'] = $rule;
                    if ($role->category == 'family_with_2'):
                        if ($request->child == '1_child'):
                            $finalRules[$key . '_2'] = $rule;
                        endif;
                        if ($request->child == '2_child'):
                            $finalRules[$key . '_2'] = $rule;
                            $finalRules[$key . '_3'] = $rule;
                        endif;
                    endif;
                endforeach;
//                dd($finalRules);
                $validateAttributes = parent::validateAttributes($request, 'POST', $finalRules, array_keys($rules), false);
                if ($validateAttributes):
                    return $validateAttributes;
                endif;
            endif;
        endif;
//        dd($request->all());

        try {
            $input = $request->all();
            $input['password'] = Hash::make($request->password);
            if (isset($request->image))
                $input['image'] = parent::__uploadImage($request->file('image'), public_path('uploads/image'), true);

            $user = \App\User::create($input);
            //Assign role to created user
            $role = \App\Role::whereId($request->role_id)->first()->name;
            $user->assignRole($role);
            if (isset($request->role_plan_id))
                \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $user->id)->update(['role_plan_id' => $request->role_plan_id]);
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
        $user = \App\User::findOrFail(\Auth::id());

        $rules = ['name' => '', 'location' => '', 'image' => '', 'dob' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
//            $input['sport_id']= json_encode($request->sport_id);
            if (isset($request->image))
                $input['image'] = parent::__uploadImage($request->file('image'), public_path('uploads/image'), true);

            $user->fill($input);
            $user->save();

            $user = \App\User::whereId($user->id)->select('id', 'name', 'email', 'mobile', 'location', 'dob', 'image')->first();
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

    public function getitem(Request $request) {

        $rules = ['id' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        // dd($category_id);
        try {
            $model = new Tournament;
            $model = $model->where('id', $request->id);
            return parent::success($model->first());
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
