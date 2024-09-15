<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'identifier', 'credentials', 'is_enabled', 'is_default'];
    
    protected $casts = [
        'is_enabled' => 'boolean',
        'is_default' => 'boolean',
        'credentials' => 'array',
    ];
}
