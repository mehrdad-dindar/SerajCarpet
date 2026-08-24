<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Comment extends Model implements HasMedia
{
    use InteractsWithMedia, softDeletes;

    protected $guarded;

    public function commentable()
    {
        return $this->morphTo();
    }

    public function commenter()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('voice_notes')
            ->acceptsMimeTypes(['audio/mpeg', 'audio/wav', 'audio/x-wav', 'audio/ogg'])
            ->useDisk('voice_notes');

        $this->addMediaCollection('attachments')
            ->useDisk('attachments');
    }

    public function voiceNote(): MorphMany
    {
        return $this->media()->where('collection_name', 'voice_notes');
    }
}
