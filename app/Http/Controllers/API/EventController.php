<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;
use App\Event as MyModel;

class EventController extends ApiController {

    public function getItems(Request $request) {
        $rules = ['search' => '', 'is_special' => '','order_by'=>'required|in:upcoming,past'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        // dd($category_id);
        try {
            $model = MyModel::where('status', '1');
            $perPage = isset($request->limit) ? $request->limit : 20;
            if ($request->is_special != null)
                $model = $model->where('special', $request->is_special);

            if ($request->order_by == 'upcoming')
                $model = $model->whereDate('start_date', '>=', \Carbon\Carbon::now());
            if ($request->order_by == 'past')
                $model = $model->whereDate('start_date', '<', \Carbon\Carbon::now());
            if (isset($request->search)) {
                $model = $model->where(function($query) use ($request) {
                    $query->where('name', 'LIKE', "%$request->search%")
                            ->orWhere('description', 'LIKE', "%$request->search%");
                });
            }
            return parent::success($model->paginate($perPage));
//            return parent::success($model->get());
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
