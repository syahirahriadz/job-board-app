<div x-data="{ showModal: @entangle('showModal') }" x-cloak>
    @if($showModal)
        <div x-show="showModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto">

            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500/80 transition-opacity"
                wire:click="closeModal">
            </div>

            <!-- Modal container -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal panel -->
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">

                    <!-- Modal header -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Generate Job Description with AI
                        </h3>
                        <button wire:click="closeModal"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="space-y-4">
                        <!-- Prompt Input -->
                        <div>
                            <label for="ai-prompt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Describe what you want to generate
                            </label>
                            <textarea
                                wire:model="prompt"
                                id="ai-prompt"
                                rows="3"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                                placeholder="Example: Generate a professional job description for a Senior Laravel Developer position in Malaysia..."
                            ></textarea>
                            @error('prompt')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Generate Button -->
                        <div class="flex justify-center">
                            <button
                                wire:click="generatePrompt"
                                wire:loading.attr="disabled"
                                @if($currentPrompt && $currentPrompt->status === 'pending') disabled @endif
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-md font-medium transition-all duration-200 hover:shadow-lg disabled:opacity-50"
                            >
                                @if($currentPrompt && $currentPrompt->status === 'pending')
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                    <span>Generating...</span>
                                @else
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span>Generate with AI</span>
                                @endif
                            </button>
                        </div>

                        <!-- AI Response Section -->
                        @if($currentPrompt)
                            <div class="mt-6 p-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700"
                                wire:poll.1s="checkStatus">

                                <div class="flex items-center mb-2">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Status:</span>
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full
                                        @if($currentPrompt->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($currentPrompt->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                        {{ ucfirst($currentPrompt->status) }}
                                    </span>
                                </div>

                                @if($currentPrompt->status === 'pending')
                                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 dark:border-blue-400 mr-2"></div>
                                        Generating AI response...
                                    </div>
                                @elseif($currentPrompt->status === 'completed')
                                    <div class="mt-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-900 dark:text-gray-100">Generated Content:</h4>
                                        </div>
                                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border max-h-64 overflow-y-auto prose prose-sm dark:prose-invert max-w-none">
                                            {!! $this->responseHtml !!}
                                        </div>
                                        <div class="mt-4 text-center">
                                            <button
                                                wire:click="useResponse"
                                                class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium transition-colors"
                                            >
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Use This Response
                                            </button>
                                        </div>
                                    </div>
                                @elseif($currentPrompt->status === 'failed')
                                    <div class="mt-4 text-red-600 dark:text-red-400">
                                        <strong>Error:</strong> {{ $currentPrompt->error_message ?? 'Failed to generate AI response. Please try again.' }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Modal footer -->
                    <div class="mt-6 flex justify-end">
                        <button
                            wire:click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
