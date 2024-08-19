<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug;
    use InteractsWithMedia;

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->preventOverwrite()
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function collections(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Collection::class);
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
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


    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => is_null($attributes['published_at']) ? 'Indisponível' : ($attributes['published_at'] > now() ? 'Agendado' : 'Publicado'),
        );
    }

    protected function seoTitle(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ?? \Str::of($this->title)->limit(66),
        );
    }

    protected function seoDescription(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ?? \Str::of(strip_tags($this->description))->limit(316),
        );
    }
}
