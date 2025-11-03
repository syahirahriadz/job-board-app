<div x-data="{ showModal: @entangle('showModal') }" x-cloak>
    <!-- Modal Backdrop -->
    {{-- @if($showModal) --}}
        <div x-show="showModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto animate-in fade-in duration-300">
            <!-- Background overlay -->
            <div
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/80 transition-opacity animate-in fade-in duration-300"
                wire:click="closeModal">
            </div>

            <!-- Modal container -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal panel -->
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl sm:my-8 sm:w-full sm:max-w-lg sm:p-6 animate-in slide-in-from-bottom-4 sm:slide-in-from-bottom-0 sm:zoom-in-95 duration-300 ease-out">
                    <!-- Modal header -->
                    <div
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Create a New Job
                        </h3>
                        <button wire:click="closeModal"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <form wire:submit.prevent="save" class="space-y-4">
                        <!-- Job Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Job Title *
                            </label>
                            <input type="text"
                                    id="title"
                                    wire:model="title"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-400 dark:hover:border-gray-500"
                                    placeholder="Enter job title"
                                    required
                            >
                            @error('title')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Company -->
                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Company
                            </label>
                            <input type="text"
                                    id="company"
                                    wire:model="company"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-400 dark:hover:border-gray-500"
                                    placeholder="Enter company name"
                                    required
                            >
                            @error('company')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Location
                            </label>
                            <input type="text"
                                    id="location"
                                    wire:model="location"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-400 dark:hover:border-gray-500"
                                    placeholder="Enter location"
                                    required
                            >
                            @error('location')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Description
                                </label>
                                <button
                                    type="button"
                                    wire:click="openAiModal"
                                    class="inline-flex items-center gap-1.5 px-2 py-1 text-xs bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-md font-medium transition-all duration-200 hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                    title="Generate with AI"
                                >
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span>Generate with AI</span>
                                </button>
                            </div>
                            {{-- <textarea id="description"
                                        wire:model="description"
                                        rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-400 dark:hover:border-gray-500"
                                        placeholder="Enter job description"></textarea> --}}
                            <!-- Rich Text Editor -->
                            <div
                                x-data="{
                                    content: @entangle('description'),
                                    editor: null,

                                    init() {
                                        this.setupEditor();
                                    },

                                    setupEditor() {
                                        // For now, use contenteditable div with basic formatting
                                        this.$refs.editor.innerHTML = this.content;

                                        // Listen for content changes
                                        this.$refs.editor.addEventListener('input', () => {
                                            this.content = this.$refs.editor.innerHTML;
                                        });

                                        // Watch for external content updates (like from AI)
                                        this.$watch('content', (newValue) => {
                                            if (this.$refs.editor.innerHTML !== newValue) {
                                                this.$refs.editor.innerHTML = newValue;
                                            }
                                        });
                                    },

                                    formatText(command, value = null) {
                                        document.execCommand(command, false, value);
                                        this.content = this.$refs.editor.innerHTML;
                                    },

                                    isActive(command) {
                                        return document.queryCommandState(command);
                                    }
                                }"
                                x-init="init()"
                                wire:ignore
                                class="w-full"
                            >
                                <!-- Toolbar -->
                                <div class="border border-gray-300 dark:border-gray-600 rounded-t-md bg-gray-50 dark:bg-gray-700 px-3 py-2 flex flex-wrap gap-1">
                                    <button
                                        type="button"
                                        @click="formatText('bold')"
                                        :class="{ 'bg-gray-300 dark:bg-gray-600': isActive('bold') }"
                                        class="px-3 py-1 text-sm rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-700 dark:text-gray-300 font-bold"
                                        title="Bold"
                                    >
                                        B
                                    </button>

                                    <button
                                        type="button"
                                        @click="formatText('italic')"
                                        :class="{ 'bg-gray-300 dark:bg-gray-600': isActive('italic') }"
                                        class="px-3 py-1 text-sm rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-700 dark:text-gray-300 italic"
                                        title="Italic"
                                    >
                                        I
                                    </button>

                                    <button
                                        type="button"
                                        @click="formatText('underline')"
                                        :class="{ 'bg-gray-300 dark:bg-gray-600': isActive('underline') }"
                                        class="px-3 py-1 text-sm rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-700 dark:text-gray-300 underline"
                                        title="Underline"
                                    >
                                        U
                                    </button>

                                    <div class="border-l border-gray-300 dark:border-gray-600 mx-1"></div>

                                    <button
                                        type="button"
                                        @click="formatText('insertUnorderedList')"
                                        :class="{ 'bg-gray-300 dark:bg-gray-600': isActive('insertUnorderedList') }"
                                        class="px-3 py-1 text-sm rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-700 dark:text-gray-300"
                                        title="Bullet List"
                                    >
                                        •
                                    </button>

                                    <button
                                        type="button"
                                        @click="formatText('insertOrderedList')"
                                        :class="{ 'bg-gray-300 dark:bg-gray-600': isActive('insertOrderedList') }"
                                        class="px-3 py-1 text-sm rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-700 dark:text-gray-300"
                                        title="Numbered List"
                                    >
                                        1.
                                    </button>

                                    <div class="border-l border-gray-300 dark:border-gray-600 mx-1"></div>

                                    <button
                                        type="button"
                                        @click="formatText('formatBlock', 'h2')"
                                        class="px-3 py-1 text-sm rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-700 dark:text-gray-300 font-bold"
                                        title="Heading"
                                    >
                                        H2
                                    </button>

                                    <button
                                        type="button"
                                        @click="formatText('formatBlock', 'p')"
                                        class="px-3 py-1 text-sm rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-700 dark:text-gray-300"
                                        title="Paragraph"
                                    >
                                        P
                                    </button>
                                </div>

                                <!-- Editor Content -->
                                <div
                                    x-ref="editor"
                                    contenteditable="true"
                                    class="rich-text-editor border border-t-0 border-gray-300 dark:border-gray-600 rounded-b-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white min-h-[150px] p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Describe the role, responsibilities, and what you're looking for in a candidate..."
                                ></div>
                            </div>
                            @error('description')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                x-transition
                                type="button"
                                wire:click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
                            >
                                <span wire:loading.remove>Create Job</span>
                                <span wire:loading>Creating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {{-- @endif --}}

    <!-- AI Description Modal -->
    <livewire:ai-description-modal />
</div>
