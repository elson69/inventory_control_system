<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Inventory Control System') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <style>
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-white antialiased text-gray-900">

    {{-- Navigation --}}
    <nav class="border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                </div>
                <span class="font-semibold text-gray-900 text-sm">Inventory Control</span>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <a href="/admin/login" class="text-gray-500 hover:text-gray-800 transition-colors font-medium">
                    Admin
                </a>
                <a href="/supplier/login" class="px-4 py-1.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Supplier Portal
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="max-w-5xl mx-auto px-6 pt-20 pb-16 text-center">
        <div class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 text-xs font-medium px-3 py-1 rounded-full mb-7 border border-indigo-100">
            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            Real-time stock management
        </div>
        <h1 class="text-5xl font-semibold text-gray-900 leading-tight mb-5 tracking-tight">
            Smart Inventory<br/>Management System
        </h1>
        <p class="text-lg text-gray-500 max-w-xl mx-auto mb-10 leading-relaxed">
            Track stock levels, manage suppliers, and get instant alerts
            before products run out — all in one place.
        </p>
        <div class="flex gap-3 justify-center flex-wrap">
            <a href="/admin/login"
               class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm text-sm">
                Admin Login
            </a>
            <a href="/supplier/login"
               class="px-6 py-2.5 bg-white text-gray-700 font-medium rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-colors text-sm">
                Supplier Portal
            </a>
        </div>
    </section>

    {{-- Features --}}
    <section class="max-w-5xl mx-auto px-6 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div class="p-6 rounded-xl border border-gray-100 bg-gray-50">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1.5 text-sm">Real-time Stock Tracking</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Monitor product quantities with every restock, deduction, and adjustment automatically logged and timestamped.
                </p>
            </div>

            <div class="p-6 rounded-xl border border-gray-100 bg-gray-50">
                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-teal-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1.5 text-sm">Supplier Management</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Give suppliers their own portal to view and manage the products they supply across your inventory.
                </p>
            </div>

            <div class="p-6 rounded-xl border border-gray-100 bg-gray-50">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1.5 text-sm">Low-Stock Alerts</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Get notified automatically whenever a product falls below its minimum stock level threshold.
                </p>
            </div>

        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 py-6">
        <div class="max-w-5xl mx-auto px-6 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} {{ config('app.name', 'Inventory Control System') }}. All rights reserved.
        </div>
    </footer>

</body>
</html>
