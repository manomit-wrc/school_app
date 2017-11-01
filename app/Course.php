<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function category_details () {
    	return $this->belongsTo('\App\Category', 'category_id');
    }
    public function tags() {
    	return $this->belongsToMany('\App\Tag', 'course_tags', 'course_id', 'tag_id');
    }
}
