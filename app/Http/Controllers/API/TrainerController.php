<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Validator;
use App\Tournament;
use App\TrainerUser as Mymodel;
use App\EnrollTournaments;
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
            $model = $model->select('id', 'first_name', 'middle_name', 'last_name', 'mobile_prefix', 'mobile', 'emergency_contact_no_prefix', 'emergency_contact_no', 'email', 'password', 'birth_date', 'emirates_id', 'about', 'services', 'image', 'address_house', 'address_street', 'address_city', 'address_country', 'address_postcode');
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
            $model = $model->select('id', 'first_name', 'middle_name', 'last_name', 'mobile_prefix', 'mobile', 'emergency_contact_no_prefix', 'emergency_contact_no', 'email', 'password', 'birth_date', 'emirates_id', 'about', 'services', 'image', 'address_house', 'address_street', 'address_city', 'address_country', 'address_postcode');
            return parent::success($model->first());
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }

}
