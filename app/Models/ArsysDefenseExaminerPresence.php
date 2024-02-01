<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsysDefenseExaminerPresence extends Model
{
    protected $table = 'arsys_defense_examiner_presence';

    protected $fillable = [
        'event_id',
        'defense_examiner_id',
        'examiner_id',
        'score',
        'remark',
    ];

    
}
