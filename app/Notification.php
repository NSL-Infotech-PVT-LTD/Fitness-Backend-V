<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\User;

class Notification extends Model {

    use LogsActivity;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

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
    protected $fillable = ['title', 'body', 'message', 'target_id', 'is_read','created_by'];

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    protected $appends = ['booking_detail'];
//    public function getDataAttribute($value) {
//
//        return $value = Null ? [] : json_decode($value);
//    }
//
//    public function getDescriptionForEvent($eventName) {
//        return _CLASS_ . " model has been {$eventName}";
//    }
    
    public function customerDetail() {
        return $this->hasOne(User::class, 'id', 'created_by')->select('id', 'name', 'image');
    }
    
    public function getBookingDetailAttribute() {
        $models = json_decode($this->message, true);
        return $models;
    }
}
