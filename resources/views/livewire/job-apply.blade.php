<div x-data="{ showModal: @entangle('showModal') }" x-cloak>
    <!-- Application Modal -->
    {{-- @if($showModal && $job) --}}
        <div
            x-show="showModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Modal Backdrop -->
            <div
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/80 transition-opacity animate-in fade-in duration-300"
                wire:click="closeModal"></div>

            <!-- Modal Content -->
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                            Apply for Position
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            {{ $job?->title }} at {{ $job?->company }}
                        </p>
                    </div>
                    <button wire:click="closeModal"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form wire:submit="submitApplication" class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Personal Information Section -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                    Personal Information
                                </h3>

                                <!-- Full Name -->
                                <div class="mb-4">
                                    <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Full Name *
                                    </label>
                                    <input type="text" id="full_name" wire:model="full_name"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    @error('full_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email Address *
                                    </label>
                                    <input type="email" id="email" wire:model="email"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Phone Number -->
                                <div class="mb-4">
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Phone Number *
                                    </label>
                                    <input type="tel" id="phone_number" wire:model="phone_number"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    @error('phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Professional Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                    Professional Information
                                </h3>

                                <!-- Resume Upload -->
                                <div class="mb-4">
                                    <label for="resume" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Resume/CV * <span class="text-gray-500">(PDF, DOC, DOCX - Max 10MB)</span>
                                    </label>
                                    <input type="file" id="resume" wire:model="resume" accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    @error('resume') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Cover Letter -->
                                <div class="mb-4">
                                    <label for="cover_letter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Cover Letter
                                    </label>
                                    <textarea id="cover_letter" wire:model="cover_letter" rows="4"
                                              placeholder="Write your cover letter here, or upload a file below..."
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                                    @error('cover_letter') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Cover Letter File Upload -->
                                <div class="mb-4">
                                    <label for="cover_letter_path" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Or Upload Cover Letter <span class="text-gray-500">(PDF, DOC, DOCX - Max 10MB)</span>
                                    </label>
                                    <input type="file" id="cover_letter_path" wire:model="cover_letter_path" accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    @error('cover_letter_path') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- LinkedIn URL -->
                                <div class="mb-4">
                                    <label for="linkedin_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        LinkedIn Profile URL
                                    </label>
                                    <input type="url" id="linkedin_url" wire:model="linkedin_url"
                                           placeholder="https://linkedin.com/in/yourprofile"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    @error('linkedin_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Portfolio URL -->
                                <div class="mb-4">
                                    <label for="portfolio_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Portfolio/Website URL
                                    </label>
                                    <input type="url" id="portfolio_url" wire:model="portfolio_url"
                                           placeholder="https://yourwebsite.com"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    @error('portfolio_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Application Specific Section -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                    Application Details
                                </h3>

                                <!-- Why Interested -->
                                <div class="mb-4">
                                    <label for="why_interested" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Why are you interested in this position? *
                                    </label>
                                    <textarea id="why_interested" wire:model="why_interested" rows="4"
                                              placeholder="Tell us why you're excited about this opportunity..."
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                                    @error('why_interested') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Expected Salary -->
                                <div class="mb-4">
                                    <label for="expected_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Expected Salary
                                    </label>
                                    <input type="text" id="expected_salary" wire:model="expected_salary"
                                           placeholder="e.g., $50,000 - $60,000 per year"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    @error('expected_salary') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Available Start Date -->
                                <div class="mb-4">
                                    <label for="available_start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Available Start Date *
                                    </label>
                                    <input type="date" id="available_start_date" wire:model="available_start_date"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    @error('available_start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Willing to Relocate -->
                                <div class="mb-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="willing_to_relocate" wire:model="willing_to_relocate"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="willing_to_relocate" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                            I am willing to relocate for this position
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700 space-x-3">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    {{-- @endif --}}

    <!-- Using dispatch for flash messages -->
</div>
