<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrisonCategory extends Model
{
    use HasFactory;
   

    protected $fillable = ['name'];

    public function prisonUnits(): HasMany {
        return $this->hasMany(PrisonUnit::class);
    }

}
