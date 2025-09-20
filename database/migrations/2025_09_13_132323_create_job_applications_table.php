<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');

            // Personal Information
            $table->string('full_name');
            $table->string('email');
            $table->string('phone_number');

            // Professional Information
            $table->string('resume_path')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('cover_letter_path')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();

            // Application-specific
            $table->text('why_interested');
            $table->string('expected_salary')->nullable();
            $table->date('available_start_date');
            $table->boolean('willing_to_relocate')->default(false);

            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('job_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
