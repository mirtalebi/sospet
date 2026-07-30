<?php

use App\Models\Pet;
use App\Models\PetImage;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $currentStep = 1;

    public $type = 'lost';
    public $type_selected = false;
    public $pet_type = 'سگ';
    public $title = '';
    public $breed = '';
    public $gender = 'نر';
    public $age = '';
    public $city = 'تهران';
    public $latitude = null;
    public $longitude = null;
    public $area = '';
    public $description = '';
    public $phone = '';
    public $photos = [];

    public function mount()
    {
        if (request()->query('type')) {
            $this->type = request()->query('type', 'lost');
            $this->type_selected = true;
        }
        $this->phone = auth()->user()->mobile ?? '';
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate(
                [
                    'title' => 'required|string|min:5|max:100',
                    'type' => 'required|in:lost,found,adoption',
                    'phone' => 'required|regex:/^09[0-9]{9}$/',
                ],
                [
                    'title.required' => 'عنوان گزارش الزامی است.',
                    'title.min' => 'عنوان باید حداقل ۵ کاراکتر باشد.',
                    'phone.required' => 'شماره تماس الزامی است.',
                    'phone.regex' => 'فرمت شماره موبایل معتبر نیست.',
                ],
            );
        } elseif ($this->currentStep === 2) {
            $this->validate(
                [
                    'latitude' => 'required|numeric',
                    'longitude' => 'required|numeric',
                ],
                [
                    'latitude.required' => 'لطفاً موقعیت خود را روی نقشه انتخاب کنید.',
                    'longitude.required' => 'لطفاً موقعیت خود را روی نقشه انتخاب کنید.',
                ],
            );
        }

        $this->currentStep++;
    }

    public function prevStep()
    {
        $this->currentStep--;
    }

    public function submitReport()
    {
        $this->validate(
            [
                'description' => 'nullable|string|max:1000',
                'photos' => 'required|array|min:1|max:4',
                'photos.*' => 'image|max:3072',
            ],
            [
                'photos.required' => 'آپلود حداقل یک تصویر از حیوان الزامی است.',
                'photos.max' => 'حداکثر می‌توانید ۴ تصویر آپلود کنید.',
                'photos.*.image' => 'فایل‌های انتخابی باید از نوع تصویر باشند.',
            ],
        );

        $pet = Pet::create([
            'user_id' => auth()->id(),
            'type' => $this->type,
            'pet_type' => $this->pet_type,
            'title' => $this->title,
            'breed' => $this->breed,
            'gender' => $this->gender,
            'age' => $this->age,
            'city' => $this->city,
            'area' => $this->area,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'description' => $this->description,
            'phone' => $this->phone,
        ]);

        foreach ($this->photos as $photo) {
            $path = $photo->store('pets', 'public');
            PetImage::create([
                'pet_id' => $pet->id,
                'image_path' => $path,
            ]);
        }

        session()->flash('success', 'گزارش شما با موفقیت ثبت و منتشر شد! 🐾');
        return $this->redirect('/', navigate: true);
    }
};
?>

