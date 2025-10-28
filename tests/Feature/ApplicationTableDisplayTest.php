<?php

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Livewire\Livewire;

test('admin can see applicant column in application table', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employer = User::factory()->create(['role' => 'employer']);
    $job = Job::factory()->create(['user_id' => $employer->id]);
    $application = JobApplication::factory()->create([
        'job_id' => $job->id,
        'full_name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    $this->actingAs($admin);

    Livewire::test(\App\Livewire\ApplicationTable::class)
        ->assertSee('Applicant') // Header
        ->assertSee('John Doe') // Applicant name
        ->assertSee('john@example.com'); // Applicant email
});

test('employer can see applicant column for their job applications', function () {
    $employer = User::factory()->create(['role' => 'employer']);
    $job = Job::factory()->create(['user_id' => $employer->id]);
    $application = JobApplication::factory()->create([
        'job_id' => $job->id,
        'full_name' => 'Jane Smith',
        'email' => 'jane@example.com',
    ]);

    $this->actingAs($employer);

    Livewire::test(\App\Livewire\ApplicationTable::class)
        ->assertSee('Applicant') // Header
        ->assertSee('Jane Smith') // Applicant name
        ->assertSee('jane@example.com'); // Applicant email
});

test('guest cannot see applicant column in application table', function () {
    $guest = User::factory()->create(['role' => 'guest']);
    $employer = User::factory()->create(['role' => 'employer']);
    $job = Job::factory()->create(['user_id' => $employer->id]);
    $application = JobApplication::factory()->create([
        'job_id' => $job->id,
        'user_id' => $guest->id,
        'email' => $guest->email,
        'full_name' => 'Guest User',
    ]);

    $this->actingAs($guest);

    Livewire::test(\App\Livewire\ApplicationTable::class)
        ->assertDontSee('Applicant') // Should not see header
        ->assertSee($job->title); // But should see job title
});
