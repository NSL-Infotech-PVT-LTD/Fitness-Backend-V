<?php

namespace App;

use App\Tournament;
use App\User;
use App\Image;
use Illuminate\Database\Eloquent\Model;

class EnrollTournaments extends Model {

    protected $fillable = ['type', 'size','price','token','status','tournament_id', 'customer_id'];

     protected $appends = array('images','tournament_name','tournament_image');
     public function getTournamentNameAttribute($value) 
     {
         $name = Tournament::where('id', $this->tournament_id)->value('name');
        return $name;
     }
     
      public function getTournamentImageAttribute($value) 
     {
         $image = Tournament::where('id', $this->tournament_id)->value('image');
        return $image;
        
        
     }
     
    public function userdetails() {
        return $this->hasOne(User::class, 'id', 'customer_id')->select('id', 'name','image');
    }
    
     public function allImages(){
         return $this->hasMany(Image::class,'enrollment_id',$this->id);
     }

    public function tournament() {
        return $this->hasOne(Tournament::class, 'id', 'tournament_id')->select('id', 'name', 'image', 'price', 'description');
    }

    public function getImagesAttribute($value) {
        $LatestImage = Image::where('enrollment_id', $this->id)->orderBy('id', 'desc')->take(3)->get();
//        dd($LatestImage);
        $FinalImage = [];
        foreach ($LatestImage as $img) {
            $FinalImage[] = $img->images;
        }
        return $FinalImage;
    }

}
