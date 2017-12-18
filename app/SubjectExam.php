<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectExam extends Model
{

    public function examslist() {
    	return $this->belongsToMany('\App\Exam', 'subject_exams', 'subject_id', 'exam_id');
    }

    public function exams(){
    	return $this->belongsTo('\App\Exam', 'exam_id');
    }
}
