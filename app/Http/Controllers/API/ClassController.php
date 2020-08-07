<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;
use App\Classes as MyModel;

class ClassController extends ApiController {

    public function getClases(Request $request) {
        $rules = ['search' => '', 'limit' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = MyModel::where('status', '1')->select('name', 'price', 'image', 'description');
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

    public function submitClassSchedule(Request $request) {
        $rules = ['class_type' => 'required', 'start_date' => 'required', 'repeat_on' => 'required', 'start_time' => 'required', 'duration' => 'required', 'trainer_id' => 'required', 'cp_spots' => 'required', 'capacity' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $requestData = $request->all();
//dd($requestData);
            if (isset($requestData['repeat_on']))
                $requestData['repeat_on'] = json_encode($requestData['repeat_on']);
            ClassSchedule::create($requestData);
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
