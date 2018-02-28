<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\User;

class Wedding extends Model
{
    protected $table = 'wedding_detail';
    protected $fillable = ['bride_id'];

    public function getGroomImageAttribute($value){
    		return url('/public/Images/WeddingImages').'/'.$value;
    }

    public function getBrideImageAttribute($value){
    		return url('/public/Images/WeddingImages').'/'.$value;
    }


}
