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

    private static function array_has_dupesReturn($array) {
        $dups = array();
        foreach (array_count_values($array) as $val => $c):
            if ($c > 1)
                $dups[] = $val;
        endforeach;
        return $dups;
    }

    private static function array_duplicates(array $array) {
        return array_diff_assoc($array, array_unique($array));
    }

    private static function array_has_dupes($array) {
        return count($array) !== count(array_unique($array));
    }

    private static function bookTrainer($trainerId, $trainerSlot, $userId) {
        \App\Booking::$__AuthID = $userId;
        return \App\Booking::create(['model_type' => 'trainer_users', 'model_id' => $trainerId, 'hours' => $trainerSlot]);
    }

    public function Register(Request $request) {
//        dd(\Carbon\Carbon::now()->addDays(2)->format('Y-m-d'));
//        dd(implode(',',\App\Currency::get()->pluck('id')->toArray()));
//dd(\App\Role::where('id',$request->role_id)->value('member'));
        $emails = [];

//        $rules = ['first_name' => 'required|alpha', 'middle_name' => '', 'last_name' => 'required|alpha', 'child' => '', 'mobile' => 'required|numeric', 'emergency_contact_no' => '', 'email' => 'required|string|max:255|email|unique:users', 'password' => 'required', 'birth_date' => 'required|date_format:Y-m-d|before:today', 'designation' => '', 'emirates_id' => '', 'address' => '', 'role_id' => 'required|exists:roles,id', 'role_plan_id' => '', 'gender' => 'required|in:male,female', 'city' => 'required', 'nationality' => 'required', 'about_us' => '', 'workplace' => '', 'marital_status' => ''];
        if (isset($request->role_plan_id))
            $rules = ['first_name' => 'required|alpha', 'middle_name' => '', 'last_name' => 'required|alpha', 'child' => '', 'mobile' => 'required|numeric', 'emergency_contact_no' => '', 'image' => '', 'email' => 'required|string|max:255|email|unique:users', 'password' => 'required', 'birth_date' => 'required|date_format:Y-m-d|before:today', 'designation' => '', 'emirates_id' => '', 'address' => '', 'role_id' => 'required|exists:roles,id', 'role_plan_id' => '', 'gender' => 'required|in:male,female', 'city' => '', 'nationality' => '', 'about_us' => '', 'workplace' => '', 'marital_status' => ''];
        else
            $rules = ['first_name' => 'required|alpha', 'middle_name' => '', 'last_name' => 'required|alpha', 'child' => '', 'mobile' => '', 'emergency_contact_no' => '', 'email' => 'required|string|max:255|email|unique:users', 'password' => '', 'birth_date' => '', 'designation' => '', 'emirates_id' => '', 'address' => '', 'role_id' => 'required|exists:roles,id', 'role_plan_id' => '', 'gender' => '', 'city' => '', 'nationality' => '', 'about_us' => '', 'workplace' => '', 'marital_status' => '', 'hotel_room_no' => '', 'duration_of_stay' => '', 'check_in' => '', 'check_out' => ''];


        $rules = array_merge($this->requiredParams, $rules);
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        $emails['email'] = $request->email;
//      $role = \App\Role::whereId($request->role_id)->first();
        $checkRole = \DB::table('roles')->whereId($request->role_id)->first();
        $roleMember = (int) $checkRole->member;
//        dd($checkRole, $roleMember);
//        if ($checkRole->type == 'user'):
        if (in_array($checkRole->type, ['user', 'user_with_child'])):
//            dd($checkRole->category);            
            $rules = ['first_name' => 'required', 'middle_name' => '', 'last_name' => 'required', 'mobile' => 'required|numeric', 'email' => 'required|string|max:255|email|unique:users,email', 'gender' => 'required|in:male,female', 'trainer_id' => '', 'trainer_slot' => ''];
            $finalRules = [];
            foreach ($rules as $key => $rule):
                for ($i = 1; $i < $roleMember; $i++):
                    $finalRules[$key . '_' . $i] = $rule;
                endfor;
            endforeach;
//                dd($finalRules);
//                foreach ($rules as $key => $rule):
//                    $finalRules[$key . '_1'] = $rule;
//                    if ($checkRole->category == 'family_with_1')
//                        $finalRules[$key . '_2'] = $rule;
//                    if ($checkRole->category == 'family_with_2'):
//                        $finalRules[$key . '_2'] = $rule;
//                        $finalRules[$key . '_3'] = $rule;
//                    endif;
//                endforeach;
//                dd($request->all(),$finalRules);
            $validateAttributes = parent::validateAttributes($request, 'POST', $finalRules, array_keys($finalRules), false);
            if ($validateAttributes):
                return $validateAttributes;
            endif;

//                if (isset($request->email_1))
//                    $emails['email_1'] = $request->email_1;
//                if (isset($request->email_2))
//                    $emails['email_2'] = $request->email_2;
//                if (isset($request->email_3))
//                    $emails['email_3'] = $request->email_3;
            for ($i = 1; $i < $roleMember; $i++):
                $var = 'email_' . $i;
                if (isset($request->$var))
                    $emails[$var] = $request->$var;
            endfor;
//            dd($emails);
        endif;
        $emails = self::array_duplicates($emails);
        if (count($emails) > 0)
            return parent::error([array_key_first($emails) => 'The ' . array_key_first($emails) . ' has already been taken.'], 422, false);
//        self::getRequestByRK($request->all(), '_1');
//        dd(array_merge(self::getRequestByRK($request->all(), '_1'), ['parent_id' => 121]));
//        $roleplan = \App\RolePlans::whereId($request->role_plan_id);
//
//        $paymentFunction = \App\Helpers\ScapePanel::paymentFunction(['firstName' => $request->first_name, 'lastName' => $request->last_name, 'email' => $request->email], $roleplan->value('fee_type'), $roleplan->value('fee'));
//        dd('ss');
        try {
            $input = $request->all();
            $input['password'] = Hash::make($request->password);
            if (isset($request->image))
                if (!empty($request->file('image')))
                    $input['image'] = parent::__uploadImage($request->file('image'), public_path('uploads/image'));
//            dd(\App\Role::whereId($request->role_id)->first()->name);
            $user = \App\User::create($input);
            //Assign role to created user
            $user->assignRole($request->role_id, 'id');
            if (isset($request->role_plan_id)):
                \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $user->id)->update(['role_plan_id' => $request->role_plan_id]);
//
//                //Payment function start
//                $paymentFunction = \App\Helpers\ScapePanel::paymentFunction($user, $user->id, \App\RolePlans::whereId($request->role_plan_id)->value('fee'));
//                if ($paymentFunction == false)
//                    return parent::error("something went wrong while sending payment link");
//                \App\Http\Controllers\Admin\UsersController::mailSend(array_merge($input, ['payment_href' => $paymentFunction]), $request);
//            //Payment function end
            else:
                $user = User::findOrFail($user->id);
                $user->status = '1';
                $user->save();
            endif;
//            if ($checkRole->type == 'user'):

            if (in_array($checkRole->type, ['user', 'user_with_child'])):
                for ($i = 1; $i < $roleMember; $i++):
                    $dataUser = array_merge(self::getRequestByRK($request->all(), '_' . $i), ['parent_id' => $user->id]);
                    $m1 = \App\User::create($dataUser);
                    //Assign role to created user
                    $m1->assignRole($request->role_id, 'id');
                    if (isset($request->role_plan_id))
                        \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $m1->id)->update(['role_plan_id' => $request->role_plan_id]);
                    if ($dataUser['trainer_id'] != '' && $dataUser['trainer_slot'] != '')
                        self::bookTrainer($dataUser['trainer_id'], $dataUser['trainer_slot'], $m1->id);
                endfor;

