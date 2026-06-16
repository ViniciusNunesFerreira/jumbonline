<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'float',
        'status' => PaymentStatus::class,
    ];
    
    protected $fillable = [
        'order_id',
        'payment_method_id',
        'cash_session_id',
        'reference',
        'amount',
        'currency',
        'status',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
