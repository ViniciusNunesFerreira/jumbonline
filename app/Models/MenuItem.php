<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class MenuItem extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;

    protected $attributes = [
        'order' => 0,
    ];

    protected $fillable = [
        'menu_id',
        'parent_id',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