<main class="flex-1 overflow-y-auto no-scrollbar pb-28 bg-bg-secondary">

    <!-- Sticky Centered Top Progress Bar Header -->
    <div class="border-b border-border-custom bg-bg-main sticky top-0 z-30 shadow-sm">
        <div class="max-w-2xl mx-auto px-5 py-3 flex items-center justify-between">
            <a href="/" wire:navigate
                class="w-9 h-9 rounded-full bg-bg-secondary border border-border-custom flex items-center justify-center text-text-title active:scale-95 transition shrink-0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </a>
            @if ($type_selected)
                <h1 class="font-bold text-text-title text-sm md:text-base grow px-3">ثبت گزارش
                    {{ $type === 'lost' ? 'گم‌شدن' : ($type === 'found' ? 'پیدا شدن' : 'واگذاری') }} حیوان</h1>
            @else
                <h1 class="font-bold text-text-title text-sm md:text-base grow px-3">ثبت گزارش جدید</h1>
            @endif
            <span
                class="text-[11px] font-bold text-primary bg-primary/10 px-3 py-1 rounded-full border border-primary/20 shrink-0">
                مرحله {{ $currentStep }} از ۳
            </span>
        </div>

        <div class="w-full h-1 bg-border-custom flex">
            <div class="h-full bg-primary transition-all duration-300" style="width: {{ ($currentStep / 3) * 100 }}%">
            </div>
        </div>
    </div>

    <!-- Centered Boxed Form Area container wrapper -->
    <div class="max-w-2xl mx-auto px-5 pt-6">
        <div class="bg-bg-main rounded-2xl border border-border-custom p-4 md:p-6 shadow-sm">

            <!-- WIZARD STEP 1 -->
            @if ($currentStep === 1)
                <div class="space-y-5">
                    @if (!$type_selected)
                        <div>
                            <label class="block text-xs font-bold text-text-title mb-2">نوع گزارش چیه؟</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" wire:click="$set('type', 'lost')"
                                    class="py-3 rounded-xl border text-xs font-bold transition {{ $type === 'lost' ? 'bg-primary text-white border-transparent shadow-sm' : 'bg-bg-main text-text-body border-border-custom hover:bg-bg-secondary' }}">حیوانی
                                    گم کردم</button>
                                <button type="button" wire:click="$set('type', 'found')"
                                    class="py-3 rounded-xl border text-xs font-bold transition {{ $type === 'found' ? 'bg-primary text-white border-transparent shadow-sm' : 'bg-bg-main text-text-body border-border-custom hover:bg-bg-secondary' }}">حیوانی
                                    پیدا کردم</button>
                                <button type="button" wire:click="$set('type', 'adoption')"
                                    class="py-3 rounded-xl border text-xs font-bold transition {{ $type === 'adoption' ? 'bg-primary text-white border-transparent shadow-sm' : 'bg-bg-main text-text-body border-border-custom hover:bg-bg-secondary' }}">درخواست
                                    واگذاری</button>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-text-title mb-2">چه حیوونیه؟</label>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach (['سگ', 'گربه', 'پرنده', 'سایر'] as $t)
                                <button type="button" wire:click="$set('pet_type', '{{ $t }}')"
                                    class="py-2.5 rounded-xl border text-xs font-bold transition {{ $pet_type === $t ? 'bg-primary text-white border-transparent shadow-sm' : 'bg-bg-main text-text-body border-border-custom hover:bg-bg-secondary' }}">{{ $t }}</button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Split Fields on Desktop Screen -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-text-title mb-1.5">عنوان کوتاه برای آگهی</label>
                            <input type="text" wire:model="title"
                                placeholder="مثال: گربه مهربان سفید در محدوده نیاوران"
                                class="w-full bg-bg-main border border-border-custom rounded-xl px-4 py-3 text-xs text-text-title outline-none focus:border-primary transition">
                            @error('title')
                                <span
                                    class="text-[11px] text-danger-custom font-medium mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-text-title mb-1.5">شماره تماس جهت هماهنگی
                                کاربران</label>
                            <input type="text" wire:model="phone" dir="ltr"
                                class="w-full bg-bg-main border border-border-custom rounded-xl px-4 py-3 text-xs text-text-title text-center outline-none focus:border-primary transition">
                            <span class="text-[10px] text-text-muted mt-1 block text-right">کاربران برای کمک به حیوان با
                                این شماره تماس می‌گیرند.</span>
                            @error('phone')
                                <span
                                    class="text-[11px] text-danger-custom font-medium mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if ($type == 'adoption')
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-text-title mb-1.5">نژاد (اختیاری)</label>
                                <input type="text" wire:model="breed" placeholder="مثال: پرشین، میکس"
                                    class="w-full bg-bg-main border border-border-custom rounded-xl px-4 py-3 text-xs text-text-title outline-none focus:border-primary transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-text-title mb-1.5">حدود سن (اختیاری)</label>
                                <input type="text" wire:model="age" placeholder="مثال: ۲ ساله، ۶ ماهه"
                                    class="w-full bg-bg-main border border-border-custom rounded-xl px-4 py-3 text-xs text-text-title outline-none focus:border-primary transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-text-title mb-2">جنسیت حیوان</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" wire:click="$set('gender', 'نر')"
                                    class="py-2.5 rounded-xl border text-xs font-bold transition {{ $gender === 'نر' ? 'bg-primary/10 text-primary border-primary/30' : 'bg-bg-main text-text-body border-border-custom' }}">نر
                                    ♂</button>
                                <button type="button" wire:click="$set('gender', 'ماده')"
                                    class="py-2.5 rounded-xl border text-xs font-bold transition {{ $gender === 'ماده' ? 'bg-primary/10 text-primary border-primary/30' : 'bg-bg-main text-text-body border-border-custom' }}">ماده
                                    ♀</button>
                                <button type="button" wire:click="$set('gender', 'نامشخص')"
                                    class="py-2.5 rounded-xl border text-xs font-bold transition {{ $gender === 'نامشخص' ? 'bg-primary/10 text-primary border-primary/30' : 'bg-bg-main text-text-body border-border-custom' }}">نامشخص</button>
                            </div>
                        </div>
                    @endif

                    <div class="pt-2">
                        <button type="button" wire:click="nextStep"
                            class="w-full bg-primary text-white font-bold py-3.5 rounded-xl shadow-lg shadow-primary/20 active:scale-[0.99] hover:opacity-95 transition">
                            مرحله بعد: موقعیت و تماس
                        </button>
                    </div>
                </div>
            @endif

            <!-- WIZARD STEP 2 -->
            @if ($currentStep === 2)
                <div class="space-y-5" data-report-section>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-3">
                            <label class="block text-xs font-bold text-text-title">موقعیت را روی نقشه انتخاب کن</label>
                            <span
                                class="text-[10px] font-bold text-primary bg-primary/10 px-2.5 py-1 rounded-full border border-primary/20 shrink-0">انتخاب
                                نقطه الزامی است</span>
                        </div>

                        <button type="button" data-report-current-location
                            class="w-full rounded-xl border border-primary/20 bg-primary/10 px-4 py-3 text-xs font-bold text-primary transition active:scale-[0.99] hover:bg-primary/15">
                            اجازه بده موقعیت فعلی‌ات را پیدا کنم
                        </button>

                        <p class="text-[10px] text-text-muted leading-5" data-report-status>
                            برای نمایش موقعیت فعلی، اجازه دسترسی مرورگر را تایید کن.
                        </p>

                        <!-- Layout Map Box -->
                        <div class="rounded-2xl border border-border-custom bg-bg-main overflow-hidden shadow-sm">
                            <div class="h-64 md:h-80 w-full" wire:ignore data-report-map data-initial-latitude="35.6892"
                                data-initial-longitude="51.3890"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-[11px] text-text-body">
                            <div class="rounded-xl border border-border-custom bg-bg-secondary px-3 py-2.5">
                                <span class="block text-text-muted mb-1 text-[10px]">شهر انتخاب‌شده</span>
                                <span class="font-bold text-text-title" data-report-city>{{ $city }}</span>
                            </div>
                            <div class="rounded-xl border border-border-custom bg-bg-secondary px-3 py-2.5">
                                <span class="block text-text-muted mb-1 text-[10px]">مختصات انتخاب‌شده</span>
                                <span class="font-bold text-text-title" data-report-coordinates>انتخاب نشده</span>
                            </div>
                        </div>

                        <input type="hidden" wire:model="latitude" data-report-latitude>
                        <input type="hidden" wire:model="longitude" data-report-longitude>
                        <input type="hidden" wire:model="city" data-report-city-input>

                        @error('latitude')
                            <span
                                class="text-[11px] text-danger-custom font-medium block text-right">{{ $message }}</span>
                        @enderror
                        @error('longitude')
                            <span
                                class="text-[11px] text-danger-custom font-medium block text-right">{{ $message }}</span>
                        @enderror
                        @error('city')
                            <span
                                class="text-[11px] text-danger-custom font-medium block text-right">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-2.5 pt-2">
                        <button type="button" wire:click="prevStep"
                            class="bg-bg-main border border-border-custom text-text-title font-bold py-3.5 rounded-xl hover:bg-bg-secondary transition">قبلی</button>
                        <button type="button" wire:click="nextStep"
                            class="col-span-2 bg-primary text-white font-bold py-3.5 rounded-xl shadow-lg shadow-primary/20 active:scale-[0.99] hover:opacity-95 transition">مرحله
                            بعد: عکس و توضیحات</button>
                    </div>
                </div>
            @endif

            <!-- WIZARD STEP 3 -->
            @if ($currentStep === 3)
                <form wire:submit.prevent="submitReport" enctype="multipart/form-data" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-text-title mb-2">تصاویر حیوان (حداقل ۱ و حداکثر ۴
                            عکس)</label>
                        <div class="grid grid-cols-4 gap-2.5">

                            @foreach ($photos as $p)
                                <div
                                    class="relative h-16 sm:h-20 bg-bg-main rounded-xl overflow-hidden border border-border-custom shadow-sm">
                                    <img src="{{ $p->temporaryUrl() }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach

                            @if (count($photos) < 4)
                                <label
                                    class="h-16 sm:h-20 bg-bg-main border-2 border-dashed border-border-custom rounded-xl flex flex-col items-center justify-center cursor-pointer text-text-body hover:text-primary hover:border-primary/60 transition shadow-sm">
                                    <input type="file" wire:model="photos" multiple class="hidden"
                                        accept="image/*">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.5">
                                        <line x1="12" y1="5" x2="12" y2="19" />
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                    </svg>
                                    <span class="text-[9px] font-bold mt-1">افزودن</span>
                                </label>
                            @endif
                        </div>
                        @error('photos')
                            <span
                                class="text-[11px] text-danger-custom font-medium mt-1 block text-right">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-title mb-1.5">توضیحات تکمیلی یا شرایط
                            خاص</label>
                        <textarea wire:model="description" rows="5"
                            placeholder="مثال: قلاده قرمز دارد، بسیار ترسو است، نیاز به جراحی فوری پا دارد..."
                            class="w-full bg-bg-main border border-border-custom rounded-xl px-4 py-3 text-xs text-text-title outline-none leading-6 focus:border-primary transition"></textarea>
                        @error('description')
                            <span class="text-[11px] text-danger-custom font-medium mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-2.5 pt-2">
                        <button type="button" wire:click="prevStep"
                            class="bg-bg-main border border-border-custom text-text-title font-bold py-3.5 rounded-xl hover:bg-bg-secondary transition">قبلی</button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="col-span-2 bg-primary text-white font-bold py-3.5 rounded-xl shadow-lg shadow-primary/20 active:scale-[0.99] hover:opacity-95 transition flex items-center justify-center gap-1.5">
                            <span wire:loading.remove>ثبت و انتشار گزارش آگهی</span>
                            <span wire:loading class="flex items-center gap-1"><span
                                    class="w-3 h-3 rounded-full border-2 border-white border-t-transparent animate-spin"></span>در
                                حال ثبت...</span>
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>
</main>
