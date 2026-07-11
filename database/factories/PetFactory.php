<?php

namespace Database\Factories;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        // آرایه‌ای از اطلاعات واقعی به زبان فارسی برای زیباتر شدن خروجی
        $types = ['lost', 'found', 'adoption'];
        
        $petDetails = [
            'سگ' => ['نژاد' => ['ژرمن شپرد', 'هاسکی', 'شیتزو', 'بومی حمایتی'], 'سن' => '۲ ساله', 'عنوان' => 'امدادرسانی به سگ نژاد'],
            'گربه' => ['نژاد' => ['DSH بومی', 'پرشین', 'اسکاتیش', 'سیامی'], 'سن' => '۶ ماهه', 'عنوان' => 'گربه دوست‌داشتنی'],
            'پرنده' => ['نژاد' => ['عروس هلندی', 'مرغ عشق', 'کاسکو'], 'سن' => 'یک ساله', 'عنوان' => 'پرنده خانگی']
        ];

        $petType = $this->faker->randomElement(array_keys($petDetails));
        $breed = $this->faker->randomElement($petDetails[$petType]['نژاد']);
        $type = $this->faker->randomElement($types);

        // تعیین عنوان بر اساس نوع گزارش
        $title = match($type) {
            'lost' => "{$petType} گم‌شده در منطقه",
            'found' => "یک قلاده {$petType} پیدا شده",
            'adoption' => "واگذاری {$petType} نژاد {$breed}",
        };

        $cities = ['تهران', 'کرج', 'شیراز', 'اصفهان', 'مشهد'];
        $areas = ['نیاوران', 'گیشا', 'پونک', 'معالی‌آباد', 'هاشمیه'];

        return [
            'user_id' => null, // فعلا کاربر را خالی می‌گذاریم
            'type' => $type,
            'title' => $title,
            'pet_type' => $petType,
            'breed' => $breed,
            'gender' => $this->faker->randomElement(['نر', 'ماده']),
            'age' => $petDetails[$petType]['سن'],
            'city' => $this->faker->randomElement($cities),
            'area' => $this->faker->randomElement($areas),
            'latitude' => $this->faker->latitude(35.6, 35.8), // پیش‌فرض محدود در تهران
            'longitude' => $this->faker->longitude(51.2, 51.5),
            'phone' => '0912' . $this->faker->numerify('#######'), // تولید ۷ رقم رندم بعد از ۰۹۱۲            'description' => 'این حیوان نیاز به امدادرسانی فوری یا خانواده‌ای دلسوز دارد. لطفا در صورت داشتن هرگونه اطلاعات تماس بگیرید.',
            'is_resolved' => false,
        ];
    }
}