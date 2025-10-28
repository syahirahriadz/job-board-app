<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\ApplicationTable;
use App\Livewire\JobTable;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\CustomerSync;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\UserList;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Job;
use Laravel\Cashier\Cashier;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])
    ->get('/users', UserList::class)
    ->can('is-admin')
    ->name('users.index');

Route::middleware(['auth', 'verified'])
    ->get('/jobs', JobTable::class)
    ->name('jobs.index');

Route::middleware(['auth', 'verified'])
    ->get('/applications', ApplicationTable::class)
    ->name('applications.index');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/stripe', CustomerSync::class)->name('settings.stripe');
});

// Success and cancel routes must come BEFORE the parameterized checkout route
Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [App\Http\Controllers\CheckoutController::class, 'cancel'])->name('checkout.cancel');

// Main checkout route - must come AFTER specific routes like /checkout/success and /checkout/cancel
Route::get('/checkout/{jobId?}', [App\Http\Controllers\CheckoutController::class, 'checkout'])->middleware(['auth', 'verified'])->name('checkout');

Route::post('/stripe/webhook', function (Request $request) {
    $payload = $request->getContent();
    $sigHeader = $request->header('Stripe-Signature');
    $webhookSecret = config('cashier.webhook.secret');

    try {
        // Verify webhook signature for security (only if secret is configured)
        if ($webhookSecret) {
            \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
            Log::info('Webhook signature verified successfully');
        } else {
            Log::warning('STRIPE_WEBHOOK_SECRET not configured - webhook signature verification skipped');
        }

        $event = json_decode($payload, true);
        Log::info('Stripe Webhook Received', [
            'type' => $event['type'] ?? 'unknown',
            'id' => $event['id'] ?? 'unknown'
        ]);

        if ($event['type'] === 'payment_intent.succeeded') {
            $paymentIntent = $event['data']['object'];
            $metadata = $paymentIntent['metadata'] ?? [];
            $jobId = $metadata['job_id'] ?? null;

            if ($jobId) {
                // Update job to published status
                $job = Job::find($jobId);
                if ($job) {
                    $job->update(['is_published' => true]);
                    Log::info("Job published via webhook", [
                        'job_id' => $jobId,
                        'job_title' => $job->title,
                        'payment_intent_id' => $paymentIntent['id']
                    ]);
                } else {
                    Log::warning("Job not found for webhook", ['job_id' => $jobId]);
                }
            } else {
                Log::warning('No job_id found in payment intent metadata', [
                    'payment_intent_id' => $paymentIntent['id']
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    } catch (\Stripe\Exception\SignatureVerificationException $e) {
        Log::error('Webhook signature verification failed', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Invalid signature'], 400);
    } catch (\Exception $e) {
        Log::error('Webhook processing failed', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Webhook processing failed'], 500);
    }
})->name('stripe.webhook');

Route::get('/ai-test', function () {
    return view('ai-test');
})->middleware(['auth'])->name('ai.test');


require __DIR__.'/auth.php';
