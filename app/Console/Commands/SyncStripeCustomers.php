<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Laravel\Cashier\Cashier;

class SyncStripeCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:sync-customers
                            {--create : Create Stripe customers for users without stripe_id}
                            {--update : Update existing Stripe customers}
                            {--user= : Sync specific user by ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user data with Stripe customers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Stripe customer sync...');

        $query = User::query();

        // Filter by specific user if provided
        if ($userId = $this->option('user')) {
            $query->where('id', $userId);
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->warn('No users found to sync.');

            return;
        }

        $stripe = Cashier::stripe();
        $created = 0;
        $updated = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                if (! $user->stripe_id && $this->option('create')) {
                    // Create new customer
                    $customer = $stripe->customers->create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'metadata' => [
                            'user_id' => $user->id,
                        ],
                    ]);

                    $user->update(['stripe_id' => $customer->id]);
                    $created++;

                    $this->info("Created Stripe customer for user {$user->id}: {$customer->id}");

                } elseif ($user->stripe_id && $this->option('update')) {
                    // Update existing customer
                    $stripe->customers->update($user->stripe_id, [
                        'name' => $user->name,
                        'email' => $user->email,
                        'metadata' => [
                            'user_id' => $user->id,
                        ],
                    ]);

                    $updated++;

                    $this->info("Updated Stripe customer for user {$user->id}: {$user->stripe_id}");
                }

            } catch (\Exception $e) {
                $errors++;
                $this->error("Failed to sync user {$user->id}: ".$e->getMessage());
            }
        }

        $this->info("\nSync completed:");
        $this->info("Created: {$created}");
        $this->info("Updated: {$updated}");
        $this->info("Errors: {$errors}");
    }
}
