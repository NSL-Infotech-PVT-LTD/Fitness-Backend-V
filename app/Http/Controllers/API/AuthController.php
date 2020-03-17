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

    public static function imageUpload($file, $tournament_id, $enrollment_id) {
        
       
        $path = public_path('uploads/images');
        $name = time() . rand(1, 10) . $file->getClientOriginalName();
        $file->move($path, $name);
        $inputNew['images'] = $name;
        $inputNew['customer_id'] = \Auth::id();
        $inputNew['tournament_id'] = $tournament_id;
        $inputNew['enrollment_id'] = $enrollment_id;

        return $inputNew;
    }

    public function Register(Request $request) {
//        dd(implode(',',\App\Currency::get()->pluck('id')->toArray()));
        $rules = ['name' => 'required', 'email' => 'required|email|unique:users', 'password' => 'required', 'mobile' => 'required|unique:users', 'location' => 'required', 'dob' => 'required'];
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
            $model = $model->select('id', 'name', 'image', 'price', 'description');
//            $model = $model->where('state','1');
            $perPage = isset($request->limit) ? $request->limit : 20;
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
        $rules = ['type' => 'required', 'token' => 'required', 'tournament_id' => 'required', 'size' => 'required', 'images' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());
            $model = Tournament::findorfail($request->tournament_id);
            $input = $request->all();
            $input['customer_id'] = \Auth::id();


            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $stripe = \Stripe\Charge::create([
                        "amount" => $model->price * 100,
                        "currency" => config('app.stripe_default_currency'),
                        "source" => $request->token, // obtained with Stripe.js
                        "description" => "Charge for the enrollments of tournamnets in fishing project"
            ]);
//             dd('ss');
            $enroll = EnrollTournaments::create($input);
//            dd($enroll);
           
            if ($files = $request->file('images')) {
                foreach ($files as $file) {
                    $img = self::imageUpload($file, $request->tournament_id,$enroll->id);
                  
                    \App\Image::create($img);
                }
            }

            $enroll->payment_details = json_encode($stripe);
            $enroll->payment_id = $stripe->id;
            $enroll->save();

            return parent::successCreated(['message' => 'Created Successfully', 'enroll' => $enroll]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function getMyenroll(Request $request) {
        //Validating attributes
        $rules = ['limit' => '', 'search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new EnrollTournaments();
            $model = $model->orderBy('created_at', 'desc');
            $model = $model->where('customer_id', \Auth::id())->select('id', 'type', 'size', 'tournament_id', 'payment_id', 'created_at')->with('tournament')->with('userdetails');
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search)):
//                dd($request->search);
                $model = $model->whereHas('userdetails', function ($query)use($request) {
                    $query->Where('name', 'LIKE', "%$request->search%")->orWhere('email', 'LIKE', "%$request->search%");
                });
            endif;
            $model = $model->orderBy('created_at', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
    
     public function getAllenrollUsers(Request $request) {
        //Validating attributes
        $rules = ['tournament_id'=>'required','limit' => '', 'search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new EnrollTournaments();
            $model = $model->orderBy('created_at', 'desc');
            $model = $model->where('tournament_id',$request->tournament_id)->select('id', 'type', 'size', 'tournament_id','customer_id', 'payment_id', 'created_at')->with(['tournament','userdetails']);
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search)):
//                dd($request->search);
                $model = $model->whereHas('userdetails', function ($query)use($request) {
                    $query->Where('name', 'LIKE', "%$request->search%")->orWhere('email', 'LIKE', "%$request->search%");
                });
            endif;
            $model = $model->orderBy('created_at', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
