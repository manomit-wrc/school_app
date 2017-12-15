<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public function exams(){
    	return $this->belongsTo('\App\Exam', 'exam_id');
    }

    public function tags() {
    	return $this->belongsToMany('\App\Tag', 'subject_tags', 'subject_id', 'tag_id');
    }

    public function topics() {
    	return $this->hasMany('\App\Topic', 'subject_id');
    }

    public function examIds() {
    	return $this->belongsToMany('\App\Exam', 'subject_exams', 'subject_id', 'exam_id');
    }

    public function subjectIds () {
    	return $this->hasMany('\App\SubjectExam', 'subject_id');
    }
}
