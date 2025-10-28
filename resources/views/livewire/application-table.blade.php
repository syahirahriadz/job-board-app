<section class="w-full">
    <!-- Outer card: add padding all around -->
    <div class="rounded-lg border border-gray-200 dark:border-neutral-700 p-6">

        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        Latest Applications
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Browse job applications with applicant name, email, applied job, date &amp; actions.
                    </p>
                </div>
            </div>

            <!-- Controls Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <!-- Filter Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter by status:</span>
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="$set('statusFilter', '')"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 border
                                {{ !$statusFilter
                                    ? 'bg-gray-100 text-gray-900 border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600'
                                    : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                All
                            </span>
                        </button>
                        <button wire:click="$set('statusFilter', 'pending')"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 border
                                {{ $statusFilter === 'pending'
                                    ? 'bg-yellow-100 text-yellow-800 border-yellow-300 dark:bg-yellow-900 dark:text-yellow-300 dark:border-yellow-700'
                                    : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pending
                            </span>
                        </button>
                        <button wire:click="$set('statusFilter', 'approved')"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 border
                                {{ $statusFilter === 'approved'
                                    ? 'bg-green-100 text-green-800 border-green-300 dark:bg-green-900 dark:text-green-300 dark:border-green-700'
                                    : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Approved
                            </span>
                        </button>
                        <button wire:click="$set('statusFilter', 'rejected')"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 border
                                {{ $statusFilter === 'rejected'
                                    ? 'bg-red-100 text-red-800 border-red-300 dark:bg-red-900 dark:text-red-300 dark:border-red-700'
                                    : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Rejected
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Search box -->
                <div class="mt-4">
                    <livewire:application-search />
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full">
                    <thead class="bg-gray-50 dark:bg-neutral-800">
                        <tr>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'employer')
                                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Applicant</th>
                            @endif
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Job Title</th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Applied On</th>
                            <th class="px-6 py-4 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                <tbody>
                    @forelse ($applications as $application)
                        <tr class="border-b border-gray-200 dark:border-neutral-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'employer')
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $application->full_name }}</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $application->email }}</span>
                                    </div>
                                </td>
                            @endif
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900">{{ optional($application->job)->title ?? 'N/A' }}</span>
                                    <span class="text-sm text-gray-500">{{ optional($application->job)->company }}, {{ optional($application->job)->location }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $application->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($application->status === 'approved')
                                    <flux:badge color="green" class="font-semibold">Approved</flux:badge>
                                @elseif($application->status === 'rejected')
                                    <flux:badge color="red" class="font-semibold">Rejected</flux:badge>
                                @else
                                    <flux:badge color="gray" class="font-semibold">Pending</flux:badge>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- 👁 View -->
                                    @can('view', $application)
                                        <button
                                            wire:click="viewApplication({{ $application->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 hover:bg-gray-200 transition"
                                            title="View Application">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="green" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>
                                    @endcan

                                    @can('approve', $application)
                                        <!-- ✅ Approve -->
                                        <button
                                            wire:click="approve({{ $application->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-green-700 font-medium bg-green-50 hover:bg-green-100 border border-green-200 shadow-sm transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Approve
                                        </button>
                                    @endcan

                                    @can('reject', $application)
                                        <!-- ❌ Reject -->
                                        <button
                                            wire:click="reject({{ $application->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-red-700 font-medium bg-red-50 hover:bg-red-100 border border-red-200 shadow-sm transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Reject
                                        </button>
                                    @endcan

                                    <!-- ✏️ Edit & 🗑 Delete (only applicant can see and only for pending applications) -->
                                    @can('update', $application)
                                        @if($application->status === 'pending')
                                            <button
                                                wire:click="editApplication({{ $application->id }})"
                                                class="p-2 rounded-full hover:bg-gray-100"
                                                title="Edit Application">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="blue" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </button>
                                        @endif
                                    @endcan

                                    @can('delete', $application)
                                        <button
                                            type="button"
                                            wire:click="deleteApplication({{ $application->id }})"
                                            onclick="return confirm('Are you sure you want to delete this application?')"
                                            class="p-2 rounded-full hover:bg-gray-100"
                                            title="Delete Application">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (auth()->user()->role === 'admin' || auth()->user()->role === 'employer') ? '5' : '4' }}" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <svg class="w-12 h-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div class="text-center">
                                        <p class="text-gray-500 dark:text-gray-400 text-base">No applications found</p>
                                        @if($statusFilter)
                                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Try changing your filter or search criteria</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>

        <!-- Modals -->
        <livewire:application-view />
        <livewire:application-edit />
    </div>
</section>
