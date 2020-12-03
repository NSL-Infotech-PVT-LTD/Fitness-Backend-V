<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainerUserDevice extends Model {

    use LogsActivity;
//    use SoftDeletes;
}
