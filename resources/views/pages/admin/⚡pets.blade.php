<?php

use App\Models\Pet;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::admin'), Title('گزارش‌ها')] class extends Component {
    use WithPagination;

    public string $search = '';

    public string $type = '';

    public string $status = '';

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'type', 'status'], true)) {
            $this->resetPage();
        }
    }

    public function toggleResolved(int $petId): void
    {
        $pet = Pet::findOrFail($petId);
        $pet->update(['is_resolved' => !$pet->is_resolved]);
    }

    public function toggleHidden(int $petId): void
    {
        $pet = Pet::findOrFail($petId);
        $pet->update(['is_hidden' => !$pet->is_hidden]);
    }

    public function deletePet(int $petId): void
    {
        $pet = Pet::with('images')->findOrFail($petId);

        foreach ($pet->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $pet->delete();
    }

    public function with(): array
    {
        $pets = Pet::query()
            ->with('user')
            ->when($this->search, fn($q) => $q->where(fn($q) => $q->where('title', 'like', '%' . $this->search . '%')->orWhere('city', 'like', '%' . $this->search . '%')))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->status === 'resolved', fn($q) => $q->where('is_resolved', true))
            ->when($this->status === 'active', fn($q) => $q->where('is_resolved', false))
            ->when($this->status === 'hidden', fn($q) => $q->where('is_hidden', true))
            ->latest('id')
            ->paginate(15);

        return ['pets' => $pets];
    }
}; ?>

<div>
    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <h1 class="font-display text-3xl text-text-title">گزارش‌ها</h1>
        <div class="flex flex-wrap gap-2 text-sm">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="جستجوی عنوان یا شهر"
                class="rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
            <select wire:model.live="type" class="rounded-xl border border-border-custom bg-bg-main px-3 py-2.5 outline-none focus:border-primary">
                <option value="">همه انواع</option>
                <option value="lost">گمشده</option>
                <option value="found">پیدا شده</option>
                <option value="adoption">فرزندخواهی</option>
            </select>
            <select wire:model.live="status" class="rounded-xl border border-border-custom bg-bg-main px-3 py-2.5 outline-none focus:border-primary">
                <option value="">همه وضعیت‌ها</option>
                <option value="active">فعال</option>
                <option value="resolved">حل‌شده</option>
                <option value="hidden">پنهان</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-border-custom bg-bg-main shadow-sm">
        <table class="w-full text-right text-sm">
            <thead class="bg-bg-secondary text-xs text-text-muted [&_th]:px-4 [&_th]:py-3 [&_th]:font-semibold">
                <tr>
                    <th class="p-3">عنوان</th>
                    <th class="p-3">نوع</th>
                    <th class="p-3">شهر</th>
                    <th class="p-3">ثبت‌کننده</th>
                    <th class="p-3">وضعیت</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-custom [&_td]:px-4 [&_td]:py-3">
                @forelse ($pets as $pet)
                    <tr wire:key="pet-{{ $pet->id }}" class="transition hover:bg-bg-secondary/60">
                        <td class="p-3 font-bold text-text-title">
                            <a href="{{ route('pet-details', $pet->id) }}" target="_blank"
                                class="hover:text-primary">{{ $pet->title }}</a>
                        </td>
                        <td class="p-3"><span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $pet->type === 'found' ? 'bg-success-custom/10 text-success-custom' : ($pet->type === 'lost' ? 'bg-danger-custom/10 text-danger-custom' : 'bg-primary/10 text-primary') }}">{{ $pet->getTypeText() }}</span></td>
                        <td class="p-3">{{ $pet->city }}</td>
                        <td class="p-3">{{ $pet->user?->name }}</td>
                        <td class="p-3">
                            {{ $pet->is_resolved ? 'حل‌شده' : 'فعال' }}{{ $pet->is_hidden ? ' · پنهان' : '' }}
                        </td>
                        <td class="text-end [&>button]:mb-1 [&>button]:ms-1">
                            <button wire:click="toggleResolved({{ $pet->id }})"
                                class="rounded-lg border border-border-custom px-3 py-1.5 text-xs font-bold transition hover:bg-bg-secondary">
                                {{ $pet->is_resolved ? 'بازگشایی' : 'حل‌شد' }}
                            </button>
                            <button wire:click="toggleHidden({{ $pet->id }})"
                                class="rounded-lg border border-primary/30 px-3 py-1.5 text-xs font-bold text-primary transition hover:bg-primary hover:text-white">
                                {{ $pet->is_hidden ? 'نمایش' : 'پنهان‌سازی' }}
                            </button>
                            <button wire:click="deletePet({{ $pet->id }})" wire:confirm="این گزارش حذف شود؟"
                                class="rounded-lg border border-danger-custom/30 px-3 py-1.5 text-xs font-bold text-danger-custom transition hover:bg-danger-custom hover:text-white">حذف</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-text-muted">گزارشی یافت نشد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $pets->links() }}</div>
</div>
