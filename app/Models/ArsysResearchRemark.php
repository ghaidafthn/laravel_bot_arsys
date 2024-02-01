<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsysResearchRemark extends Model
{
    protected $fillable = [
        'research_id',
        'discussant_id',
        'message',
    ];

    // Relasi dengan tabel ArsysResearch
    public function research()
    {
        return $this->belongsTo(ArsysResearch::class, 'research_id');
    }

    // Relasi dengan tabel ArsysStaff (jika discussant diambil dari tabel staff)
    public function discussant()
    {
        return $this->belongsTo(User::class, 'discussant_id', 'id');
    }
}
