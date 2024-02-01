<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsysResearch extends Model
{
    protected $fillable = [
        'milestone_id',
        'academic_year_id',
        'type_id',
        'student_id',
        'code',
        'status',
        'title',
        'abstract',
        'file',
        'remark',
    ];

    // Relasi dengan tabel ArsysResearchRemark
    // public function remarks()
    // {
    //     return $this->hasMany(ArsysResearchRemark::class, 'research_id');
    // }
    public function remarks(){
        return $this->hasMany(ArsysResearchRemark::class, 'research_id', 'id');
    }
    public function defenseSupervisorPresence()
    {
        return $this->hasOne(ArsysDefenseSupervisorPresence::class, 'research_id', 'id');
    }
    public function supervisor(){
        return $this->hasMany(ArsysResearchSupervisor::class, 'research_id', 'id');
    }
}
