<?php

namespace App;

use App\Tournament;
use App\User;
use Illuminate\Database\Eloquent\Model;

class EnrollTournaments extends Model {

    protected $fillable = ['type', 'size', 'token', 'tournament_id', 'customer_id'];

     protected $appends = array('images');
     
    public function userdetails() {
        return $this->hasOne(User::class, 'id', 'customer_id')->select('id', 'name');
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
        return json_encode($FinalImage);
    }

}
