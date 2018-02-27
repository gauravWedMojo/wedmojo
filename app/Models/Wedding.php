<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\User;

class Wedding extends Model
{
    protected $table = 'wedding_detail';
    protected $fillable = ['bride_id'];

    public function getGroomIdAttribute($value){
    		return User::where(['id' => $value])->first();
    }
}
