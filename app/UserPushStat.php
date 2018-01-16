<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPushStat extends Model
{
    public function stats() {
    	return $this->belongsTo('\App\Section', 'section_id');
    }
}
