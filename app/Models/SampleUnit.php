<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleUnit extends Model
{
	protected $table = 'sample_unit';
	protected $fillable = ['id',
		'name_desc'];
	public $timestamps = false;
	protected $dateFormat = 'U';
}