//                if (in_array($checkRole->category, ['couple', 'family_with_2', 'family_with_1'])):
////                    dd(array_merge(self::getRequestByRK($request->all(), '_1'), ['parent_id' => $user->id]));
//                    switch ($checkRole->category):
//                        case'couple':
//                            $m1 = \App\User::create(array_merge(self::getRequestByRK($request->all(), '_1'), ['parent_id' => $user->id]));
//                            //Assign role to created user
//                            $m1->assignRole($request->role_id, 'id');
//                            if (isset($request->role_plan_id))
//                                \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $m1->id)->update(['role_plan_id' => $request->role_plan_id]);
//                            break;
//                        case'family_with_1':
//                            $m2 = \App\User::create(array_merge(self::getRequestByRK($request->all(), '_2'), ['parent_id' => $user->id]));
//                            //Assign role to created user
//                            $m2->assignRole($request->role_id, 'id');
//                            if (isset($request->role_plan_id))
//                                \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $m2->id)->update(['role_plan_id' => $request->role_plan_id]);
//                            break;
//                        case'family_with_2':
//                            $m31 = \App\User::create(array_merge(self::getRequestByRK($request->all(), '_2'), ['parent_id' => $user->id]));
//                            //Assign role to created user
//                            $m31->assignRole($request->role_id, 'id');
//                            if (isset($request->role_plan_id))
//                                \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $m31->id)->update(['role_plan_id' => $request->role_plan_id]);
//
//                            $m32 = \App\User::create(array_merge(self::getRequestByRK($request->all(), '_3'), ['parent_id' => $user->id]));
//                            //Assign role to created user
//                            $m32->assignRole($request->role_id, 'id');
//                            if (isset($request->role_plan_id))
//                                \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $m32->id)->update(['role_plan_id' => $request->role_plan_id]);
//                            break;
//                    endswitch;
//                endif;
            endif;
            // create user token for authorization
            $token = $user->createToken('netscape')->accessToken;

            if ($request->trainer_id != '' && $request->trainer_slot != '')
                self::bookTrainer($request->trainer_id, $request->trainer_id, $user->id);
