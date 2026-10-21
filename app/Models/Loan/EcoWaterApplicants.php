<?php

namespace App\Models\Loan;

use App\Models\Management\EcoWaterCampus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcoWaterApplicants extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'loan_application_id',
        'eco_water_campus_id',
        'remarks',
        'registration_code',
    ];
    
    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }
    
    public function ecoWaterCampus()
    {
        return $this->belongsTo(EcoWaterCampus::class, 'eco_water_campus_id');
    }
}

