<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::admin'), Title('کاربران')] class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleAdmin(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->is(auth()->user())) {
            session()->flash('error', 'نقش حساب خودتان را نمی‌توانید تغییر دهید.');

            return;
        }

        $user->forceFill(['role' => $user->isAdmin() ? 'user' : 'admin'])->save();
    }

    public function deleteUser(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->is(auth()->user())) {
            session()->flash('error', 'حساب خودتان را نمی‌توانید حذف کنید.');

            return;
        }

        $user->delete();
    }

    public function with(): array
    {
        $users = User::query()
            ->withCount('pets')
            ->when($this->search, function ($query) {
                $term = '%' . $this->search . '%';
                $query->where(fn($q) => $q->where('first_name', 'like', $term)->orWhere('last_name', 'like', $term)->orWhere('mobile', 'like', $term));
            })
            ->latest('id')
            ->paginate(15);

        return ['users' => $users];
    }
}; ?>

<div>
    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="font-display text-3xl text-text-title">کاربران</h1>
            <p class="mt-1 text-sm text-text-muted">{{ number_format($users->total()) }} کاربر ثبت‌شده</p>
        </div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="جستجوی نام یا موبایل"
            class="w-full rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20 sm:w-72">
    </div>

    @if (session('error'))
        <div class="mb-4 rounded-xl bg-danger-custom/10 px-4 py-3 text-sm font-semibold text-danger-custom">
            {{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto rounded-2xl border border-border-custom bg-bg-main shadow-sm">
        <table class="w-full text-right text-sm">
            <thead class="bg-bg-secondary text-xs text-text-muted">
                <tr>
                    <th class="px-4 py-3 font-semibold">کاربر</th>
                    <th class="px-4 py-3 font-semibold">موبایل</th>
                    <th class="px-4 py-3 font-semibold">نقش</th>
                    <th class="px-4 py-3 font-semibold">گزارش‌ها</th>
                    <th class="px-4 py-3 font-semibold">عضویت</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-custom">
                @forelse ($users as $user)
                    <tr wire:key="user-{{ $user->id }}" class="transition hover:bg-bg-secondary/60">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary/10 font-bold text-primary">{{ mb_substr($user->name, 0, 1) }}</span>
                                <span class="font-semibold text-text-title">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3" dir="ltr">{{ $user->mobile }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $user->isAdmin() ? 'bg-cta/10 text-cta' : 'bg-bg-secondary text-text-body' }}">{{ $user->isAdmin() ? 'مدیر' : 'کاربر' }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $user->pets_count }}</td>
                        <td class="px-4 py-3">{{ $user->created_at?->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                @unless ($user->is(auth()->user()))
                                    <button wire:click="toggleAdmin({{ $user->id }})"
                                        class="rounded-lg border border-primary/30 px-3 py-1.5 text-xs font-bold text-primary transition hover:bg-primary hover:text-white">
                                        {{ $user->isAdmin() ? 'تبدیل به کاربر' : 'تبدیل به مدیر' }}
                                    </button>
                                    <button wire:click="deleteUser({{ $user->id }})"
                                        wire:confirm="این کاربر و تمام گزارش‌هایش حذف شود؟"
                                        class="rounded-lg border border-danger-custom/30 px-3 py-1.5 text-xs font-bold text-danger-custom transition hover:bg-danger-custom hover:text-white">حذف</button>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-text-muted">کاربری یافت نشد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</div>
