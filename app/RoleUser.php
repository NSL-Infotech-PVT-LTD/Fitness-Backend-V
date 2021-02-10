<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RoleUser extends Model {

    use LogsActivity;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_user';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'role_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'role_plan_id', 'user_id', 'created_at'];

}
