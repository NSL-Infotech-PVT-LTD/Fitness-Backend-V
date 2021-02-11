<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Event extends Model {

    use LogsActivity;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'events';

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
    protected $fillable = ['name', 'image', 'description', 'status', 'start_date', 'end_date', 'special', 'location_id','capacity'];
    protected $appends = array('is_booked_by_me','is_booked_by_me_booking_id','available_capacity');

    public function getIsBookedByMeAttribute() {
        return (((\App\Booking::where('model_type', 'events')->where('model_id', $this->id)->where('created_by', \Auth::id())->count()) > 0) ? true : false);
    }

    public function getIsBookedByMeBookingIdAttribute() {
        $booking = \App\Booking::where('model_type', 'events')->where('model_id', $this->id)->where('created_by', \Auth::id());
        return ((($booking->count()) > 0) ? $booking->first()->id : 0);
    }

    public function getAvailableCapacityAttribute() {
        $bookedTickets = \App\Booking::where('model_type', 'events')->where('model_id', $this->id)->get()->count();
        return $this->capacity - $bookedTickets;
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

    public function locationDetail() {
        return $this->hasOne(Location::class, 'id', 'location_id')->select('id', 'name', 'image', 'location');
    }

}
