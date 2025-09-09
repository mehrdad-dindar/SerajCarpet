<?php

namespace MehrdadDindar\FilamentPorsline\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'porsline_response_id',
        'responder_id',
        'responder_email',
        'responder_phone',
        'responder_name',
        'start_time',
        'submit_time',
        'last_edit_time',
        'score',
        'data',
        'is_complete',
        'is_spam',
    ];

    protected $casts = [
        'data' => 'array',
        'start_time' => 'datetime',
        'submit_time' => 'datetime',
        'last_edit_time' => 'datetime',
        'is_complete' => 'boolean',
        'is_spam' => 'boolean',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function getDurationAttribute(): ?string
    {
        if (!$this->start_time || !$this->submit_time) {
            return null;
        }

        $duration = $this->submit_time->diffInSeconds($this->start_time);
        return gmdate('H:i:s', $duration);
    }

    public function scopeComplete($query)
    {
        return $query->where('is_complete', true);
    }

    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }
} 