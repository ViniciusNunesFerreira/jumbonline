<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_session_id',
        'employee_id',
        'type',
        'amount',
        'description',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function session()
    {
        return $this->belongsTo(CashSession::class, 'cash_session_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}