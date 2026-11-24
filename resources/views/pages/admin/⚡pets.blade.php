<?php

use App\Models\Pet;
use App\Support\Seo\SeoManager;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Layout('layouts::admin'), Title('گزارش‌ها')] class extends Component {
    use WithFileUploads;
    use WithPagination;

    public string $search = '';

    public string $type = '';

    public string $status = '';

    public bool $seoModalOpen = false;

    public ?int $seoPetId = null;

    public string $seoTitle = '';

    public string $seoDescription = '';

    public string $seoCanonical = '';

    public bool $seoNoindex = false;

    public $seoOgImage = null;

    public ?string $seoOgImageUrl = null;

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

    public function openSeoEditor(int $petId): void
    {
        $pet = Pet::findOrFail($petId);

        $this->seoPetId = $pet->id;
        $this->seoTitle = (string) $pet->seo_title;
        $this->seoDescription = (string) $pet->seo_description;
        $this->seoCanonical = (string) $pet->seo_canonical;
        $this->seoNoindex = (bool) $pet->seo_noindex;
        $this->seoOgImage = null;
        $this->seoOgImageUrl = $pet->og_image ? app(SeoManager::class)->mediaUrl($pet->og_image) : null;
        $this->seoModalOpen = true;

        $this->resetErrorBag();
    }

    public function closeSeoEditor(): void
    {
        $this->seoModalOpen = false;
        $this->seoPetId = null;
        $this->seoOgImage = null;
        $this->seoOgImageUrl = null;
        $this->seoTitle = '';
        $this->seoDescription = '';
        $this->seoCanonical = '';
        $this->seoNoindex = false;

        $this->resetErrorBag();
    }

    public function saveSeo(): void
    {
        $validated = $this->validate([
            'seoTitle' => ['nullable', 'string', 'max:70'],
            'seoDescription' => ['nullable', 'string', 'max:160'],
            'seoCanonical' => ['nullable', 'url', 'max:255'],
            'seoNoindex' => ['boolean'],
            'seoOgImage' => ['nullable', 'image', 'max:4096'],
        ]);

        $pet = Pet::findOrFail($this->seoPetId);

        $data = [
            'seo_title' => filled($validated['seoTitle'] ?? null) ? trim($validated['seoTitle']) : null,
            'seo_description' => filled($validated['seoDescription'] ?? null) ? trim($validated['seoDescription']) : null,
            'seo_canonical' => filled($validated['seoCanonical'] ?? null) ? trim($validated['seoCanonical']) : null,
            'seo_noindex' => $validated['seoNoindex'],
        ];

        if ($this->seoOgImage) {
            if (filled($pet->og_image) && ! str_starts_with((string) $pet->og_image, 'http')) {
                Storage::disk('public')->delete($pet->og_image);
            }

            $data['og_image'] = $this->seoOgImage->store('seo', 'public');
        }

        $pet->update($data);

        $this->closeSeoEditor();

        session()->flash('seo_status', 'تنظیمات سئوی آگهی ذخیره شد.');
    }

    public function removeSeoOgImage(): void
    {
        $pet = Pet::findOrFail($this->seoPetId);

        if (filled($pet->og_image) && ! str_starts_with((string) $pet->og_image, 'http')) {
            Storage::disk('public')->delete($pet->og_image);
        }

        $pet->update(['og_image' => null]);

        $this->seoOgImage = null;
        $this->seoOgImageUrl = null;
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

        $seoState = null;

        if ($this->seoModalOpen && $this->seoPetId !== null) {
            $pet = Pet::with('images')->find($this->seoPetId);

            if ($pet) {
                $generated = app(SeoManager::class)->forPet($pet);

                $exclusionReason = match (true) {
                    $pet->is_resolved => 'آگهی حل‌شده',
                    $pet->is_hidden => 'آگهی پنهان',
                    $pet->seo_noindex => 'noindex فعال است',
                    blank($pet->city) => 'شهر ثبت نشده',
                    $pet->images->isEmpty() => 'تصویر ندارد',
                    blank($pet->slug) => 'اسلاگ ندارد',
                    default => null,
                };

                $seoState = [
                    'petTitle' => $pet->title,
                    'url' => route('pet-details', $pet),
                    'canonical' => route('pet-details', $pet),
                    'title' => filled($this->seoTitle) ? $this->seoTitle : $generated->title,
                    'description' => filled($this->seoDescription) ? $this->seoDescription : (string) $generated->description,
                    'generatedTitle' => $generated->title,
                    'generatedDescription' => (string) $generated->description,
                    'inSitemap' => $exclusionReason === null,
                    'reason' => $exclusionReason,
                    'ogImage' => $this->seoOgImage ? $this->seoOgImage->temporaryUrl() : $this->seoOgImageUrl,
                ];
            }
        }

        return ['pets' => $pets, 'seoState' => $seoState];
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

    @if (session('seo_status'))
        <div class="mb-4 rounded-2xl border border-success-custom/30 bg-success-custom/10 px-4 py-3 text-sm font-semibold text-success-custom">
            {{ session('seo_status') }}
        </div>
    @endif

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
                            <a href="{{ route('pet-details', $pet) }}" target="_blank"
                                class="hover:text-primary">{{ $pet->title }}</a>
                        </td>
                        <td class="p-3"><span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $pet->type === 'found' ? 'bg-success-custom/10 text-success-custom' : ($pet->type === 'lost' ? 'bg-danger-custom/10 text-danger-custom' : 'bg-primary/10 text-primary') }}">{{ $pet->getTypeText() }}</span></td>
                        <td class="p-3">{{ $pet->city }}</td>
                        <td class="p-3">{{ $pet->user?->name }}</td>
                        <td class="p-3">
                            {{ $pet->is_resolved ? 'حل‌شده' : 'فعال' }}{{ $pet->is_hidden ? ' · پنهان' : '' }}
                        </td>
                        <td class="text-end [&>button]:mb-1 [&>button]:ms-1">
                            <button wire:click="openSeoEditor({{ $pet->id }})"
                                class="rounded-lg border border-border-custom px-3 py-1.5 text-xs font-bold text-text-title transition hover:border-primary/40 hover:text-primary">
                                سئو
                            </button>
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

    @if ($seoModalOpen && $seoState)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-text-title/50 p-4 backdrop-blur-sm" role="dialog"
            aria-modal="true" aria-label="تنظیمات سئوی آگهی">
            <div class="mx-auto my-6 w-full max-w-2xl rounded-2xl border border-border-custom bg-bg-main shadow-2xl">
                <div class="flex items-start justify-between gap-3 border-b border-border-custom px-5 py-4 md:px-6">
                    <div class="min-w-0">
                        <h2 class="font-bold text-lg text-text-title">تنظیمات سئوی آگهی</h2>
                        <p class="mt-0.5 truncate text-xs text-text-muted">{{ $seoState['petTitle'] }}</p>
                    </div>
                    <button type="button" wire:click="closeSeoEditor" aria-label="بستن"
                        class="grid h-9 w-9 shrink-0 place-items-center rounded-full border border-border-custom bg-bg-secondary text-text-body transition hover:text-danger-custom">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.4" stroke-linecap="round" aria-hidden="true">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="saveSeo" class="space-y-5 px-5 py-5 md:px-6">
                    <div class="flex flex-wrap items-center gap-2 text-[11px]">
                        @if ($seoState['inSitemap'])
                            <span class="rounded-full bg-success-custom/10 px-2.5 py-1 font-bold text-success-custom">
                                در سایت‌مپ قرار دارد
                            </span>
                        @else
                            <span class="rounded-full bg-danger-custom/10 px-2.5 py-1 font-bold text-danger-custom">
                                خارج از سایت‌مپ — {{ $seoState['reason'] }}
                            </span>
                        @endif
                        <a href="{{ $seoState['url'] }}" target="_blank" rel="noopener" dir="ltr"
                            class="truncate text-primary hover:underline">{{ $seoState['url'] }}</a>
                    </div>

                    <!-- Google preview -->
                    <div>
                        <p class="mb-2 text-xs font-bold text-text-title">پیش‌نمایش گوگل</p>
                        <div class="rounded-xl border border-border-custom bg-white p-4">
                            <p class="text-[10.5px] text-[#202124]" dir="ltr">{{ parse_url($seoState['canonical'], PHP_URL_HOST) }}</p>
                            <p class="mt-1 line-clamp-2 text-sm font-medium text-[#1a0dab]" dir="rtl">{{ $seoState['title'] }}</p>
                            <p class="mt-1 line-clamp-2 text-[11.5px] leading-5 text-[#4d5156]" dir="rtl">{{ $seoState['description'] }}</p>
                        </div>
                        <p class="mt-1.5 text-[10.5px] text-text-muted">
                            متن خاکستری، مقدار خودکار فعلی است؛ هر فیلدی را که خالی بگذارید، همان مقدار خودکار استفاده می‌شود.
                        </p>
                    </div>

                    <div>
                        <div class="mb-1.5 flex items-center justify-between gap-2">
                            <label class="text-xs font-bold text-text-title" for="pet-seo-title">عنوان سئو</label>
                            <span class="text-[10.5px] font-semibold {{ mb_strlen($seoTitle) > 60 ? 'text-danger-custom' : 'text-text-muted' }}">
                                {{ mb_strlen($seoTitle) }}/60
                            </span>
                        </div>
                        <input id="pet-seo-title" type="text" wire:model.live.debounce.400ms="seoTitle"
                            placeholder="{{ $seoState['generatedTitle'] }}"
                            class="w-full rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                        @error('seoTitle')
                            <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="mb-1.5 flex items-center justify-between gap-2">
                            <label class="text-xs font-bold text-text-title" for="pet-seo-description">توضیحات سئو</label>
                            <span class="text-[10.5px] font-semibold {{ mb_strlen($seoDescription) > 160 ? 'text-danger-custom' : 'text-text-muted' }}">
                                {{ mb_strlen($seoDescription) }}/160
                            </span>
                        </div>
                        <textarea id="pet-seo-description" rows="3" wire:model.live.debounce.400ms="seoDescription"
                            placeholder="{{ $seoState['generatedDescription'] }}"
                            class="w-full rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-sm leading-6 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
                        @error('seoDescription')
                            <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-text-title" for="pet-seo-canonical">آدرس Canonical</label>
                        <input id="pet-seo-canonical" type="url" wire:model="seoCanonical" dir="ltr"
                            placeholder="{{ $seoState['canonical'] }}"
                            class="w-full rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                        <p class="mt-1 text-[10.5px] text-text-muted">فقط در صورت نیاز به آدرس جایگزین پر کنید.</p>
                        @error('seoCanonical')
                            <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <p class="mb-1.5 text-xs font-bold text-text-title">تصویر اشتراک‌گذاری (OG)</p>
                        <div class="overflow-hidden rounded-xl border border-border-custom bg-bg-secondary">
                            @if ($seoState['ogImage'])
                                <img src="{{ $seoState['ogImage'] }}" alt="تصویر اشتراک‌گذاری آگهی"
                                    class="aspect-[1200/630] w-full object-cover">
                            @else
                                <div class="grid aspect-[1200/630] place-items-center text-[11px] text-text-muted">
                                    تصویر اختصاصی تنظیم نشده است؛ از تصویر اصلی آگهی استفاده می‌شود.
                                </div>
                            @endif
                        </div>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <label
                                class="cursor-pointer rounded-xl border border-border-custom bg-bg-secondary px-4 py-2.5 text-xs font-bold text-text-title transition hover:border-primary/30 hover:text-primary">
                                انتخاب تصویر
                                <input type="file" wire:model="seoOgImage" accept="image/*" class="hidden">
                            </label>

                            @if ($seoOgImageUrl)
                                <button type="button" wire:click="removeSeoOgImage" wire:confirm="تصویر اختصاصی حذف شود؟"
                                    class="rounded-xl border border-danger-custom/30 px-4 py-2.5 text-xs font-bold text-danger-custom transition hover:bg-danger-custom hover:text-white">
                                    حذف تصویر
                                </button>
                            @endif

                            <span wire:loading wire:target="seoOgImage" class="text-[11px] text-text-muted">در حال بارگذاری…</span>
                        </div>
                        @error('seoOgImage')
                            <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-start gap-2 rounded-xl border border-border-custom bg-bg-secondary/50 px-4 py-3">
                        <input type="checkbox" wire:model="seoNoindex"
                            class="mt-0.5 h-4 w-4 rounded border-border-custom text-primary focus:ring-primary/30">
                        <span>
                            <span class="block text-xs font-bold text-text-title">خارج کردن از نتایج گوگل (noindex)</span>
                            <span class="mt-0.5 block text-[10.5px] leading-5 text-text-muted">
                                صفحه برای کاربران باز می‌ماند اما از نتایج جست‌وجو و سایت‌مپ حذف می‌شود.
                            </span>
                        </span>
                    </label>

                    <div class="flex flex-wrap items-center justify-end gap-2 border-t border-border-custom pt-4">
                        <button type="button" wire:click="closeSeoEditor"
                            class="rounded-xl border border-border-custom px-4 py-2.5 text-xs font-bold text-text-body transition hover:bg-bg-secondary">
                            انصراف
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="rounded-xl bg-primary px-5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-primary-hover disabled:opacity-60">
                            ذخیره تنظیمات سئو
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
