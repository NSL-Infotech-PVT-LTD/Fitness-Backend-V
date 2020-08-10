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
    protected $fillable = ['class_type', 'start_date', 'end_date', 'repeat_on', 'start_time', 'duration', 'class_id', 'trainer_id', 'cp_spots', 'capacity', 'location_id'];

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

    public function locationDetail() {
        return $this->hasOne(Location::class, 'id', 'location_id')->select('id', 'name', 'image', 'location');
    }

}
