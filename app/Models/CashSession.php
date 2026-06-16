<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'device_name',
        'opening_balance',
        'closing_balance',
        'calculated_balance',
        'difference',
        'status',
        'opened_at',
        'closed_at',
        'notes',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_balance' => 'float',
        'closing_balance' => 'float',
        'calculated_balance' => 'float',
        'difference' => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function movements()
    {
        return $this->hasMany(CashMovement::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}