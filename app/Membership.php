<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model {

    use LogsActivity;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'memberships';

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
    protected $fillable = ['membership_details_id', 'fee_type', 'fee', 'params'];

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

    public function detail() {
        return $this->hasOne(MembershipDetail::class, 'id', 'membership_details_id')->select('id', 'user_type', 'category', 'name', 'description', 'image');
    }

    protected $appends = array('fees');

    public function getFeesAttribute() {
        try {
            $data = [];
            $model = Membership::where('membership_details_id', $this->membership_details_id)->get();
//            dd($model->toArray());
            if ($model->isEmpty() !== true):
                foreach ($model as $mo):
                    $data[$mo->fee_type.'_fee'] = $mo->fee;
                endforeach;
            endif;
            return $data;
        } catch (\Exception $ex) {
            return [];
        }
    }

}
