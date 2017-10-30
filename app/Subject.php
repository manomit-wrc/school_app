<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public function course_details(){
    	return $this->belongsTo('\App\Course', 'course_id');
    }
}
