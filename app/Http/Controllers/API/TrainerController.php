<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Validator;
use App\TrainerUser as Mymodel;
use DB;
use Auth;

class TrainerController extends ApiController {

    public function getitems(Request $request) {
        $rules = ['limit' => '', 'search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new Mymodel;
            $perPage = isset($request->limit) ? $request->limit : 20;
            $model = $model->select('id', 'first_name', 'middle_name', 'last_name', 'image');
            if (isset($request->search)) {
                $model = $model->where(function($query) use ($request) {
                    $query->where('first_name', 'LIKE', "$request->search%")->orWhere('middle_name', 'LIKE', "$request->search%")->orWhere('last_name', 'LIKE', "$request->search%")->orWhere('email', 'LIKE', "$request->search%");
                });
            }
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }

    public function getItem(Request $request) {

        $rules = ['id' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        // dd($category_id);
        try {
            $model = new Mymodel;
            $model = $model->where('id', $request->id);
            $model = $model->select('id', 'first_name', 'middle_name', 'last_name', 'email', 'about', 'services', 'image');
            $related = Mymodel::where('status', '1')->select('id', 'first_name', 'middle_name', 'last_name', 'image')->orderBy(DB::raw('RAND()'))->take(10)->get();
            return parent::success(['trainer' => $model->first(), 'related' => $related]);
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }

    public function getReviewListByTrainerID(Request $request) {
        $rules = ['trainer_id' => 'required|exists:trainer_users,id'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $classschedule = \App\ClassSchedule::where('trainer_id', $request->trainer_id)->get()->pluck('id');
//            dd($request->trainer_id);
            $model = \App\Booking::where('model_type', 'class_schedules')->whereIn('model_id', $classschedule->toArray());
            $model = $model->whereNotNull('rating')->with('createdByDetail');
            $model = $model->select('id', 'review', 'rating','created_at','created_by')->orderBy('id', 'desc');
            $perPage = isset($request->limit) ? $request->limit : 20;
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }

}
