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
            $model = $model->select('id', 'model_type', 'model_id', 'payment_status', 'created_by', 'session', 'hours', 'created_at')->where('model_id', \Auth::id())->orderBy('id', 'asc');
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

}
