<x-guest-layout>
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Login to Your Account</h2>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Pastikan setiap form memiliki @csrf token -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mb-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full py-3 flex justify-center bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-md">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="mt-4 text-center">
            <a class="text-sm text-gray-600 hover:text-gray-900 underline rounded-md" href="{{ route('register') }}">
                {{ __("Don't have an account? Sign up") }}
            </a>
        </div>
    </form>

    <!-- Developer Notice - Remove in production -->
    <div class="mt-6 text-xs p-3 bg-gray-100 rounded-md border border-gray-300">
        <p class="font-semibold text-gray-700">Developer Note:</p>
        <p class="text-gray-600 mt-1">To redirect users to the products page after login/registration, modify <code>HOME</code> constant in <code>app/Providers/RouteServiceProvider.php</code>:</p>
        <pre class="mt-2 bg-gray-800 text-gray-200 p-2 rounded overflow-x-auto">
    public const HOME = '/produk';  // Change from '/dashboard' to '/produk'
        </pre>
    </div>
</x-guest-layout>
