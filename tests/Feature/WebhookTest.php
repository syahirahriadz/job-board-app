<?php

declare(strict_types=1);

use App\Models\Job;
use App\Models\User;
use function Pest\Laravel\postJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->group('webhook');

test('webhook publishes job when payment intent succeeds', function () {
    // Create a user and unpublished job
    $user = User::factory()->create();
    $job = Job::factory()->create([
        'user_id' => $user->id,
        'is_published' => false,
    ]);

    // Simulate a Stripe webhook payload
    $webhookPayload = [
        'id' => 'evt_test_webhook',
        'type' => 'payment_intent.succeeded',
        'data' => [
            'object' => [
                'id' => 'pi_test_payment_intent',
                'metadata' => [
                    'job_id' => (string) $job->id,
                    'user_id' => (string) $user->id,
                ],
            ],
        ],
    ];

    // Send webhook request without CSRF protection
    $response = $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
                     ->postJson('/stripe/webhook', $webhookPayload);

    $response->assertOk();
    $response->assertJson(['status' => 'success']);

    // Verify job was published
    $job->refresh();
    expect($job->is_published)->toBeTrue();
});

test('webhook handles missing job_id gracefully', function () {
    // Simulate webhook without job_id in metadata
    $webhookPayload = [
        'id' => 'evt_test_webhook',
        'type' => 'payment_intent.succeeded',
        'data' => [
            'object' => [
                'id' => 'pi_test_payment_intent',
                'metadata' => [],
            ],
        ],
    ];

    $response = $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
                     ->postJson('/stripe/webhook', $webhookPayload);

    $response->assertOk();
    $response->assertJson(['status' => 'success']);
});

test('webhook handles non-existent job gracefully', function () {
    // Don't create any jobs, so any ID will be non-existent
    $nonExistentJobId = 99999;

    $webhookPayload = [
        'id' => 'evt_test_webhook',
        'type' => 'payment_intent.succeeded',
        'data' => [
            'object' => [
                'id' => 'pi_test_payment_intent',
                'metadata' => [
                    'job_id' => (string) $nonExistentJobId,
                ],
            ],
        ],
    ];

    $response = $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
                     ->postJson('/stripe/webhook', $webhookPayload);

    $response->assertOk();
    $response->assertJson(['status' => 'success']);
});

test('webhook ignores other event types', function () {
    $webhookPayload = [
        'id' => 'evt_test_webhook',
        'type' => 'customer.created', // Different event type
        'data' => [
            'object' => [
                'id' => 'cus_test_customer',
            ],
        ],
    ];

    $response = $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
                     ->postJson('/stripe/webhook', $webhookPayload);

    $response->assertOk();
    $response->assertJson(['status' => 'success']);
});
