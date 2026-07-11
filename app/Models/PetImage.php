<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetImage extends Model
{
    protected $fillable = ['pet_id', 'image_path'];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
