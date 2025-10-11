<div
    class="p-6 rounded-lg border border-gray-200 dark:border-neutral-700
           bg-white dark:bg-neutral-800 shadow hover:shadow-md
           transition transform hover:-translate-y-1
           flex flex-col justify-between h-56">

    <!-- Header Row: Title + Icon -->
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-medium text-gray-500 dark:text-gray-400">
            @if(auth()->user()->isEmployer())
                New Applications
            @else
                Pending Applications
            @endif
        </h3>
        <div class="p-2 rounded-md bg-yellow-100 dark:bg-yellow-900">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6 text-yellow-600 dark:text-yellow-300"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <!-- Middle Content -->
    <div>
        <p class="mt-3 text-6xl font-extrabold text-yellow-600 dark:text-yellow-400">
            @if(auth()->user()->isEmployer())
                {{ $newApplications }}
            @else
                {{ $pendingApplications }}
            @endif
        </p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
            Awaiting review
        </p>
    </div>

    <!-- Footer Link -->
    <a href="{{ route('applications.index', ['status' => 'pending']) }}"
        class="mt-3 inline-block text-sm text-yellow-600 hover:underline dark:text-yellow-400">
        View pending applications →
    </a>
</div>
