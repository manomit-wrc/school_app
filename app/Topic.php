<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    public function subject_details(){
    	return $this->belongsTo('\App\Subject', 'subject_id');
    }
}
