<div
    class="p-6 rounded-lg border border-gray-200 dark:border-neutral-700
           bg-white dark:bg-neutral-800 shadow hover:shadow-md
           transition transform hover:-translate-y-1
           flex flex-col justify-between h-56">

    <!-- Header Row: Title + Icon -->
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-medium text-gray-500 dark:text-gray-400">
            Jobs Pending Payment
        </h3>
        <div class="p-2 rounded-md bg-orange-100 dark:bg-orange-900">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6 text-orange-600 dark:text-orange-300"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
            </svg>
        </div>
    </div>

    <!-- Middle Content -->
    <div>
        <p class="mt-3 text-6xl font-extrabold text-orange-600 dark:text-orange-400">
            {{ $pendingPaymentJobs }}
        </p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
            Awaiting payment
        </p>
    </div>

    <!-- Footer Link -->
    <a href="{{ route('jobs.index') }}?filter=pending"
        class="mt-3 inline-block text-sm text-orange-600 hover:underline dark:text-orange-400">
        View pending jobs →
    </a>
</div>
