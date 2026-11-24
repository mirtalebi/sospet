<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'slug',
        'seo_title',
        'seo_description',
        'seo_canonical',
        'seo_noindex',
        'og_image',
        'pet_type',
        'breed',
        'gender',
        'age',
        'city',
        'area',
        'latitude', // جدید
        'longitude', // جدید
        'phone',
        'description',
        'is_resolved',
        'is_hidden',
    ];

    protected function casts(): array
    {
        return [
            'is_resolved' => 'boolean',
            'is_hidden' => 'boolean',
            'seo_noindex' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Pet $pet): void {
            if (blank($pet->slug)) {
                $base = Str::slug((string) $pet->title) ?: 'pet';

                $pet->forceFill(['slug' => $base.'-'.$pet->id])->saveQuietly();
            }
        });

        static::saved(fn () => static::flushSeoCache());
        static::deleted(fn () => static::flushSeoCache());
    }

    public static function flushSeoCache(): void
    {
        Cache::forget('sospet.seo.city-map');
        Cache::forever('sospet.sitemap.version', (int) Cache::get('sospet.sitemap.version', 1) + 1);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images(): HasMany
    {
        return $this->hasMany(PetImage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);

    }

    public function getTypeText()
    {
        return $this->type == 'adoption' ? 'فرزندخواهی' :
            ($this->type == 'found' ? 'پیدا شده' : 'گمشده');
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'adoption' => 'آماده واگذاری',
            'found' => 'پیدا شده',
            default => 'گمشده',
        };
    }

    public function resolvedLabel(): string
    {
        return $this->type === 'adoption' ? 'واگذاری انجام شد' : 'به خانه برگشت';
    }

    public function hubRouteName(): string
    {
        return match ($this->type) {
            'adoption' => 'adoption.index',
            'found' => 'found-pets.index',
            default => 'lost-pets.index',
        };
    }

    public function primaryImageUrl(): ?string
    {
        $image = $this->relationLoaded('images')
            ? $this->images->first()
            : $this->images()->first();

        return $image?->image_url;
    }

    public function imageAlt(): string
    {
        $subject = trim("{$this->pet_type} {$this->breed}");
        $place = filled($this->city) ? " در {$this->city}" : '';

        return trim($subject.$place) ?: (string) $this->title;
    }

    public function isSeoIncomplete(): bool
    {
        $hasImages = $this->relationLoaded('images')
            ? $this->images->isNotEmpty()
            : $this->images()->exists();

        return blank($this->title) || blank($this->city) || ! $hasImages;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_resolved', false)->where('is_hidden', false);
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_hidden', false);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeInCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }

    public function scopeWithinRadiusKm(Builder $query, float $latitude, float $longitude, float $radiusKm): Builder
    {
        $distanceSql = '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))';

        return $query
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("pets.*, {$distanceSql} as distance_km", [$latitude, $longitude, $latitude])
            ->having('distance_km', '<=', $radiusKm);
    }
}
