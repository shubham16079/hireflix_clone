<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'submission_id',
        'reviewer_id',
        'score',
        'comments',
    ];

    /**
     * Get the submission that owns the review
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get the reviewer that owns the review
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the score as a percentage
     */
    public function getScorePercentageAttribute(): float
    {
        return ($this->score / 10) * 100;
    }

    /**
     * Get the score description
     */
    public function getScoreDescriptionAttribute(): string
    {
        $descriptions = [
            1 => 'Poor',
            2 => 'Below Average',
            3 => 'Below Average',
            4 => 'Below Average',
            5 => 'Average',
            6 => 'Average',
            7 => 'Good',
            8 => 'Good',
            9 => 'Excellent',
            10 => 'Outstanding',
        ];

        return $descriptions[$this->score] ?? 'Unknown';
    }

    /**
     * Scope to filter by score range
     */
    public function scopeScoreRange($query, $min, $max)
    {
        return $query->whereBetween('score', [$min, $max]);
    }

    /**
     * Scope to filter by reviewer
     */
    public function scopeByReviewer($query, $reviewerId)
    {
        return $query->where('reviewer_id', $reviewerId);
    }
}