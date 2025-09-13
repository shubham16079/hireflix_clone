<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'created_by',
        'allow_retakes',
        'max_retakes_per_question',
        'max_video_duration',
        'preparation_time',
        'show_timer',
        'allow_pause',
        'sequential_questions',
        'allow_skip',
        'show_progress',
        'allowed_video_formats',
        'max_video_size',
        'video_quality',
    ];

    protected $casts = [
        'allow_retakes' => 'boolean',
        'show_timer' => 'boolean',
        'allow_pause' => 'boolean',
        'sequential_questions' => 'boolean',
        'allow_skip' => 'boolean',
        'show_progress' => 'boolean',
        'allowed_video_formats' => 'array',
    ];

    /**
     * Get the user that owns the interview.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the questions for the interview.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the submissions for the interview.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
