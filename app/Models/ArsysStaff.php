<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsysStaff extends Model
{
    protected $table = 'arsys_staff';

    protected $fillable = [
        'user_id',
        'sso',
        'code',
        'univ_code',
        'employee_id',
        'old_employee_id',
        'front_title',
        'rear_title',
        'first_name',
        'last_name',
        'staff_type_id',
        'status_id',
        'position_id',
        'structure_id',
        'specialization_id',
        'program_id',
        'phone',
        'email',
    ];

    public function researches()
    {
        return $this->hasMany(ArsysResearch::class, 'student_id', 'id');
    }

    public function researchSupervisors()
    {
        return $this->hasMany(ArsysResearchSupervisor::class, 'staff_id', 'id');
    }

    public function telegrams()
    {
        return $this->hasMany(ArsysTelegram::class, 'staff_id', 'id');
    }

    // Tambahkan relasi jika diperlukan
    public function firstSPV()
    {
        return $this->hasMany(ArsysResearchSupervisor::class, 'supervisor_id', 'id')->where('order', 1);
    }
}
