<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Cashier;
use Livewire\Component;

class CustomerSync extends Component
{
    public $syncStatus = '';

    public $isLoading = false;

    public $customerData = null;

    public $lastSyncAt = null;

    public function mount(): void
    {
        $this->loadCustomerData();
    }

    public function loadCustomerData(): void
    {
        $user = Auth::user();

        if ($user && $user->stripe_id) {
            try {
                $stripe = Cashier::stripe();
                $customer = $stripe->customers->retrieve($user->stripe_id);

                $this->customerData = [
                    'stripe_id' => $user->stripe_id,
                    'email' => $customer->email,
                    'name' => $customer->name,
                    'created' => date('M d, Y', $customer->created),
                    'currency' => $customer->currency,
                    'balance' => $customer->balance,
                ];
                $this->lastSyncAt = now()->format('M d, Y H:i:s');
            } catch (\Exception $e) {
                $this->syncStatus = 'Error loading customer data: '.$e->getMessage();
                Log::error('Error loading Stripe customer data', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    public function syncWithStripe(): void
    {
        $this->isLoading = true;
        $this->syncStatus = '';

        try {
            $user = Auth::user();

            if (! $user) {
                throw new \Exception('User not authenticated');
            }

            $stripe = Cashier::stripe();

            // Create or update Stripe customer
            if (! $user->stripe_id) {
                // Create new customer in Stripe
                $customer = $stripe->customers->create([
                    'name' => $user->name,
                    'email' => $user->email,
                ]);

                // Save the Stripe ID to user
                $user->update(['stripe_id' => $customer->id]);

                $this->syncStatus = 'Successfully created new Stripe customer';
                Log::info('Created new Stripe customer', [
                    'user_id' => $user->id,
                    'stripe_id' => $customer->id,
                ]);
            } else {
                // Update existing customer
                $customer = $stripe->customers->update($user->stripe_id, [
                    'name' => $user->name,
                    'email' => $user->email,
                ]);

                $this->syncStatus = 'Successfully updated Stripe customer data';
                Log::info('Updated Stripe customer', [
                    'user_id' => $user->id,
                    'stripe_id' => $user->stripe_id,
                ]);
            }

            // Refresh customer data
            $this->loadCustomerData();

        } catch (\Exception $e) {
            $this->syncStatus = 'Sync failed: '.$e->getMessage();
            Log::error('Stripe customer sync failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    public function deleteStripeCustomer(): void
    {
        $this->isLoading = true;
        $this->syncStatus = '';

        try {
            $user = Auth::user();

            if (! $user || ! $user->stripe_id) {
                throw new \Exception('No Stripe customer found');
            }

            $stripe = Cashier::stripe();
            $formerStripeId = $user->stripe_id;

            // Delete customer from Stripe
            $stripe->customers->delete($user->stripe_id);

            // Clear local Stripe data
            $user->update([
                'stripe_id' => null,
                'pm_type' => null,
                'pm_last_four' => null,
                'trial_ends_at' => null,
            ]);

            $this->customerData = null;
            $this->syncStatus = 'Successfully deleted Stripe customer';

            Log::info('Deleted Stripe customer', [
                'user_id' => $user->id,
                'former_stripe_id' => $formerStripeId,
            ]);

        } catch (\Exception $e) {
            $this->syncStatus = 'Delete failed: '.$e->getMessage();
            Log::error('Stripe customer deletion failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.settings.customer-sync');
    }
}
