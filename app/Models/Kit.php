<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\KitProductStatus;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Kit extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug;
    use InteractsWithMedia;

    protected $attributes = [ 'price' => 0];

    protected $fillable = [
        'title', 'status', 'product_list', 'price'  
    ];

    protected $casts = [
        'price' => 'float',
        'product_list' => 'array',
        'status' => KitProductStatus::class,
        'is_active' => 'boolean',        
    ];


    protected static function boot()
    {
        parent::boot();

        static::created(function (Kit $product) {
            $variant = new Variant();
            $variant->kit_id = $product->id;
            $variant->price = $product->price;
            $variant->save();
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->usingLanguage('pt-BR');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

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

    /**
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->performOnCollections('gallery')->width(100)->height(100);
        $this->addMediaConversion('thumb_small')->performOnCollections('gallery')->width(50)->height(50);
        $this->addMediaConversion('thumb_large')->performOnCollections('gallery')->width(200)->height(200);
        $this->addMediaConversion('responsive')->performOnCollections('gallery')->withResponsiveImages();
    }

    public function scopeActive($query)
    {
        return $query->where('status', KitProductStatus::ACTIVE->name);
    }

   
    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['status'] === KitProductStatus::ACTIVE->name,
        );
    }

    protected function price(): Attribute
    {
        $thousand_separator = config('money.' . config('app.currency') . '.thousands_separator');

        return Attribute::make(
            set: fn($value) => str_replace($thousand_separator, '', $value)
        );
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function variants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function first_variant(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Variant::class)->oldestOfMany();
    }

    public function last_variant(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Variant::class)->latestOfMany();
     }

    public function variantAttributes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VariantAttribute::class);
    }


    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

}
