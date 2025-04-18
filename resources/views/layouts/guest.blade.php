<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WIB FISH FARM') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=montserrat:700,800|poppins:500,600&display=swap" rel="stylesheet" />
        <!-- Font Awesome untuk ikon -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Debug Info -->
        <script>
            console.log('Guest layout loaded successfully');
        </script>

        <style>
            .logo-container {
                background: linear-gradient(135deg, #2563eb, #3b82f6, #60a5fa);
                padding: 1.5rem 2.5rem;
                border-radius: 1rem;
                box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
                transform: perspective(1000px) rotateX(5deg);
                transition: all 0.3s ease;
            }

            .logo-container:hover {
                transform: perspective(1000px) rotateX(0);
                box-shadow: 0 15px 30px -5px rgba(59, 130, 246, 0.6);
            }

            .logo-text {
                font-family: 'Montserrat', sans-serif;
                font-weight: 800;
                font-size: 1.8rem;
                background: linear-gradient(90deg, #f59e0b, #fbbf24, #fcd34d);
                -webkit-background-clip: text;
                color: transparent;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
                letter-spacing: 0.05em;
            }

            .logo-tagline {
                font-family: 'Poppins', sans-serif;
                color: white;
                font-size: 0.85rem;
                margin-top: 0.25rem;
                letter-spacing: 0.05em;
                opacity: 0.9;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Debug Info -->
        <div style="position: fixed; top: 0; left: 0; background: #ff0; color: #000; padding: 5px; z-index: 9999;">
            Layout loaded: {{ request()->route()->getName() ?? 'Unknown route' }}
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/" class="block no-underline">
                    <div class="logo-container text-center">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-fish text-white text-2xl mr-2"></i>
                            <h1 class="logo-text">WIB FISH FARM</h1>
                        </div>
                        <div class="logo-tagline">Premium Aquaculture</div>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <!-- Debug Info -->
                @if(empty(trim($slot)))
                    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                        Warning: The content slot is empty. This might be why nothing is displaying.
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
