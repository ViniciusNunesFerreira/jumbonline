<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Customer extends Authenticatable implements HasMedia, MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'phone_country',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone' => E164PhoneNumberCast::class . ':phone_country',
        'email_verified_at' => 'datetime',
        'banned_at' => 'datetime',
        'last_order_at' => 'datetime',
        'must_change_password' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useFallbackUrl('/img/avatar.svg')
            ->useFallbackPath(public_path('/img/avatar.svg'))
            ->singleFile();
    }

    public function addresses(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function defaultAddress(): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->addresses->where('is_default', true)->first();
    }

    public function cart(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(OrderItem::class, Order::class);
    }

    public function detentos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return  $this->hasMany(Detento::class);
    }

    public function visitantes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return  $this->hasMany(Visitante::class);
    }

    public function interactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Interaction::class);
    }

    /**
     * Selo de "Cliente frequente" — usado na lista e no cadastro. Não faz
     * consulta nenhuma: paid_orders_count já é mantido por CustomerMetricsService
     * (via OrderObserver/PaymentObserver/RefundObserver) a cada pedido/pagamento/
     * reembolso. Mesmo padrão de Variant::getIsLowStockAttribute() (limite
     * configurável em Settings, com app(...) resolvido sob demanda).
     */
    public function getIsFrequentAttribute(): bool
    {
        $minOrders = app(\App\Settings\CustomerSetting::class)->frequent_customer_min_orders;

        return $this->paid_orders_count >= $minOrders;
    }

    /**
     * Data do primeiro pedido PAGO do cliente ("cliente desde", no resumo de
     * relacionamento) — não é denormalizado como last_order_at, então esta
     * consulta roda sob demanda. Método explícito (não um accessor mágico)
     * de propósito: evita uso acidental dentro de uma listagem, onde viraria
     * uma consulta por linha.
     */
    public function firstPaidOrderAt(): ?\Illuminate\Support\Carbon
    {
        $date = $this->orders()
            ->whereHas('payments', fn ($query) => $query->where('status', \App\Enums\PaymentStatus::PAID->name))
            ->oldest('created_at')
            ->value('created_at');

        return $date ? \Illuminate\Support\Carbon::parse($date) : null;
    }
}