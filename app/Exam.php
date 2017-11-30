<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
    	'code','name','description','start_date','end_date','status'
    ];

    public function tags() {
    	return $this->belongsToMany('\App\Tag', 'exam_tags', 'exam_id', 'tag_id');
    }
}
