<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Booking extends Model {

    use LogsActivity;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bookings';

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
    protected $fillable = ['model_type', 'model_id', 'payment_status', 'payment_params', 'created_by','review','rating'];

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

    public static function boot() {
        parent::boot();


//        static::updating(function($model) {
//            $model->updated_by = isset(auth()->user()->id) ? auth()->user()->id : $model->updated_by;
//        });

        static::creating(function($model) {
            $model->created_by = \Auth::id() == '' ? null : \Auth::id();
        });

//        static::deleting(function($model) {
//            
//        });
    }

}
