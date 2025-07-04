@if (auth()->check() && auth()->user()->avatar)
    <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="rounded-full h-8 w-8 mr-2">
@else
    <!-- Default avatar or icon -->
    <svg class="h-8 w-8 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
@endif
{{ auth()->user()->name }}
