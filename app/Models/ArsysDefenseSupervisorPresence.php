<?php
// app/Models/ArsysDefenseSupervisorPresence.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsysDefenseSupervisorPresence extends Model
{
    protected $table = 'arsys_defense_supervisor_presence';

    protected $fillable = [
        'event_id',
        'research_supervisor_id',
        'supervisor_id',
        'research_id',
        'score',
        'remark',
    ];
    public function research()
    {
        return $this->belongsTo(ArsysResearch::class, 'research_id', 'id');
    }
    // Add any additional relationships or methods as needed
}
