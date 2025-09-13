<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionResponse extends Model
{
    protected $fillable = [
        'submission_id',
        'question_id',
        'response_text',
        'response_type',
        'video_url',
        'video_filename',
        'video_duration',
        'video_size',
        'video_metadata',
        'retake_count',
        'is_final',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'video_metadata' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_final' => 'boolean',
    ];

    /**
     * Get the submission that owns the response
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get the question that this response belongs to
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
