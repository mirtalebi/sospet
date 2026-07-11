<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\PetImage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'first_name' => 'عباس',
            'last_name' => 'میرطالبی',
            'mobile' => '09131234567',
        ]);

        Pet::factory()->count(20)->create([
            'user_id' => $user->id // شناسه کاربر ساخته شده را اینجا پاس می‌دهیم
        ])->each(function ($pet) {
            $randomImageId = rand(1, 100);
            $imageUrl = "pet-images/pet-{$randomImageId}.png";
            PetImage::create([
                'pet_id' => $pet->id,
                'image_path' => $imageUrl
            ]);
            $randomImageId = rand(1, 100);
            $imageUrl = "pet-images/pet-{$randomImageId}.png";
            PetImage::create([
                'pet_id' => $pet->id,
                'image_path' => $imageUrl
            ]);
            
        });
    }
}
