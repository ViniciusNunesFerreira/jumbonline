<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'os_value', 'is_enabled' 
     ];

    protected $casts = [
        'os_value' => 'float',
        'is_enabled' => 'boolean',
    ];
}
