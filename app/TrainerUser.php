<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TrainerUser extends Authenticatable {

    use LogsActivity,
        HasApiTokens,
        Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'trainer_users';
 public static $_imagePublicPath = 'uploads/emirateimages';
    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'middle_name', 'last_name', 'mobile_prefix', 'mobile', 'emergency_contact_no_prefix', 'emergency_contact_no', 'email', 'password', 'birth_date', 'emirates_id', 'about', 'services', 'image', 'address_house', 'address_street', 'address_city', 'address_country', 'address_postcode', 'expirence', 'certifications', 'specialities','type','emirate_image1','emirate_image2'];
    protected $appends = array('full_name', 'booking_cnt', 'booking_reviewed_cnt', 'rating_avg', 'is_booked_by_me', 'is_booked_by_me_booking_id', 'date_duration');

    public function getIsBookedByMeAttribute() {
        return (((\App\Booking::where('model_type', 'trainer_users')->where('model_id', $this->id)->where('created_by', \Auth::id())->count()) > 0) ? true : false);
    }

    public function getIsBookedByMeBookingIdAttribute() {
        $booking = \App\Booking::where('model_type', 'trainer_users')->where('model_id', $this->id)->where('created_by', \Auth::id());
        return ((($booking->count()) > 0) ? $booking->first()->id : 0);
    }

    public function getRatingAvgAttribute() {
        $classschedule = \App\ClassSchedule::where('trainer_id', $this->id)->get()->pluck('id');
        $model = \App\Booking::where('model_type', 'class_schedules')->whereIn('model_id', $classschedule->toArray());
        $model = $model->whereNotNull('rating');
        return number_format((float) $model->avg('rating'), 1, '.', '');
//        return $model->avg('rating');
    }
    
    public function getDateDurationAttribute() {
        $classschedule = \App\ClassSchedule::where('trainer_id', $this->id)->first(['id', 'start_date', 'end_date', 'trainer_id', 'duration']);
        return $classschedule;
//        return $model->avg('rating');
    }
     public function getEmirateImage1Attribute($value) {
	try {
	    if ($value === null || $value == '')
		return $value;
	    return env('APP_URL') . '/' . self::$_imagePublicPath . '/' . $value;
	} catch (\Exception $ex) {
	    return $value;
	}
    } 
     public function getEmirateImage2Attribute($value) {
	try {
	    if ($value === null || $value == '')
		return $value;
	    return env('APP_URL') . '/' . self::$_imagePublicPath . '/' . $value;
	} catch (\Exception $ex) {
	    return $value;
	}
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

        return ucwords($name);
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
