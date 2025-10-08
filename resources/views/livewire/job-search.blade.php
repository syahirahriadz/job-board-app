<div class="w-full mb-5">
    <!-- Search Input Section -->
    {{-- <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6"> --}}
        <div class="relative mb-4">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text"
                   wire:model.live="search"
                   placeholder="Search for jobs, companies, or locations..."
                   class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200">
        </div>

        @if($search)
            <div class="mt-3 flex items-center text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Searching for: "{{ $search }}"
                <button wire:click="$set('search', '')" class="ml-2 text-blue-500 hover:text-blue-700 text-xs">
                    Clear
                </button>
            </div>
        @endif
    {{-- </div> --}}

    {{-- @if (config('app.debug'))
        <!-- Component Lifecycle Logs (Debug Mode Only) -->
        <div class="mt-4 bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Search Component Lifecycle Log:</h3>
            <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1 font-mono max-h-32 overflow-y-auto">
                @foreach ($log as $entry)
                    <div class="py-1 px-2 bg-white dark:bg-gray-800 rounded border border-gray-100 dark:border-gray-700">{{ $entry }}</div>
                @endforeach
            </div>
        </div>
    @endif --}}
</div>

