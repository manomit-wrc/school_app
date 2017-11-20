<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    public function subject_details(){
    	return $this->belongsTo('\App\Subject', 'subject_id');
    }

    public function tags() {
    	return $this->belongsToMany('\App\Tag', 'topic_tags', 'topic_id', 'tag_id');
    }

    public function topic_files () {
    	return $this->hasMany('\App\TopicAllFile', 'topic_id');
    }

    public function topic_content () {
    	return $this->hasMany('App\TopicContent', 'topic_id');
    }
}
