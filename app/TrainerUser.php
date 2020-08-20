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
    protected $fillable = ['first_name', 'middle_name', 'last_name', 'mobile_prefix', 'mobile', 'emergency_contact_no_prefix', 'emergency_contact_no', 'email', 'password', 'birth_date', 'emirates_id', 'about', 'services', 'image', 'address_house', 'address_street', 'address_city', 'address_country', 'address_postcode', 'expirence'];
    protected $appends = array('full_name', 'booking_cnt', 'booking_reviewed_cnt', 'rating_avg', 'is_booked_by_me');

    public function getIsBookedByMeAttribute() {
        return (((\App\Booking::where('model_type', 'trainer_users')->where('model_id', $this->id)->count()) > 0) ? true : false);
    }

    public function getRatingAvgAttribute() {
        $classschedule = \App\ClassSchedule::where('trainer_id', $this->id)->get()->pluck('id');
        $model = \App\Booking::where('model_type', 'class_schedules')->whereIn('model_id', $classschedule->toArray());
        $model = $model->whereNotNull('rating');
        return number_format((float) $model->avg('rating'), 1, '.', '');
//        return $model->avg('rating');
    }

    public function getBookingReviewedCntAttribute() {
        $classschedule = \App\ClassSchedule::where('trainer_id', $this->id)->get()->pluck('id');
        $model = \App\Booking::where('model_type', 'class_schedules')->whereIn('model_id', $classschedule->toArray());
        $model = $model->whereNotNull('rating');
        return $model->count();
    }

    public function getBookingCntAttribute() {
        $classschedule = \App\ClassSchedule::where('trainer_id', $this->id)->get()->pluck('id');
        $model = \App\Booking::where('model_type', 'class_schedules')->whereIn('model_id', $classschedule->toArray());
//        $model = $model->whereNotNull('rating');
        return $model->count();
    }

    public function getFullNameAttribute() {
        $name = '';
        $name .= $this->first_name;
        $this->middle_name == '' ? '' : $name .= ' ' . $this->middle_name;
        $name .= ' ' . $this->last_name;

        return $name;
    }

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
