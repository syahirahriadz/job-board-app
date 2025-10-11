<div
    class="p-6 rounded-lg border border-gray-200 dark:border-neutral-700
           bg-white dark:bg-neutral-800 shadow hover:shadow-md
           transition transform hover:-translate-y-1
           flex flex-col justify-between h-56">

    <!-- Header Row: Title + Icon -->
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-medium text-gray-500 dark:text-gray-400">
            @if(auth()->user()->isAdmin())
                Total Applications
            @elseif(auth()->user()->isEmployer())
                Applications Received
            @else
                Your Applications
            @endif
        </h3>
        <div class="p-2 rounded-md bg-purple-100 dark:bg-purple-900">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6 text-purple-600 dark:text-purple-300"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 14l9-5-9-5-9 5 9 5zm0 7v-7m0 0l9-5m-9 5l-9-5" />
            </svg>
        </div>
    </div>

    <!-- Middle Content -->
    <div>
        <p class="mt-3 text-6xl font-extrabold text-purple-600 dark:text-purple-400">
            {{ $totalApplications }}
        </p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
            Updated today
        </p>
    </div>

    <!-- Footer Link -->
    <a href="{{ route('applications.index') }}"
        class="mt-3 inline-block text-sm text-purple-600 hover:underline dark:text-purple-400">
        View all applications →
    </a>
</div>
