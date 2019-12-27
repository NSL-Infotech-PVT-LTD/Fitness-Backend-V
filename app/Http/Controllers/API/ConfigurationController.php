<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;
use App\Configuration as MyModel;
class ConfigurationController extends ApiController
{
   public function getConfigurationColumn(Request $request, $column) {
        $user = User::findOrFail(\Auth::id());
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'GET', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {

            if (!in_array($column, ['terms_and_conditions','private_policy']))
                return parent::error('Please use valid column');
//dd($column);
            $key = '';
//            if ($column == 'terms_and_conditions'):
                if ($user->hasRole('Customer') === true)
                    $key = '_customer';
                if ($user->hasRole('Service-provider') === true)
                    $key = '_service_provider';
                
//            endif;
            $model = new MyModel();
            $model = $model->first();
            $var = $column . $key;
//            dd($var);   
            return parent::success($model->$var, 200, 'data');
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
}
