<?php

use App\Livewire\JobList;
use App\Livewire\JobTable;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\UserList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Livewire\ApplicationTable;

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
});

require __DIR__.'/auth.php';
