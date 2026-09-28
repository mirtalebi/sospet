<?php

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {
    public $isOpen = false;
    public $step = 1; // مرحله ۱: ورود موبایل | مرحله ۲: ورود OTP | مرحله ۳: مشخصات فردی

    public $mobile = '';
    public $otp = '';
    public $first_name = '';
    public $last_name = '';

    // گوش دادن به رویداد باز شدن مودال از سراسر برنامه
    #[On('open-auth')]
    public function openModal()
    {
        $this->isOpen = true;
        $this->step = 1;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    // مرحله اول: بررسی یا ارسال پیامک
    public function sendOtp()
    {
        $this->validate(
            [
                'mobile' => 'required|regex:/^09[0-9]{9}$/',
            ],
            [
                'mobile.required' => 'شماره موبایل الزامی است.',
                'mobile.regex' => 'فرمت شماره موبایل معتبر نیست.',
            ],
        );

        // در دنیای واقعی اینجا متد ارسال پیامک صدا زده می‌شود.
        // فعلا برای دمو به مرحله بعد می‌رویم و کد پیش‌فرض را 1234 در نظر می‌گیریم.
        $this->step = 2;
    }

    // مرحله دوم: تایید کد OTP
    public function verifyOtp()
    {
        if ($this->otp !== '1234') {
            $this->addError('otp', 'کد تایید اشتباه است. (کد تست: 1234)');
            return;
        }

        // بررسی اینکه آیا کاربر از قبل وجود دارد یا خیر
        $user = User::where('mobile', $this->mobile)->first();

        if ($user) {
            // کاربر وجود دارد -> لاگین مستقیم
            auth()->login($user, remember: true);
            $this->dispatch('user-logged-in');
            $this->closeModal();
            return $this->redirect('/', navigate: true);
        } else {
            // کاربر جدید است -> هدایت به مرحله ثبت اطلاعات تکمیلی
            $this->step = 3;
        }
    }

    // مرحله سوم: ثبت نام کاربر جدید
    public function register()
    {
        $this->validate(
            [
                'first_name' => 'required|string|min:2',
                'last_name' => 'required|string|min:2',
            ],
            [
                'first_name.required' => 'نام الزامی است.',
                'last_name.required' => 'نام خانوادگی الزامی است.',
            ],
        );

        $user = User::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'mobile' => $this->mobile,
        ]);

        auth()->login($user, remember: true);
        $this->dispatch('user-logged-in');
        $this->closeModal();
        return $this->redirect('/', navigate: true);
    }
};
?>

<div>
    <div x-data="{ show: $wire.entangle('isOpen') }" x-show="$wire.isOpen" @click="$wire.closeModal()"
        class="fixed inset-0 bg-ink/40 backdrop-blur-sm z-50 transition-opacity max-w-md mx-auto" style="display: none;">
    </div>

    <div class="fixed inset-x-0 bottom-0 max-w-md mx-auto bg-sand rounded-t-[2.5rem] shadow-2xl z-50 transform transition-transform duration-300 pb-8 px-6 pt-4 border-t border-ink/5"
        x-show="$wire.isOpen" x-transition:enter="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="translate-y-0" x-transition:leave-end="translate-y-full" style="display: none;">

        <div class="w-12 h-1 bg-ink/10 rounded-full mx-auto mb-6"></div>

        @if ($step === 1)
            <div class="text-center mb-5">
                <h3 class="font-display text-xl text-ink">ورود یا ثبت‌نام</h3>
                <p class="text-xs text-ink-soft mt-1">شماره موبایل خود را وارد کنید تا کد تایید ارسال شود.</p>
            </div>
            <form wire:submit.prevent="sendOtp" class="flex flex-col gap-4">
                <div>
                    <input type="text" wire:model="mobile" placeholder="مثال: ۰۹۱۲۳۴۵۶۷۸۹" dir="ltr"
                        class="w-full bg-white border border-ink/10 rounded-xl px-4 py-3 text-sm text-ink text-center outline-none focus:border-saffron-500">
                    @error('mobile')
                        <span class="text-xs text-coral-dark font-medium mt-1 block text-right">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full bg-saffron-500 text-white font-bold py-3 rounded-xl shadow-md hover:bg-saffron-600 transition-colors">
                    ارسال کد تایید
                </button>
            </form>
        @endif

        @if ($step === 2)
            <div class="text-center mb-5">
                <h3 class="font-display text-xl text-ink">تایید شماره موبایل</h3>
                <p class="text-xs text-ink-soft mt-1">کد ۴ رقمی ارسال شده به شماره <span class="font-bold"
                        dir="ltr">{{ $mobile }}</span> را وارد کنید.</p>
            </div>
            <form wire:submit.prevent="verifyOtp" class="flex flex-col gap-4">
                <div>
                    <input type="text" wire:model="otp" placeholder="کد ۴ رقمی (تست: 1234)" dir="ltr"
                        maxlength="4"
                        class="w-full bg-white border border-ink/10 rounded-xl px-4 py-3 text-sm text-ink text-center tracking-[0.5em] font-bold outline-none focus:border-saffron-500">
                    @error('otp')
                        <span class="text-xs text-coral-dark font-medium mt-1 block text-right">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full bg-saffron-500 text-white font-bold py-3 rounded-xl shadow-md hover:bg-saffron-600 transition-colors">
                    بررسی و ورود
                </button>
                <button type="button" wire:click="$set('step', 1)"
                    class="text-xs font-semibold text-ink-soft hover:text-ink transition-colors">
                    تغییر شماره موبایل
                </button>
            </form>
        @endif

        @if ($step === 3)
            <div class="text-center mb-5">
                <h3 class="font-display text-xl text-ink">تکمیل مشخصات</h3>
                <p class="text-xs text-ink-soft mt-1">به ساس‌پت خوش اومدی! برای اولین ورود نام خودت را وارد کن.</p>
            </div>
            <form wire:submit.prevent="register" class="flex flex-col gap-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <input type="text" wire:model="first_name" placeholder="نام"
                            class="w-full bg-white border border-ink/10 rounded-xl px-3 py-3 text-sm text-ink outline-none focus:border-saffron-500">
                        @error('first_name')
                            <span
                                class="text-xs text-coral-dark font-medium mt-1 block text-right">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="last_name" placeholder="نام خانوادگی"
                            class="w-full bg-white border border-ink/10 rounded-xl px-3 py-3 text-sm text-ink outline-none focus:border-saffron-500">
                        @error('last_name')
                            <span
                                class="text-xs text-coral-dark font-medium mt-1 block text-right">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <button type="submit"
                    class="w-full bg-saffron-500 text-white font-bold py-3 rounded-xl shadow-md hover:bg-saffron-600 transition-colors">
                    ساخت حساب و ورود
                </button>
            </form>
        @endif
    </div>
</div>
