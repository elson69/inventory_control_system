<x-filament-panels::page.simple>
    <x-slot name="heading">
        <div class="flex flex-col items-center gap-3 text-center mb-1">
            <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">Inventory Control</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Sign in to the Supplier Portal</p>
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
            Administrator?
            <a href="/admin/login" class="text-teal-600 dark:text-teal-400 hover:underline font-medium">
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
