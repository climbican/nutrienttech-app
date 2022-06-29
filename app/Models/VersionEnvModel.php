<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VersionEnvModel extends Model
{
	protected $table = 'environment_version';
	protected $fillable = ['id',
		'version_num',
		'env_id',
		'active',
		'create_dte',
		'create_by',
		'last_update',
		'last_update_by'];
	public $timestamps = false;
	protected $dateFormat = 'U';
}
