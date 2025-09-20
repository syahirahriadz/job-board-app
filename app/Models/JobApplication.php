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
    ];

    protected $casts = [
        'available_start_date' => 'date',
        'willing_to_relocate' => 'boolean',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
