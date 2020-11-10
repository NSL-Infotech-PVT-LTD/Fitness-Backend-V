<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ClassSchedule extends Model {

    use LogsActivity;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'class_schedules';

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
    protected $fillable = ['class_type', 'start_date', 'end_date', 'repeat_on', 'start_time', 'duration', 'class_id', 'trainer_id', 'cp_spots', 'capacity', 'location_id','gender_type'];
    protected $appends = array('is_booked_by_me', 'available_capacity','is_booked_by_me_booking_id');

    public function getIsBookedByMeAttribute() {
        return (((\App\Booking::where('model_type', 'class_schedules')->where('model_id', $this->id)->where('created_by', \Auth::id())->count()) > 0) ? true : false);
    }

    public function getIsBookedByMeBookingIdAttribute() {
        $booking = \App\Booking::where('model_type', 'class_schedules')->where('model_id', $this->id)->where('created_by', \Auth::id());
        return ((($booking->count()) > 0) ? $booking->first()->id : 0);
    }

    public function getAvailableCapacityAttribute() {
        $bookedTickets = \App\Booking::where('model_type', 'class_schedules')->where('model_id', $this->id)->get()->count();
        return $this->capacity - $bookedTickets - $this->cp_spots;
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

    public function getRepeatOnAttribute($value) {
        return ($value == null) ? null : json_decode($value);
    }

    public function classDetail() {
        return $this->hasOne(Classes::class, 'id', 'class_id')->select('id', 'name', 'image', 'description');
    }

    public function trainer() {
        return $this->hasOne(TrainerUser::class, 'id', 'trainer_id')->select('id', 'first_name', 'middle_name', 'last_name', 'image','email');
    }

    public function locationDetail() {
        return $this->hasOne(Location::class, 'id', 'location_id')->select('id', 'name', 'image', 'location');
    }

}
