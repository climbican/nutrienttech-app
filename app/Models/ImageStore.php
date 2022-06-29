<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageStore extends Model
{
	protected $table = 'image_store';
	protected $fillable = ['id',
		'link_table',
		'linked_table_id',
		'image_name',
		'active',
		'approved_by',
		'create_dte',
		'create_by',
		'last_update',
		'last_update_by'];
	public $timestamps = false;
	protected $dateFormat = 'U';
}
