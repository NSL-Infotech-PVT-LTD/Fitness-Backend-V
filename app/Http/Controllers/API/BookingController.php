<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Validator;
use App\Booking as Mymodel;

class BookingController extends ApiController {

    public function store(Request $request) {
//        dd(implode(',',\App\Currency::get()->pluck('id')->toArray()));
        $rules = ['model_type' => 'required|in:events,class_schedules,trainer_users,sessions'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        $rules += ['model_id' => 'required|exists:' . $request->model_type . ',id'];
        if ($request->model_type == 'class_schedules')
            $rules += ['session' => 'required|in:1,6,12'];
        else if ($request->model_type == 'sessions')
            $rules += ['session' => 'required|in:1,6,12'];
        else if ($request->model_type == 'trainer_users')
            $rules += ['hours' => 'required|in:1,6,12,24'];
        if ($request->model_type == 'sessions')
            unset($rules['model_id']);
//        dd();
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
//            if (\Auth::user()->role->id == '8')
            if ($request->model_type == 'class_schedules')
                if (\App\User::whereId(\Auth::id())->first()->my_sessions < $request->session)
                    return parent::error('Please buy more session to book your booking, your current session count is ' . \App\User::whereId(\Auth::id())->first()->my_sessions);

            if ($request->model_type != 'sessions')
                if (Mymodel::where('model_id', $request->model_id)->where('model_type', $request->model_type)->where('created_by', \Auth::id())->get()->isEmpty() !== true)
                    return parent::error('Booking already in place');

            if ($request->model_type == 'trainer_users')
                if (Mymodel::where('model_type', 'trainer_users')->where('created_by', \Auth::id())->get()->count() >= 1)
                    return parent::error('You can book one trainer at one time');
//            endif;
            $input = [];
            if ($request->model_type == 'class_schedules')
                $input = $request->only('model_type', 'model_id', 'session');
            else if ($request->model_type == 'sessions')
                $input = $request->only('model_type', 'session');
            else if ($request->model_type == 'trainer_users')
                $input = $request->only('model_type', 'model_id', 'hours');
            else
                $input = $request->only('model_type', 'model_id');


            $model = Mymodel::create($input);
//            if (\Auth::user()->role->id == '8'):
            if ($request->model_type == 'class_schedules'):
                $user = \App\User::findOrFail(\Auth::id());
                $user->my_sessions = $user->my_sessions - $request->session;
                $user->save();
            endif;
            if ($request->model_type == 'sessions'):
                $user = \App\User::find(\Auth::id());
                \App\Helpers\ScapePanel::paymentFunction($user, $model->id, (self::$__session[$request->session]));
            endif;
            if ($request->model_type == 'trainer_users'):
                $user = \App\User::find(\Auth::id());
                \App\Helpers\ScapePanel::paymentFunction($user, $model->id, (self::$__trainer[$request->hours]));
            endif;
//            endif;
//            dd($model['created_by']);
            //Send to the artist
//            parent::pushNotifications(['title' => 'Confirmed', 'body' => 'Booking confirmation', 'data' => ['target_id' => $model['created_by'], 'target_model' => 'Booking', 'data_type' => 'Booking']], $model->created_by, TRUE);
            parent::pushNotifications(['title' => 'Booking Requested', 'body' => 'Booking Requested Successfully', 'data' => ['target_id' => $model->id, 'target_model' => 'Booking', 'data_type' => 'Booking']], $model->created_by, TRUE, ['template_name' => 'notify', 'subject' => 'Your Booking is Requested', 'customData' => ['notifyMessage' => "Your booking has been in request status "]]);
            return parent::successCreated(['message' => 'Created Successfully', 'booking' => $model]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    private static $__session = ['1' => '60', '6' => '340', '12' => '660'];
    private static $__trainer = ['1' => '250', '6' => '1400', '12' => '2600', '24' => '5000'];

    public function getitems(Request $request) {
        $rules = ['limit' => '', 'model_type' => 'required|in:class_schedules,trainer_users,events,sessions,all'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new Mymodel;
            $model = $model->select('id', 'model_type', 'model_id', 'payment_status', 'created_by', 'session', 'hours', 'created_at', 'status')->where('created_by', \Auth::id())->orderBy('id', 'desc');
            if ($request->model_type != 'all')
                $model = $model->where('model_type', $request->model_type);
            $model = $model->where('model_type', '!=', 'users');
            $perPage = isset($request->limit) ? $request->limit : 20;
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }

    public function deleteItem(Request $request) {
        $rules = ['id' => [
                'required',
                \Illuminate\Validation\Rule::exists('bookings')->where(function ($query)use($request) {
                            $query->where('id', $request->id);
                            $query->where('created_by', \Auth::id());
                        }),
        ]];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            Mymodel::where('id', $request->id)->delete();
            return parent::successCreated('Booking Deleted Succesfully !');
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }

}
