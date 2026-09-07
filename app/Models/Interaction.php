<?php

namespace App\Models;

use App\Enums\InteractionChannel;
use App\Enums\InteractionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'employee_id',
        'channel',
        'type',
        'description',
        'meta',
    ];

    protected $casts = [
        'channel' => InteractionChannel::class,
        'type' => InteractionType::class,
        'meta' => 'json',
    ];

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}