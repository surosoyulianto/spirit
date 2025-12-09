<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-600 to-purple-600 px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden p-8">
        <div class="text-center mb-6">
            <div id="lottie-login" class="w-32 h-32 mx-auto mb-4"></div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Masuk ke Akun Anda</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Silakan login untuk melanjutkan</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-6">
            <!-- Email or Username -->
            <div>
                <x-input-label for="email" :value="__('Email or Username')" />
                <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="text" name="email" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:underline dark:text-indigo-400" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div>
                <x-primary-button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm transition duration-200">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>

        <div class="text-center mt-6 text-sm text-gray-600 dark:text-gray-400">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline dark:text-indigo-400" wire:navigate>Daftar sekarang</a>
        </div>
    </div>
</div>
