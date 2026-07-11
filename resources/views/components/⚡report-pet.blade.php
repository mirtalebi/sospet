<?php

use App\Models\Pet;
use App\Models\PetImage;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $currentStep = 1;

    // داده‌های فرم
    public $type = 'lost'; // lost, found, adoption
    public $pet_type = 'سگ'; // سگ، گربه، پرنده، غیره
    public $title = '';
    public $breed = '';
    public $gender = 'نر';
    public $age = '';
    public $city = 'تهران';
    public $area = '';
    public $description = '';
    public $phone = ''; // شماره تماس برای این آگهی
    public $photos = []; // تصاویر آپلود شده

    public function mount()
    {
        // به صورت پیش‌فرض شماره موبایل خود کاربر لاگین شده را قرار می‌دهیم تا کار راحت‌تر شود
        $this->phone = auth()->user()->mobile;
    }

    // رفتن به مرحله بعد همراه با ولیدیشن همان مرحله
    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate(
                [
                    'title' => 'required|string|min:5|max:100',
                    'type' => 'required|in:lost,found,adoption',
                ],
                [
                    'title.required' => 'عنوان گزارش الزامی است.',
                    'title.min' => 'عنوان باید حداقل ۵ کاراکتر باشد.',
                ],
            );
        } elseif ($this->currentStep === 2) {
            $this->validate(
                [
                    'city' => 'required|string',
                    'area' => 'required|string|min:2',
                    'phone' => 'required|regex:/^09[0-9]{9}$/',
                ],
                [
                    'area.required' => 'محدوده یا محله الزامی است.',
                    'phone.required' => 'شماره تماس الزامی است.',
                    'phone.regex' => 'فرمت شماره موبایل معتبر نیست.',
                ],
            );
        }

        $this->currentStep++;
    }

    // بازگشت به مرحله قبل
    public function prevStep()
    {
        $this->currentStep--;
    }

    // ثبت نهایی گزارش در دیتابیس
    public function submitReport()
    {
        $this->validate(
            [
                'description' => 'nullable|string|max:1000',
                'photos' => 'required|array|min:1|max:4',
                'photos.*' => 'image|max:3072', // حداکثر ۳ مگابایت برای هر عکس
            ],
            [
                'photos.required' => 'آپلود حداقل یک تصویر از حیوان الزامی است.',
                'photos.max' => 'حداکثر می‌توانید ۴ تصویر آپلود کنید.',
                'photos.*.image' => 'فایل‌های انتخابی باید از نوع تصویر باشند.',
            ],
        );

        // ۱. ذخیره اطلاعات اصلی حیوان
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
            'description' => $this->description,
            'phone' => $this->phone,
        ]);

        // ۲. ذخیره و آپلود تصاویر حیوان
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

