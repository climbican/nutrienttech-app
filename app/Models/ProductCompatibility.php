<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCompatibility extends Model
{
    protected $table = 'product_compatibility';
    protected $fillable = [
        'product_id',
        'compatibility_id'];
    public $timestamps = false;
    protected $dateFormat = 'U';
}
