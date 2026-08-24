<?php

namespace MehrdadDindar\FilamentSurveyNotifier\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyConfig extends Model
{
    protected $fillable = [
        'porsline_form_id',
        'sms_pattern_code',
        'send_after_days',
    ];
}
