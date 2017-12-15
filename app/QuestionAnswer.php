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

    public function exam () {
    	return $this->belongsTo('\App\Exam', 'exam_id');
    }

    public function area () {
    	return $this->belongsTo('\App\Area', 'area_id');
    }

    public function section () {
    	return $this->belongsTo('\App\Section', 'section_id');
    }
}
