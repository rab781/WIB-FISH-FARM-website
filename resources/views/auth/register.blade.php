<x-guest-layout>
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Buat akun anda!</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <x-input-label for="name" :value="__('Name')" class="text-gray-700 font-semibold" />
            <x-text-input id="name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
            <x-text-input id="email" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
            <x-text-input id="password" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-semibold" />
            <x-text-input id="password_confirmation" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full py-3 flex justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <div class="mt-4 text-center">
            <a class="text-sm text-gray-600 hover:text-gray-900 underline rounded-md" href="{{ route('login') }}">
                {{ __('Already have an account? Sign in') }}
            </a>
        </div>
    </form>
</x-guest-layout>
