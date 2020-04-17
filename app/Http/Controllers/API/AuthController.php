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
        $rules = ['name' => 'required', 'email' => 'required|email|unique:users', 'password' => 'required', 'mobile' => 'required|unique:users', 'location' => 'required', 'dob' => 'required', 'image' => ''];
        $rules = array_merge($this->requiredParams, $rules);
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
            $input['password'] = Hash::make($request->password);
            if (isset($request->image))
                $input['image'] = parent::__uploadImage($request->file('image'), public_path('uploads/image'), true);
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

    public function getitems(Request $request) {
        $rules = ['search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        // dd($category_id);
        try {
            $user = \App\User::findOrFail(\Auth::id());
            $model = new \App\Tournament();
            $model = $model->select('id', 'name', 'image', 'location', 'price', 'description', 'start_date', 'end_date', 'rules', 'privacy_policy');
            $model = $model->where('state', '1');
            $perPage = isset($request->limit) ? $request->limit : 20;

            if (isset($request->search)) {
                $model = $model->where(function($query) use ($request) {
                    $query->where('name', 'LIKE', "%$request->search%")
                            ->orWhere('description', 'LIKE', "%$request->search%");
                });
            }


            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

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

    public function enroll(Request $request) {
        $rules = ['type' => '', 'price' => '', 'token' => '', 'tournament_id' => 'required|exists:tournaments,id', 'size' => '', 'images' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());
            $model = Tournament::findorfail($request->tournament_id);
            $input = $request->all();
            $input['customer_id'] = \Auth::id();

            //start and end date check starts//
//            if ($model->whereDate("start_date", '>',\Carbon\Carbon::now())->get()->isEmpty() != true)
//                return parent::error('Sorry, You cant Enroll before start date');
//            if ($model->whereDate("end_date", '<', \Carbon\Carbon::now())->get()->isEmpty() != true)
//                return parent::error('Sorry, You cant Enroll after end date');
            // start and end date check ends//


            $oldenroll = \App\EnrollTournaments::where('tournament_id', $request->tournament_id)->where('customer_id', \Auth::id())->value('id');
//            dd($oldenroll);
            if (\App\EnrollTournaments::where('tournament_id', $request->tournament_id)->where('customer_id', \Auth::id())->get()->isEmpty() === false) {

                if ($files = $request->file('images')) {
                    foreach ($files as $file) {
                        $img = self::imageUpload($file, $request->tournament_id, $oldenroll, $request->type, $request->size);

                        \App\Image::create($img);
                    }
                }
                return parent::successCreated(['message' => 'Images added Successfully', 'images' => $img]);
            } else {

                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                if (\App\Tournament::where('id', $request->tournament_id)->where('price', '!=', '0')->get()->isEmpty() === false) {
                    $stripe = \Stripe\Charge::create([
                                "amount" => $model->price * 100,
                                "currency" => config('app.stripe_default_currency'),
                                "source" => $request->token, // obtained with Stripe.js
                                "description" => "Charge for the enrollments of tournamnets in fishing project"
                    ]);
                    $enroll = EnrollTournaments::create($input);
                    $enroll->payment_details = json_encode($stripe);
                    $enroll->payment_id = $stripe->id;
                    $enroll->save();
                }


                if (\App\Tournament::where('id', $request->tournament_id)->where('price', '=', '0')->get()->isEmpty() === false) {
                    $enroll = EnrollTournaments::create($input);
                }
//                

                return parent::successCreated(['message' => 'Enrolled Successfully', 'enroll' => $enroll]);
            }
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function getMyenroll(Request $request) {


        $getMyTournamnents = EnrollTournaments::where('customer_id', Auth::id())->distinct('tournament_id')->pluck('tournament_id');
        $getTuornament = Tournament::whereIn('id', $getMyTournamnents)->get();
        if ($getTuornament) {
            return parent::success(['tournaments' => $getTuornament]);
        } else {
            return parent::error('No Tournaments Found');
        }
    }

    public function getTournamentDetails(Request $request) {
        $rules = ['tournament_id' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;


        $mySubmittedEnrollments = EnrollTournaments::where('customer_id', Auth::id())->where('tournament_id', $request->tournament_id)->with('allImages')->with('userdetails')->get();

        $worldWideEnrollments = EnrollTournaments::where('customer_id', '!=', Auth::id())->where('tournament_id', $request->tournament_id)->with('userdetails')->get();

//        $winner = EnrollTournaments::where('tournament_id', $request->tournament_id)->where('status', '1')->with('userdetails')->get();

        $winner = EnrollTournaments::where('tournament_id', $request->tournament_id)->where('status', '1')->with('allImages')->with('userdetails')->first();



        return parent::success(['mySubmittedEnrollments' => $mySubmittedEnrollments, 'worldWideEnrollments' => $worldWideEnrollments, 'winner' => $winner]);
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
