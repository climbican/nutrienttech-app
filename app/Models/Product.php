<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $fillable = ['id',
        'product_group_id',
        'product_name',
        'product_subname',
        'product_code',
        'benefits',
        'compatibility',
        'dilution',
        'warning',
        'caution',
        'net_contents',
        'image_url',
	    'active',
	    'show_flag',
        'added_by',
        'last_update_by',
        'create_dte',
        'last_update'];
    public $timestamps = false;
    protected $dateFormat = 'U';
}
