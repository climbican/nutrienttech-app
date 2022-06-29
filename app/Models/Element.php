<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    protected $table = 'element';
    protected $fillable = ['id',
        'symbol',
        'element_name',
        'chemical_name',
        'element_desc',
        'deficiency',
        'benefits',
	    'show_flag',
        'create_dte',
        'last_update'];
    public $timestamps = false;
    protected $dateFormat = 'U';
}
