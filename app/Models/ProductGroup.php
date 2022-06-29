<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
	protected $table = 'product_group';
	protected $fillable = ['id',
		'name',
		'color_header',
		'color_pri',
		'color_sec',
		'active',
		'product_list_visible',
		'data_grid_visible',
		'create_dte',
		'last_update',
		'create_by_id',
		'last_update_by'];
	public $timestamps = false;
	protected $dateFormat = 'U';
}
