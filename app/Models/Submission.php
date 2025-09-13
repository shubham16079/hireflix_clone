<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    protected $fillable = [
        'interview_id',
        'candidate_name',
        'candidate_email',
        'status',
        'submitted_at',
        'started_at',
        'last_activity_at',
        'total_questions',
        'completed_questions',
        'metadata',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the interview that owns the submission
     */
    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    /**
     * Get the responses for the submission
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SubmissionResponse::class);
    }

    /**
     * Get the reviews for the submission
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the completion percentage
     */
    public function getCompletionPercentageAttribute(): int
    {
        if ($this->total_questions === 0) {
            return 0;
        }
        
        return round(($this->completed_questions / $this->total_questions) * 100);
    }

    /**
     * Check if submission is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if submission is in progress
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Update progress
     */
    public function updateProgress(): void
    {
        $this->completed_questions = $this->responses()
            ->where('status', 'completed')
            ->where('is_final', true)
            ->count();
        
        $this->last_activity_at = now();
        $this->save();
    }

    /**
     * Mark as started
     */
    public function markAsStarted(): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'submitted_at' => now(),
            'last_activity_at' => now(),
        ]);
    }
}
