@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'mt-1']) }}>
        @foreach ((array) $messages as $message)
            <div class="flex items-center space-x-2 text-red-600 bg-red-50 px-3 py-2 rounded-md border border-red-200 shadow-sm transform transition-all duration-300 ease-in-out hover:scale-102">
                <svg class="w-5 h-5 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium">{{ $message }}</span>
            </div>
        @endforeach
    </div>
@endif
