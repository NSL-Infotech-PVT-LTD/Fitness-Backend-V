<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Configuration as MyModel;

class ConfigurationController extends ApiController {

    public function getConfigurationByColumn(Request $request, $column) {
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'GET', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {

            if (!in_array($column, ['terms_and_conditions', 'privacy_policy', 'about_us']))
                return parent::error('Please use valid column');
//dd($column);
            $model = MyModel::first();
            $var = $column;
//            dd($var);   
            return parent::success(['config' => $model->$var]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
