<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalContactPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'hospital_id',
        'name',
        'email',
        'phone_number',
    ];

    public function hospital(){
        return $this->belongsTo(Hospital::class,'hospital_id','id');
    }
}
