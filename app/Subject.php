<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public function course_details(){
    	return $this->belongsTo('\App\Course', 'course_id');
    }

    public function tags() {
    	return $this->belongsToMany('\App\Tag', 'subject_tags', 'subject_id', 'tag_id');
    }
}
