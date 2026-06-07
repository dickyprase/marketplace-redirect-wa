<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col">
    <header class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('products.index') }}" class="text-xl font-bold text-gray-800">
                {{ config('app.name') }}
            </a>
            <nav class="text-sm">
                @auth
                    <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-800">Dashboard Admin</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700">Login Admin</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-1 max-w-6xl mx-auto px-4 py-8 w-full">
        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="bg-white border-t mt-8">
        <div class="max-w-6xl mx-auto px-4 py-6 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Checkout via WhatsApp.
        </div>
    </footer>
</body>
</html>
