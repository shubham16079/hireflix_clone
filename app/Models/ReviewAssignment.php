<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewAssignment extends Model
{
    protected $fillable = [
        'interview_id',
        'reviewer_id',
        'assigned_by',
        'status',
        'message',
        'assigned_at',
        'responded_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Get the interview that this assignment belongs to
     */
    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    /**
     * Get the reviewer that this assignment belongs to
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the user who assigned this review
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by reviewer
     */
    public function scopeForReviewer($query, $reviewerId)
    {
        return $query->where('reviewer_id', $reviewerId);
    }

    /**
     * Scope to filter by interview
     */
    public function scopeForInterview($query, $interviewId)
    {
        return $query->where('interview_id', $interviewId);
    }

    /**
     * Check if assignment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'assigned';
    }

    /**
     * Check if assignment is accepted
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if assignment is declined
     */
    public function isDeclined(): bool
    {
        return $this->status === 'declined';
    }
}