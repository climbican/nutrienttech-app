<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compatibility extends Model
{
    protected $table = 'compatibility';
    protected $fillable = ['id',
        'symbol',
        'name_short',
        'comp_text',
        'added_by',
        'create_dte',
        'last_update',
        'last_update_by'];
    public $timestamps = false;
    protected $dateFormat = 'U';
}
