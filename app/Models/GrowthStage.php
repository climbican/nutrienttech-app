<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrowthStage extends Model
{
	protected $table = 'growth_stage';
	protected $fillable = ['id',
		'crop_id',
		'growth_stage_id',
		'sample_unit_id',
		'create_dte',
		'create_by'];
	public $timestamps = false;
	protected $dateFormat = 'U';
}
