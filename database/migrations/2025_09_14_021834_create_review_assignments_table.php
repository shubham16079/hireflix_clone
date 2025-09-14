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
        Schema::create('review_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['assigned', 'accepted', 'declined'])->default('assigned');
            $table->text('message')->nullable(); // Custom message from assigner
            $table->timestamp('assigned_at');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
            
            // Ensure a reviewer can only be assigned once per interview
            $table->unique(['interview_id', 'reviewer_id']);
            
            // Indexes for better performance
            $table->index(['reviewer_id', 'status']);
            $table->index(['interview_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_assignments');
    }
};