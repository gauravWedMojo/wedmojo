<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreateFunction extends Model
{
	protected $table = 'function';
	protected $fillable = ['function_name','function_image','function_date','created_by_user_id',];
}
