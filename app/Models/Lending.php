<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lending extends Model
{
	use SoftDeletes;

    protected $fillable = ['user_id', 'date_start', 'date_end', 'date_finish'];
    protected $dates = ['deleted_at'];

    public function books(){
    	return $this->belongsToMany('App\Models\Book','books_lendings')->as("lent");
    }
    public function user(){
    	return $this->belongsTo('App\User');
    }

}