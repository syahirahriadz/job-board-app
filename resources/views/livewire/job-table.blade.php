<section class="w-full">
    <!-- Outer card: add padding all around -->
    <div class="rounded-xl border border-gray-200 dark:border-neutral-700 p-6">

        <!-- Header + Search -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        Latest Job
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Browse a list of job postings with title, company &amp; location.
                    </p>
                </div>

                @can('create', \App\Models\Job::class)
                    <!-- Add New Job Button -->
                    <button
                        wire:click="createJob()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add New Job
                    </button>
                @endcan
            </div>

            <div class="mt-5">
                <!-- Search box aligned right -->
                <livewire:job-search />
            </div>
        </div>

        <!-- Table (no outer border now, just header row divider) -->
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Posted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs as $job)
                        <tr class="border-b border-gray-200 dark:border-neutral-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $job->title }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $job->company }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $job->location }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $job->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 flex space-x-2">
                                @can('update', $job)
                                    <button
                                        wire:click="editJob({{$job->id}})"
                                        x-transition
                                        class="p-2 rounded-full hover:bg-gray-100"
                                        title="Edit job">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="blue" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                @endcan
                                @can('delete', $job)
                                    <button
                                        type="button"
                                        wire:click="deleteJob({{ $job->id }})"
                                        onclick="return confirm('Are you sure you want to delete this job listing?')"
                                        class="p-2 rounded-full hover:bg-gray-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <livewire:job-created />
        <livewire:job-edit />
        <livewire:job-apply />
    </div>
</section>
