<x-filament-panels::page.simple>
    <x-slot name="heading">
        <div class="flex flex-col items-center gap-3 text-center mb-1">
            <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                </svg>
            </div>
            <div>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">Inventory Control</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Sign in to the Admin Portal</p>
            </div>
        </div>
    </x-slot>

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <div class="text-center text-sm text-gray-400 dark:text-gray-500 mt-4 space-y-1">
        <p>
            Supplier?
            <a href="/supplier/login" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                Sign in here
            </a>
        </p>
        <p>
            <a href="/" class="hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                &larr; Back to home
            </a>
        </p>
    </div>
</x-filament-panels::page.simple>
