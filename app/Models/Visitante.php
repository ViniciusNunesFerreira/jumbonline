<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Visitante extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

       
    protected $fillable = [
        'nome', 'logradouro', 'numero', 'bairro', 'cidade', 'uf', 'cep', 'customer_id', 'prison_unit_id'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->useFallbackUrl('/img/placeholder.png')
            ->useFallbackPath(public_path('img/placeholder.png'))
            ->singleFile();

        $this->addMediaCollection('gallery')
            ->useFallbackPath(public_path('img/placeholder.png'))
            ->useFallbackUrl('/img/placeholder.png');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->performOnCollections('gallery')->width(100)->height(100);
        $this->addMediaConversion('thumb_small')->performOnCollections('gallery')->width(50)->height(50);
        $this->addMediaConversion('thumb_large')->performOnCollections('gallery')->width(200)->height(200);
        $this->addMediaConversion('responsive')->performOnCollections('gallery')->withResponsiveImages();
    }


    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function prison_unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PrisonUnit::class);
    }
}
