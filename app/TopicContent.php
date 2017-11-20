<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopicContent extends Model
{
    public function content_upload_details () {
    	return $this->hasMany('App\TopicAllFile','topic_content_id');
    }

    public function content_dropbox_details () {
    	return $this->hasMany('App\TopicDropboxFile','topic_content_id');
    }

    public function content_embed_details () {
    	return $this->hasMany('App\TopicEmbedFile','topic_content_id');
    }
}
