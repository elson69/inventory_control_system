<x-filament-panels::page.simple>
    {{ $this->content }}

    <div class="text-center text-sm mt-4 space-y-1.5">
        <p class="text-gray-500 dark:text-gray-400">
            Administrator?
            <a href="/admin/login" class="text-teal-600 dark:text-teal-400 hover:underline font-medium">
                Sign in here
            </a>
        </p>
        <p>
            <a href="/" class="text-xs text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                &larr; Back to home
            </a>
        </p>
    </div>
</x-filament-panels::page.simple>
