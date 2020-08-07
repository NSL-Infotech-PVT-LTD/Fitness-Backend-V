<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model {

    protected $table = 'configurations';

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
    protected $fillable = ['about_us', 'terms_and_conditions', 'privacy_policy', 'admin_email'];

}
