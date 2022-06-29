<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    protected $table = 'crop';
    protected $fillable = ['id',
       'name',
        'sub_type',
        'image_url',
        'create_dte',
        'last_update',
        'added_by',
        'last_update_by'];
    public $timestamps = false;
    protected $dateFormat = 'U';
}
