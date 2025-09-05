<?php

namespace MehrdadDindar\FilamentPorsline\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    protected $guarded = [];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'created_date' => 'datetime',
    ];

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function getSurveyUrlAttribute(): string
    {
        return "https://survey.porsline.ir/n/survey/{$this->porsline_id}/build/";
    }

    public function getReportUrlAttribute(): string
    {
        return "https://survey.porsline.ir/n/survey/{$this->porsline_id}/results/metrics/";
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', 1);
    }

    public function scopeNotStopped($query)
    {
        return $query->where('is_stopped', false);
    }
}
