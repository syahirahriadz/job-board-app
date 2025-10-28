<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Stripe Customer Sync')" :subheading="__('Manage your Stripe customer data synchronization')">

    <!-- Status Messages -->
    @if($syncStatus)
        <flux:callout
            :variant="str_contains($syncStatus, 'failed') || str_contains($syncStatus, 'Error') ? 'danger' : 'success'"
            class="mb-4">
            {{ $syncStatus }}
        </flux:callout>
    @endif

    <!-- Customer Data Display -->
    @if($customerData)
        <div class="mb-6">
            <flux:heading size="lg" class="mb-3">Current Customer Data</flux:heading>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stripe ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $customerData['stripe_id'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customerData['email'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customerData['name'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customerData['created'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Currency</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ strtoupper($customerData['currency'] ?? 'N/A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Balance</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customerData['balance'] ?? 0 }}</dd>
                    </div>
                </dl>
            </div>
            @if($lastSyncAt)
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Last synced: {{ $lastSyncAt }}
                </p>
            @endif
        </div>
    @else
        <flux:callout variant="warning" class="mb-6">
            <div>
                <flux:heading size="sm">No Stripe Customer Found</flux:heading>
                <p class="mt-1 text-sm">
                    You don't have a Stripe customer record yet. Click "Sync with Stripe" to create one.
                </p>
            </div>
        </flux:callout>
    @endif

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3 mb-8">
        <flux:button
            wire:click="syncWithStripe"
            :disabled="$isLoading"
            variant="primary"
            wire:loading.attr="disabled"
            wire:target="syncWithStripe">
            <span wire:loading.remove wire:target="syncWithStripe">
                {{ $customerData ? 'Update Customer Data' : 'Create Stripe Customer' }}
            </span>
            <span wire:loading wire:target="syncWithStripe">
                Syncing...
            </span>
        </flux:button>

        <flux:button
            wire:click="loadCustomerData"
            :disabled="$isLoading"
            variant="ghost"
            wire:loading.attr="disabled"
            wire:target="loadCustomerData">
            <span wire:loading.remove wire:target="loadCustomerData">
                Refresh Data
            </span>
            <span wire:loading wire:target="loadCustomerData">
                Loading...
            </span>
        </flux:button>

        @if($customerData)
            <flux:button
                wire:click="deleteStripeCustomer"
                :disabled="$isLoading"
                variant="danger"
                wire:loading.attr="disabled"
                wire:target="deleteStripeCustomer"
                onclick="return confirm('Are you sure you want to delete your Stripe customer? This action cannot be undone.')">
                <span wire:loading.remove wire:target="deleteStripeCustomer">
                    Delete Customer
                </span>
                <span wire:loading wire:target="deleteStripeCustomer">
                    Deleting...
                </span>
            </flux:button>
        @endif
    </div>

    <!-- Local User Data -->
    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
        <flux:heading size="lg" class="mb-3">Local User Data</flux:heading>
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ auth()->id() }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ auth()->user()->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Local Stripe ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">
                        {{ auth()->user()->stripe_id ?: 'Not set' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ auth()->user()->pm_type ? auth()->user()->pm_type . ' ****' . auth()->user()->pm_last_four : 'None' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Payment</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ auth()->user()->last_payment_date ? auth()->user()->last_payment_date->format('M d, Y H:i:s') : 'Never' }}
                    </dd>
                </div>
            </dl>
        </div>
    </x-settings.layout>
</section>
