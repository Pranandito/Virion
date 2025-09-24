<x-guest-layout>
    <div class="text-center space-y-5 text-xl mb-7">
        <h1 class="text-2xl font-semibold">Selamat Datang !</h1>
        <h2>Silahkan masukkan informasi akun anda</h2>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nama -->
        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Nomor Wa -->
        <div class="mt-4">
            <x-input-label for="nomor_hp" :value="__('Nomor Hp')" />
            <x-text-input id="nomor_hp" class="block mt-1 w-full" type="text" name="nomor_hp" :value="old('nomor_hp')" required autofocus autocomplete="nomor_hp" />
            <x-input-error :messages="$errors->get('nomor_hp')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="flex justify-center my-4">
            {{ __('Daftar') }}
        </x-primary-button>

        <div class="flex justify-center">
            <H1>Sudah memiliki akun?&nbsp;</H1>
            <a href="{{ route('login') }}" class="hover:text-gray-400 hover:underline hover:underline-offset-2">Masuk</a>
        </div>
    </form>
</x-guest-layout>