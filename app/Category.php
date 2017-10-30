<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
    	'p_id', 'name', 'description', 'status'
    ];

    public function parent()
	{
	    return $this->belongsTo(self::class, 'p_id');
	}

	public function children()
	{
	    return $this->hasMany(self::class, 'p_id');
	}

	public static function tree() {

	return static::with(implode('.', array_fill(0, 100, 'parent')))->orderBy('id')->get();

	}

	public static function generate_dropdown($array, $name)
	{
		if(count($array['parent']) === 0) {
			return $name;
		}
		else {
			$temp_name = $array['parent']['name'] . "->".$name;
			$name = $temp_name;
			return \App\Category::generate_dropdown($array['parent'], $name);
		}
	}
}
