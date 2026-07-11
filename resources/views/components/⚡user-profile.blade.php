<?php

use App\Models\Pet;
use Livewire\Component;

new class extends Component {
    // تغییر وضعیت آگهی
    public function toggleResolve($petId)
    {
        $pet = Pet::where('user_id', auth()->id())->findOrFail($petId);
        $pet->update([
            'is_resolved' => !$pet->is_resolved,
        ]);

        session()->flash('message', 'وضعیت آگهی با موفقیت به‌روزرسانی شد.');
    }

    // حذف آگهی
    public function deletePet($petId)
    {
        $pet = Pet::where('user_id', auth()->id())->findOrFail($petId);

        $pet->images()->delete();
        $pet->delete();

        session()->flash('message', 'آگهی با موفقیت حذف شد.');
    }

    // لایووایر به صورت خودکار مقادیر برگشتی این متد را به قالب تزریق می‌کند
    public function with(): array
    {
        return [
            'myPets' => Pet::with('images')
                ->where('user_id', auth()->id())
                ->latest()
                ->get(),
        ];
    }
};
?>

<main class="flex-1 overflow-y-auto no-scrollbar pb-28 px-5 pt-4 bg-sand">

    <div class="bg-white rounded-3xl p-5 border border-ink/5 shadow-sm mb-6 flex items-center gap-4">
        <div
            class="w-14 h-14 rounded-full bg-saffron-100 text-saffron-600 font-bold flex items-center justify-center text-lg shadow-inner">
            {{ mb_substr(auth()->user()->first_name ?? 'ک', 0, 1) }}
        </div>
        <div class="text-right">
            <h1 class="font-display text-xl text-ink">{{ auth()->user()->name }}</h1>
            <p class="text-xs text-ink-soft mt-1" dir="ltr">{{ auth()->user()->mobile }}</p>
        </div>
    </div>

    <div class="mb-4">
        <h2 class="font-display text-lg text-ink mb-1">گزارش‌های ثبت‌شده‌ی شما</h2>
        <p class="text-[11px] text-ink-soft">آگهی‌های خود را مدیریت، ویرایش یا منقضی کنید.</p>
    </div>

    @if (session()->has('message'))
        <div
            class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold p-3.5 rounded-xl mb-4 text-right">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col gap-3">
        @forelse($myPets as $pet)
            <div class="bg-white rounded-2xl p-3 border border-ink/5 shadow-sm flex gap-3 relative overflow-hidden">

                <div class="w-20 h-20 bg-sand-100 rounded-xl overflow-hidden shrink-0">
                    @if ($pet->images->count() > 0)
                        <img src="{{ asset('storage/' . $pet->images->first()->image_path) }}"
                            class="w-full h-full object-cover">
                    @else
                        <img src="https://placedog.net/150/150" class="w-full h-full object-cover">
                    @endif
                </div>

                <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5 text-right">
                    <div>
                        <h3
                            class="font-bold text-sm text-ink truncate {{ $pet->is_resolved ? 'line-through opacity-50' : '' }}">
                            {{ $pet->title }}
                        </h3>
                        <p class="text-[11px] text-ink-soft mt-1">
                            {{ $pet->pet_type }} · {{ $pet->area }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 mt-2">
                        <button wire:click="toggleResolve({{ $pet->id }})"
                            class="text-[11px] font-bold px-2.5 py-1.5 rounded-lg border transition {{ $pet->is_resolved ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-sand-100 text-ink-soft border-transparent hover:border-ink/10' }}">
                            {{ $pet->is_resolved ? '✓ پیدا/حل شده' : 'علامت به عنوان حل‌شده' }}
                        </button>

                        <button
                            onclick="confirm('آیا از حذف این گزارش مطمئن هستید؟') || event.stopImmediatePropagation()"
                            wire:click="deletePet({{ $pet->id }})"
                            class="text-[11px] font-semibold text-coral-dark bg-coral-light/50 px-2.5 py-1.5 rounded-lg border border-transparent hover:border-coral/20 transition">
                            حذف آگهی
                        </button>
                    </div>
                </div>

                <span
                    class="absolute top-3 end-3 text-[9px] font-bold text-white px-2 py-0.5 rounded-full {{ $pet->type === 'lost' ? 'bg-coral' : ($pet->type === 'foster' ? 'bg-pistachio-dark' : 'bg-saffron-500') }}">
                    {{ $pet->type === 'lost' ? 'گم‌شده' : ($pet->type === 'found' ? 'پیدا شده' : 'فرزندخواهی') }}
                </span>
            </div>
        @empty
            <div class="text-center py-12 bg-white/50 rounded-2xl border-2 border-dashed border-ink/10">
                <span class="text-3xl">🐾</span>
                <p class="text-xs text-ink-soft font-medium mt-2">هنوز هیچ گزارش یا آگهی ثبت نکرده‌اید.</p>
            </div>
        @endforelse
    </div>
</main>
