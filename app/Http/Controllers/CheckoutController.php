<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Cashier;

class CheckoutController extends Controller
{
    public function checkout($jobId = null): \Illuminate\Http\RedirectResponse
    {
        // If no job ID provided, check session for pending job
        if (! $jobId) {
            $jobId = session('pending_job_id');
        }

        // If a specific job ID is available, validate and store it in session
        if ($jobId) {
            $job = \App\Models\Job::find($jobId);
            if ($job && $job->user_id === Auth::id() && ! $job->is_published) {
                session(['pending_job_id' => $jobId]);
            } else {
                // Invalid job ID, clear session and redirect back
                session()->forget('pending_job_id');

                return redirect()->route('jobs.index')->with('error', 'Invalid job or job already published.');
            }
        }

        $stripedPriceID = 'price_1SI91mR4sgxTSvRROWaBqM12';
        $quantity = 1;

        return request()->user()->checkout([$stripedPriceID => $quantity], [
            'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            'automatic_tax' => ['enabled' => false],
            'billing_address_collection' => 'auto',
            'metadata' => [
                'order_id' => Auth::id().'_'.time(),
                'job_id' => $jobId,
                'user_id' => Auth::id(),
                'return_url' => route('jobs.index'),
            ],
            'custom_text' => [
                'submit' => [
                    'message' => 'Complete payment to publish your job listing instantly!',
                ],
            ],
        ])->redirect();
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if ($sessionId) {
            try {
                $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);

                // Validate that this is actually a successful payment
                if ($session->payment_status === 'paid' && $session->mode === 'payment') {
                    // Get user ID from session metadata instead of auth
                    $userId = $session->metadata->user_id ?? null;
                    $jobId = $session->metadata->job_id ?? null;

                    // Log successful payment
                    Log::info('Payment successful', [
                        'session_id' => $sessionId,
                        'customer_email' => $session->customer_details->email ?? 'N/A',
                        'amount_total' => $session->amount_total,
                        'currency' => $session->currency,
                        'user_id' => $userId,
                        'job_id' => $jobId,
                    ]);

                    // Update user's payment status using metadata
                    if ($userId) {
                        $user = User::find($userId);
                        if ($user) {
                            $user->update([
                                'has_paid' => true,
                                'last_payment_date' => now(),
                            ]);

                            // Publish the specific job that was paid for
                            if ($jobId) {
                                $job = Job::find($jobId);
                                if ($job && $job->user_id === $user->id && ! $job->is_published) {

                                    // Check if payment already exists for this session
                                    $existingPayment = Payment::where('stripe_session_id', $sessionId)->first();
                                    if ($existingPayment) {
                                        Log::warning('Duplicate payment attempt', [
                                            'session_id' => $sessionId,
                                            'existing_payment_id' => $existingPayment->id,
                                        ]);
                                        return view('checkout.success')->with('success', 'Payment already processed! Your job is published.');
                                    }

                                    try {
                                        // Create payment record FIRST
                                        $payment = Payment::create([
                                            'job_id' => $job->id,
                                            'user_id' => $user->id,
                                            'stripe_session_id' => $sessionId,
                                            'stripe_payment_intent_id' => $session->payment_intent,
                                            'amount' => $session->amount_total,
                                            'currency' => $session->currency,
                                            'status' => 'completed',
                                            'metadata' => [
                                                'customer_email' => $session->customer_details->email ?? null,
                                                'payment_method_types' => $session->payment_method_types,
                                                'mode' => $session->mode,
                                            ],
                                            'paid_at' => now(),
                                        ]);

                                        // Only publish job AFTER payment record is successfully created
                                        $job->update(['is_published' => true]);

                                        Log::info('Job published after payment', [
                                            'job_id' => $job->id,
                                            'job_title' => $job->title,
                                            'user_id' => $user->id,
                                            'payment_id' => $payment->id,
                                            'payment_amount' => $session->amount_total,
                                        ]);

                                        // Clear the pending job session
                                        session()->forget('pending_job_id');

                                    } catch (\Exception $e) {
                                        Log::error('Payment record creation failed, job not published', [
                                            'job_id' => $job->id,
                                            'user_id' => $user->id,
                                            'session_id' => $sessionId,
                                            'error' => $e->getMessage(),
                                        ]);

                                        // Don't publish the job if payment record creation fails
                                        return view('checkout.cancel')->with('error', 'Payment processing failed. Please contact support.');
                                    }
                                }
                            }
                        }
                    }

                    // Show success page
                    return view('checkout.success')->with('success', 'Payment completed successfully! Your job has been published.');

                } else {
                    Log::warning('Payment not completed', [
                        'session_id' => $sessionId,
                        'payment_status' => $session->payment_status,
                    ]);

                    // Show cancel page
                    return view('checkout.cancel');
                }
            } catch (\Exception $e) {
                Log::error('Error retrieving checkout session', [
                    'session_id' => $sessionId,
                    'error' => $e->getMessage(),
                ]);

                // Show cancel page with error
                return view('checkout.cancel');
            }
        }

        // No session ID provided, show cancel page
        return view('checkout.cancel');
    }

    public function cancel()
    {
        // Get pending job ID to log what was cancelled
        $pendingJobId = session('pending_job_id');

        // Log the cancellation (user might not be authenticated when coming from Stripe)
        Log::info('Checkout cancelled', [
            'user_id' => Auth::id() ?? 'unknown',
            'pending_job_id' => $pendingJobId,
            'timestamp' => now(),
            'user_agent' => request()->userAgent(),
            'referrer' => request()->header('referer'),
        ]);

        // Show cancel page
        return view('checkout.cancel');
    }
}
