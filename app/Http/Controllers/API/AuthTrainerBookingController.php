<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Validator;
use App\Booking as Mymodel;

class AuthTrainerBookingController extends ApiController {

    public function getitems(Request $request) {

//        dd(\Auth::id());
        $rules = ['limit' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new Mymodel;
            $model = $model->with(['booking_schedule'])->select('id', 'model_type', 'model_id', 'payment_status', 'created_by', 'session', 'hours', 'created_at')->where('model_id', \Auth::id())->orderBy('id', 'asc');
//            if ($request->model_type != 'all')
            $model = $model->where('model_type', 'trainer_users');
//            unset($model['password']);
//            dd($model->get()->toArray());
            $perPage = isset($request->limit) ? $request->limit : 20;
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }
    public function getitem(Request $request) {
        $rules = ['id' => 'required|exists:bookings,id'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new Mymodel;
            $model = $model->with(['booking_schedule'])->select('id', 'model_type', 'model_id', 'payment_status', 'created_by', 'session', 'hours', 'created_at')->where('id', $request->id)->orderBy('id', 'asc');
            $model = $model->where('model_type', 'trainer_users');
            return parent::success($model->get());
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }
    
    public function getScheduledDates(Request $request) {
        $rules = ['booking_id' => 'required|exists:bookings,id', 'trainer_user_id' => 'required|exists:trainer_users,id'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new \App\BookingSchedule();
            $model = $model->where('booking_id', $request->booking_id)->where('trainer_user_id', $request->trainer_user_id)->select('id', 'booking_id', 'trainer_user_id', 'schedule_date')->orderBy('id', 'asc');
            
            $model = $model->get();
            if(count($model) > 0)
                return parent::success($model);
            return parent::successCreatedNoData(['data' => [], 'message' => 'No Data Found!']);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
