<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sufficiency extends Model
{
	protected $table = 'sufficiency';
	protected $fillable = ['id',
		'crop_id',
		'growth_stage_id',
		'sample_unit_id',
		'n_percent',
		'no3_ppm',
		'p_percent',
		'po4_ppm',
		'k_percent',
		'ca_percent',
		'mg_percent',
		's_percent',
		'b_ppm',
		'cu_ppm',
		'fe_ppm',
		'mn_ppm',
		'zn_ppm',
		'na_percent',
		'cl_percent',
		'create_dte',
		'added_by',
		'last_update',
		'last_update_by'];
	public $timestamps = false;
	protected $dateFormat = 'U';
}
