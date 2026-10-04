<?php

use App\Models\Pet;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts::admin'), Title('داشبورد')] class extends Component {
    public function with(): array
    {
        return [
            'stats' => [
                'کاربران' => User::count(),
                'مدیران' => User::where('role', 'admin')->count(),
                'گزارش‌ها' => Pet::count(),
                'حل‌شده' => Pet::where('is_resolved', true)->count(),
                'پنهان‌شده' => Pet::where('is_hidden', true)->count(),
            ],
        ];
    }
}; ?>

<div>
    <h1 class="font-display text-3xl text-text-title">داشبورد مدیریت</h1>
    <p class="mt-1 text-sm text-text-muted">خلاصه‌ای از وضعیت کاربران و گزارش‌های ساس‌پت</p>

    <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-3">
        @foreach ($stats as $label => $value)
            <div class="rounded-2xl border border-border-custom bg-bg-main p-5 shadow-sm">
                <div class="text-xs font-semibold text-text-muted">{{ $label }}</div>
                <div class="mt-2 text-3xl font-extrabold text-text-title">{{ number_format($value) }}</div>
                <div class="mt-3 h-1 w-10 rounded-full bg-primary"></div>
            </div>
        @endforeach
    </div>
</div>
