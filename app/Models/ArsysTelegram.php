<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsysTelegram extends Model
{
    protected $table = 'arsys_telegram';

    protected $fillable = [
        'research_id',
        'staff_id',
        'supervisor_id',
        'chat_id',
        'message_id',
        'message_text',
        'created_at',
        'updated_at',
    ];

    public function research()
    {
        return $this->belongsTo(ArsysResearch::class, 'research_id', 'id');
    }

    public function staff()
    {
        return $this->belongsTo(ArsysStaff::class, 'staff_id', 'id');
    }

    public function supervisor()
    {
        return $this->belongsTo(ArsysResearchSupervisor::class, 'supervisor_id', 'id');
    }
}
