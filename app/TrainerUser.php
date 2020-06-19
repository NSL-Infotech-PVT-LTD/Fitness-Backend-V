<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TrainerUser extends Model {

    use LogsActivity;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'trainer_users';

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
    protected $fillable = ['first_name', 'middle_name', 'last_name', 'mobile_prefix', 'mobile', 'emergency_contact_no_prefix', 'emergency_contact_no', 'email', 'password', 'birth_date', 'emirates_id', 'about', 'services', 'image', 'address_house', 'address_street', 'address_city', 'address_country', 'address_postcode'];

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

    public function getServicesAttribute($value) {
        return ($value == null) ? null : json_decode($value);
    }

}
