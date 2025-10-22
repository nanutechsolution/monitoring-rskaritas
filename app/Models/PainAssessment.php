<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PainAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitoring_cycle_id',
        'id_user',
        'assessment_time',
        'facial_expression',
        'cry',
        'breathing_pattern',
        'arms_movement',
        'legs_movement',
        'state_of_arousal',
        'total_score',
    ];
}