<main class="flex-1 overflow-y-auto no-scrollbar pb-28 bg-sand">
    <div class="px-5 pt-2 pb-2 flex items-center justify-between border-b border-ink/5 bg-white">
        <a href="/" wire:navigate
            class="w-9 h-9 rounded-full bg-sand flex items-center justify-center text-ink active:scale-95 transition">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6" />
            </svg>
        </a>
        <h1 class="font-display text-lg text-ink grow px-2">ثبت گزارش جدید</h1>
        <span class="text-xs font-bold text-ink-soft bg-sand px-3 py-1 rounded-full">مرحله {{ $currentStep }} از
            ۳</span>
    </div>

    <div class="w-full h-1 bg-ink/5 flex">
        <div class="h-full bg-saffron-500 transition-all duration-300" style="width: {{ ($currentStep / 3) * 100 }}%">
        </div>
    </div>

    <div class="px-5 pt-5">

        @if ($currentStep === 1)
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-ink mb-2">نوع گزارش چیه؟</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" wire:click="$set('type', 'lost')"
                            class="py-3 rounded-xl border text-xs font-bold transition {{ $type === 'lost' ? 'bg-coral text-white border-transparent' : 'bg-white text-ink-soft border-ink/10' }}">گم‌شده</button>
                        <button type="button" wire:click="$set('type', 'found')"
                            class="py-3 rounded-xl border text-xs font-bold transition {{ $type === 'found' ? 'bg-pistachio-dark text-white border-transparent' : 'bg-white text-ink-soft border-ink/10' }}">پیدا
                            شده</button>
                        <button type="button" wire:click="$set('type', 'adoption')"
                            class="py-3 rounded-xl border text-xs font-bold transition {{ $type === 'adoption' ? 'bg-saffron-500 text-white border-transparent' : 'bg-white text-ink-soft border-ink/10' }}">فرزندخواهی</button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-ink mb-2">چه حیوونیه؟</label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach (['سگ', 'گربه', 'پرنده', 'سایر'] as $t)
                            <button type="button" wire:click="$set('pet_type', '{{ $t }}')"
                                class="py-2.5 rounded-xl border text-xs font-bold transition {{ $pet_type === $t ? 'bg-ink text-white border-transparent' : 'bg-white text-ink-soft border-ink/10' }}">{{ $t }}</button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-ink mb-1.5">عنوان کوتاه برای آگهی</label>
                    <input type="text" wire:model="title" placeholder="مثال: گربه مهربان سفید در محدوده نیاوران"
                        class="w-full bg-white border border-ink/10 rounded-xl px-4 py-3 text-xs text-ink outline-none focus:border-saffron-500">
                    @error('title')
                        <span class="text-[11px] text-coral-dark font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-ink mb-1.5">نژاد (اختیاری)</label>
                        <input type="text" wire:model="breed" placeholder="مثال: پرشین، میکس"
                            class="w-full bg-white border border-ink/10 rounded-xl px-4 py-3 text-xs text-ink outline-none focus:border-saffron-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-ink mb-1.5">حدود سن (اختیاری)</label>
                        <input type="text" wire:model="age" placeholder="مثال: ۲ ساله، ۶ ماهه"
                            class="w-full bg-white border border-ink/10 rounded-xl px-4 py-3 text-xs text-ink outline-none focus:border-saffron-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-ink mb-2">جنسیت حیوان</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" wire:click="$set('gender', 'نر')"
                            class="py-2.5 rounded-xl border text-xs font-bold transition {{ $gender === 'نر' ? 'bg-saffron-100 text-saffron-700 border-saffron-300' : 'bg-white text-ink-soft border-ink/10' }}">نر
                            ♂</button>
                        <button type="button" wire:click="$set('gender', 'ماده')"
                            class="py-2.5 rounded-xl border text-xs font-bold transition {{ $gender === 'ماده' ? 'bg-saffron-100 text-saffron-700 border-saffron-300' : 'bg-white text-ink-soft border-ink/10' }}">ماده
                            ♀</button>
                        <button type="button" wire:click="$set('gender', 'نامشخص')"
                            class="py-2.5 rounded-xl border text-xs font-bold transition {{ $gender === 'نامشخص' ? 'bg-saffron-100 text-saffron-700 border-saffron-300' : 'bg-white text-ink-soft border-ink/10' }}">نامشخص</button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" wire:click="nextStep"
                        class="w-full bg-saffron-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-saffron-500/10 active:scale-[0.99] transition">مرحله
                        بعد: موقعیت و تماس</button>
                </div>
            </div>
        @endif

        @if ($currentStep === 2)
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-ink mb-1.5">شهر</label>
                    <select wire:model="city"
                        class="w-full bg-white border border-ink/10 rounded-xl px-4 py-3 text-xs text-ink outline-none focus:border-saffron-500">
                        <option value="تهران">تهران</option>
                        <option value="کرج">کرج</option>
                        <option value="اصفهان">اصفهان</option>
                        <option value="مشهد">مشهد</option>
                        <option value="شیراز">شیراز</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-ink mb-1.5">کدام محدوده یا محله؟</label>
                    <input type="text" wire:model="area" placeholder="مثال: گیشا، مرزداران، بلوار ارم"
                        class="w-full bg-white border border-ink/10 rounded-xl px-4 py-3 text-xs text-ink outline-none focus:border-saffron-500">
                    @error('area')
                        <span class="text-[11px] text-coral-dark font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-ink mb-1.5">شماره تماس جهت هماهنگی کاربران</label>
                    <input type="text" wire:model="phone" dir="ltr"
                        class="w-full bg-white border border-ink/10 rounded-xl px-4 py-3 text-xs text-ink text-center outline-none focus:border-saffron-500">
                    <span class="text-[10px] text-ink-soft mt-1 block text-right">کاربران پاوِت برای کمک به حیوان با این
                        شماره تماس می‌گیرند.</span>
                    @error('phone')
                        <span class="text-[11px] text-coral-dark font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-3 gap-2.5 pt-2">
                    <button type="button" wire:click="prevStep"
                        class="bg-white border border-ink/10 text-ink-soft font-bold py-3.5 rounded-xl transition">قبلی</button>
                    <button type="button" wire:click="nextStep"
                        class="col-span-2 bg-saffron-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-saffron-500/10 active:scale-[0.99] transition">مرحله
                        بعد: عکس و توضیحات</button>
                </div>
            </div>
        @endif

        @if ($currentStep === 3)
            <form wire:submit.prevent="submitReport" enctype="multipart/form-data" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-ink mb-2">تصاویر حیوان (حداقل ۱ و حداکثر ۴ عکس)</label>
                    <div class="grid grid-cols-4 gap-2">

                        @foreach ($photos as $p)
                            <div class="relative h-16 bg-white rounded-xl overflow-hidden border border-ink/5">
                                <img src="{{ $p->temporaryUrl() }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach

                        @if (count($photos) < 4)
                            <label
                                class="h-16 bg-white border-2 border-dashed border-ink/15 rounded-xl flex flex-col items-center justify-center cursor-pointer text-ink-soft hover:text-saffron-500 hover:border-saffron-400 transition">
                                <input type="file" wire:model="photos" multiple class="hidden" accept="image/*">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                <span class="text-[9px] font-bold mt-0.5">افزودن</span>
                            </label>
                        @endif
                    </div>
                    @error('photos')
                        <span
                            class="text-[11px] text-coral-dark font-medium mt-1 block text-right">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-ink mb-1.5">توضیحات تکمیلی یا شرایط خاص</label>
                    <textarea wire:model="description" rows="4"
                        placeholder="مثال: قلاده قرمز دارد، بسیار ترسو است، نیاز به جراحی فوری پا دارد..."
                        class="w-full bg-white border border-ink/10 rounded-xl px-4 py-3 text-xs text-ink outline-none leading-5 focus:border-saffron-500"></textarea>
                    @error('description')
                        <span class="text-[11px] text-coral-dark font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-3 gap-2.5 pt-2">
                    <button type="button" wire:click="prevStep"
                        class="bg-white border border-ink/10 text-ink-soft font-bold py-3.5 rounded-xl transition">قبلی</button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="col-span-2 bg-emerald-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-emerald-600/10 active:scale-[0.99] transition flex items-center justify-center gap-1">
                        <span wire:loading.remove>ثبت و انتشار گزارش آگهی</span>
                        <span wire:loading>در حال آپلود و ثبت...</span>
                    </button>
                </div>
            </form>
        @endif

    </div>
</main>
