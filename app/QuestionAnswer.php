<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    // public function exams(){
    // 	return $this->hasMany('\App\Exam', 'exam_id');
    // }

    public function subject () {
    	return $this->belongsTo('\App\Subject', 'subject_id');
    }
}
