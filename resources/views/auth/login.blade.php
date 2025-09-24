<x-guest-layout>
    <div class="text-center space-y-5 text-xl mb-7">
        <h1 class="text-2xl font-semibold">Selamat Datang !</h1>
        <h2>Silahkan masukkan informasi akun anda</h2>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#4CAF50] shadow-sm focus:ring-[#4CAF50]" name="remember">
                    <span class="ms-2 text-sm">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                <a class="hover:underline text-sm hover:text-gray-400 rounded-md" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
                @endif

            </div>
        </div>

        <x-primary-button class="flex justify-center my-4">
            {{ __('Masuk') }}
        </x-primary-button>

        <div class="flex justify-center">
            <H1>Belum memiliki akun?&nbsp;</H1>
            <a href="{{ route('register') }}" class="hover:text-gray-400 hover:underline hover:underline-offset-2">Daftar</a>
        </div>
    </form>
</x-guest-layout>