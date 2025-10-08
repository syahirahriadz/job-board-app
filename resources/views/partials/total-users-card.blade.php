<div
    class="p-6 rounded-lg border border-gray-200 dark:border-neutral-700
        bg-white dark:bg-neutral-800 shadow hover:shadow-md
        transition transform hover:-translate-y-1
        flex flex-col justify-between h-56">

    <!-- Header Row: Title + Icon -->
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-medium text-gray-500 dark:text-gray-400">
            Total Users
        </h3>
        <div class="p-2 rounded-md bg-green-100 dark:bg-green-900">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6 text-green-600 dark:text-green-300"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A4 4 0 017 17h10a4 4 0 011.879.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </div>

    <!-- Middle Content -->
    <div>
        <p class="mt-3 text-6xl font-extrabold text-green-600 dark:text-green-400">
            {{ $totalUsers }}
        </p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
            Updated today
        </p>
    </div>

    <!-- Footer Link -->
    <a href="{{ route('users.index') }}"
        class="mt-3 inline-block text-sm text-green-600 hover:underline dark:text-green-400">
        View all users →
    </a>
</div>
