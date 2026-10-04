<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
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
