<?php

namespace App;

use Illuminate\Foundation\Auth\Student as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    //
    use Notifiable;
	protected $table = 'students';

	protected $fillable = [
		'first_name','last_name','username','email','password','address','city','pincode','image','status'
	];

	public function exams(){
    	return $this->belongsTo('\App\Exam', 'exam_id');
    }
}
