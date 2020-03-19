<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EnrollTournaments;
class Image extends Model
{
    protected $fillable = ['tournament_id','enrollment_id', 'customer_id','images'];
    
    
    
    public function images() {
        return $this->hasMany(EnrollTournaments::class, 'id', 'enrollment_id')->select('images');
      
    }

}