//            testing comment
            // Add user device details for firbase
            parent::addUserDeviceData($user, $request);
            return parent::successCreated(['message' => 'Created Successfully', 'token' => $token, 'user' => $user]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function Update(Request $request) {

        $rules = ['first_name' => 'required|alpha', 'middle_name' => '', 'last_name' => 'required|alpha', 'child' => '', 'mobile' => '', 'emergency_contact_no' => '', 'birth_date' => 'required|date_format:Y-m-d|before:today', 'designation' => '', 'emirates_id' => '', 'address' => '', 'image' => '', 'city' => '', 'nationality' => 'required', 'about_us' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
//            dd($input);
//            $input['sport_id']= json_encode($request->sport_id);
            if (isset($request->image))
                if (!empty($request->file('image')))
                    $input['image'] = parent::__uploadImage($request->file('image'), public_path('uploads/image'), true);

            $user = \App\User::findOrFail(\Auth::id());
            $user->fill($input);
            $user->save();

            $user = \App\User::whereId($user->id)->select('first_name', 'middle_name', 'last_name', 'mobile', 'emergency_contact_no', 'email', 'password', 'birth_date', 'marital_status', 'designation', 'emirates_id', 'address', 'status', 'image', 'parent_id', 'gender', 'city')->first();
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
                if ($user->payment_status != 'AUTHORISED' || $user->payment_status != 'CAPTURED')
                    return parent::error("Account is Approved but the payment is not authorized  or captured yet !");

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
        $rules = [];
        if (isset($request->type))
            $rules += ['type' => 'required|in:gym_members,pool_and_beach_members,local_guest,fairmont_hotel_guest', 'user_with_child_only' => 'required|in:true,false'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
//         dd($rules);
        try {
            $model = \App\Role::where('status', '1');
            $return = [];
            foreach ($model->select('name')->get()->flatten()->unique() as $data):
//                dd($request->user_with_child_only);
                $var = strtolower(str_replace(' ', '_', $data->name));
                $role = \App\Role::where('status', '1');
                if ($request->user_with_child_only == 'false')
                    $role = $role->where('type', '!=', 'user_with_child');
                elseif ($request->user_with_child_only == 'true')
                    $role = $role->where('type', '=', 'user_with_child');
                else
                    $role = $role->where('type', '!=', 'user_with_child');

                $return[$var] = $role->where('name', $data->name)->with('PlanDetail')->get();
//                $return[] = ['plan_name' => $data->name, 'data' => \App\Role::where('type', $request->type)->where('status', '1')->where('name', $data->name)->with('PlanDetail')->get()];
//                $return[] = [$data->name => \App\Role::where('type', $request->type)->where('status', '1')->where('name', $data->name)->with('PlanDetail')->get()];
            endforeach;

            if ($request->type != '')
                return parent::success($return[$request->type]);
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
            $model = \App\User::where('id', \Auth::id());
//            $model = \App\User::select('id', 'first_name', 'middle_name', 'last_name', 'mobile', 'emergency_contact_no', 'email', 'password', 'birth_date', 'marital_status', 'designation', 'emirates_id', 'address', 'status', 'image', 'parent_id', 'gender', 'city', 'nationality', 'about_us','my_sessions')->where('id', \Auth::id());
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

    public function upgradePlan(Request $request) {
        $rules = ['role_plan_id' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
//            dd(\Auth::user()->role->id);
            $role_id = \Auth::user()->role->id;
//            dd($role_id);
            $role_plan_id = \App\RolePlans::where('role_id', $role_id)->get()->pluck('id')->toArray();
//            dd($role_plan_id);
            if (!in_array($request->role_plan_id, $role_plan_id))
                return parent::error('Choose valid role plan id, it must be under current role plan');

            \App\RoleUser::where('role_id', $role_id)->where('user_id', \Auth::id())->update(['role_plan_id' => $request->role_plan_id]);
            $user = \App\User::findOrFail(\Auth::id());
            $user->status = 0;
            $user->save();
            return parent::successCreated(['message' => 'Updated Successfully wait let admin approve']);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
