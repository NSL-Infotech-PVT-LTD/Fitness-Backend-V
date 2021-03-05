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
            $model = $model->with(['booking_schedule', 'createdByDetail'])->select('id', 'model_type', 'model_id', 'payment_status', 'created_by', 'session', 'hours', 'created_at', \DB::raw("(SELECT count(id) FROM booking_schedules where booking_id=bookings.id) as book_count"))->where('model_id', \Auth::id())->orderBy('book_count', 'asc');
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
            $model = $model->with(['booking_schedule', 'createdByDetail'])->select('id', 'model_type', 'model_id', 'payment_status', 'created_by', 'session', 'hours', 'created_at')->where('id', $request->id)->orderBy('id', 'asc');
            $model = $model->where('model_type', 'trainer_users');
            return parent::success($model->get());
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }

    public function getScheduledDates(Request $request) {
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new \App\BookingSchedule();
            $model = $model->where('trainer_user_id', \Auth::id())->pluck('schedule_date');
//
//            if (count($model) > 0)
//                return parent::success(['model' => $model]);
            return parent::success(['model' => $model]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    //add Schedule date for booking in booking schedule table
    public function addScheduleDate(Request $request) {
        $rules = ['booking_id' => 'required|exists:bookings,id', 'trainer_user_id' => 'required|exists:trainer_users,id', 'schedule_date' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new \App\BookingSchedule();
            $slot = \App\Booking::whereId($request->booking_id)->value('hours');
            $input = $request->all();
            $totalDate = count(json_decode($request->schedule_date));
            $data = [];
            if ($slot == $totalDate) {
                foreach (json_decode($request->schedule_date) as $date) {
                    $data = [
                        'booking_id' => $input['booking_id'],
                        'trainer_user_id' => $input['trainer_user_id'],
                        'schedule_date' => $date,
                    ];
                    \App\BookingSchedule::insert($data);
                }
            } else {
                return parent::error('Please select ' . $slot . ' slots!');
            }
            return parent::successCreated(['message' => 'Created Successfully']);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
