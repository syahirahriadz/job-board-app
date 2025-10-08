<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'job_id',
        'full_name',
        'email',
        'phone_number',
        'resume_path',
        'cover_letter',
        'cover_letter_path',
        'linkedin_url',
        'portfolio_url',
        'why_interested',
        'expected_salary',
        'available_start_date',
        'willing_to_relocate',
        'status',
    ];

    protected $casts = [
        'available_start_date' => 'date',
        'willing_to_relocate' => 'boolean',
    ];

    /**
     * Get the possible status values
     */
    public static function statuses(): array
    {
        return ['pending', 'approved', 'rejected'];
    }

    /**
     * Get the status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the formatted status text
     */
    public function getStatusText(): string
    {
        return ucfirst($this->status ?? 'pending');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
