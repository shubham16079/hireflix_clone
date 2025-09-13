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
        // Drop all tables that reference submissions in the correct order
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('submission_responses');
        Schema::dropIfExists('submissions');
        
        // Create the new submissions table with all the enhanced fields
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained()->onDelete('cascade');
            $table->string('candidate_name');
            $table->string('candidate_email');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'abandoned'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            
            // Additional fields for better tracking
            $table->timestamp('started_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->integer('total_questions')->default(0);
            $table->integer('completed_questions')->default(0);
            $table->json('metadata')->nullable(); // For storing additional data like browser info, etc.
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['interview_id', 'candidate_email']);
            $table->index('status');
            $table->index('submitted_at');
        });
        
        // Recreate the submission_responses table with all the video support fields
        Schema::create('submission_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->text('response_text');
            $table->string('response_type')->default('text'); // text, video, audio
            
            // Video-specific fields
            $table->string('video_url')->nullable();
            $table->string('video_filename')->nullable();
            $table->integer('video_duration')->nullable(); // in seconds
            $table->integer('video_size')->nullable(); // in bytes
            $table->json('video_metadata')->nullable(); // resolution, format, etc.
            
            // Retake functionality
            $table->integer('retake_count')->default(0);
            $table->boolean('is_final')->default(true);
            
            // Progress tracking
            $table->enum('status', ['draft', 'recording', 'completed', 'retake_requested'])->default('draft');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
        });
        
        // Recreate the reviews table with the same structure
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->integer('score')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->unique(['submission_id', 'reviewer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('submission_responses');
        Schema::dropIfExists('submissions');
    }
};