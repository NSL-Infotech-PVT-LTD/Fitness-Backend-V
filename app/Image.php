<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['tournament_id','enrollment_id', 'customer_id','images'];

}
