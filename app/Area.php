<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
    	'exam_id','subject_id','code','name','description','status'
    ];

    public function subjects() {
    	return $this->belongsTo('\App\Subject', 'subject_id');
    }
}
