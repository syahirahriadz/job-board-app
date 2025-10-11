<div
    class="p-6 rounded-lg border border-gray-200 dark:border-neutral-700
        bg-white dark:bg-neutral-800 shadow hover:shadow-md
        transition transform hover:-translate-y-1
        flex flex-col justify-between h-56">

    <!-- Header Row: Title + Icon -->
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-medium text-gray-500 dark:text-gray-400">
            @if(auth()->user()->isAdmin())
                Total Jobs
            @elseif(auth()->user()->isEmployer())
                Your Posted Jobs
            @else
                Total Jobs
            @endif
        </h3>
        <div class="p-2 rounded-md bg-blue-100 dark:bg-blue-900">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6 text-blue-600 dark:text-blue-300"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7h18M3 12h18M3 17h18" />
            </svg>
        </div>
    </div>

    <!-- Middle Content -->
    <div>
        <p class="mt-3 text-7xl font-extrabold text-blue-600 dark:text-blue-400">
            {{ $totalJobs }}
        </p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
            Updated today
        </p>
    </div>

    <!-- Footer Link -->
    <a href="{{ route('jobs.index') }}"
        class="mt-3 inline-block text-sm text-blue-600 hover:underline dark:text-blue-400">
        View all jobs →
    </a>
</div>
