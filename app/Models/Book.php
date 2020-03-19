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

    public function objectToArray($book, $file){
        $arr = array();
        if(isset($book->title))
            $arr['title'] = $book->title;
        if(isset($book->description))
            $arr['description'] = $book->description;
        if(isset($book->image))
            $arr['image'] = $book->image;
        elseif($file)
            $arr['image'] = $file;
        if(isset($book->authors))
            $arr['authors'] = $book->authors;
        return $arr;
    }

}