<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
	use SoftDeletes;

    protected $fillable = ['title', 'description', 'image' ];
    protected $dates = ['deleted_at'];

    public function authors(){
    	return $this->belongsToMany('App\Models\Author','authors_books')->as("authorship");
    }
    public function lendings(){
    	return $this->belongsToMany('App\Models\Lending','books_lendings')->as("lent");
    }

}