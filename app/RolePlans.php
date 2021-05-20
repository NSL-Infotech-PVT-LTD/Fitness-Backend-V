<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RolePlans extends Model {

    use LogsActivity;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_plans';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'fee_type', 'fee', 'params', 'status', 'image'];

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent($eventName) {
        return __CLASS__ . " model has been {$eventName}";
    }

    protected $appends = array('role_plan');

    public function getRolePlanAttribute() {
        // return ucfirst($this->fee_type) . ': ';
        return $this->fee_type . ': AED ' . $this->fee;
    }

//    public function getFeeTypeAttribute($value) {
//        return ucfirst(str_replace('_', ' ', $value));
//    }

}
