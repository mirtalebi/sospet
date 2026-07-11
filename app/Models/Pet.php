<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'is_resolved'
    ];

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PetImage::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);

    }

    public function getTypeText() {
        return $this->type == 'adoption' ? 'فرزندخواهی' : 
            ($this->type == 'found' ? 'پیدا شده' : 'گمشده');
    }
}
