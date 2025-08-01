<?php

namespace MehrdadDindar\FilamentSurveyNotifier\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyLink extends Model
{
    protected $fillable = [
        'order_id',
        'mobile',
        'link',
        'sent_at',
    ];

    protected array $dates = ['sent_at'];
}
