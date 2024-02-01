<?php

namespace App;
use App\Controller\ArsysResearchController;
use Illuminate\Database\Eloquent\Model;

class ArsysResearchSupervisor extends Model
{
    protected $table = 'arsys_research_supervisor';
    protected $primaryKey = 'id';
    public $timestamps = true;

    // Kolom-kolom yang dapat diisi
    protected $fillable = [
        'research_id',
        'supervisor_id',
        'order',
        'bypass',
    ];

    public function staff(){
        return $this->belongsTo(ArsysStaff::class, 'supervisor_id','id' );
    }
    public function research(){
        return $this->belongsTo(ArsysResearch::class, 'research_id', 'id');
    }
}
