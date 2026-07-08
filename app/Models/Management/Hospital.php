<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'short_name',
        'region_id',
        'district_id',
        'status',
        'created_by',
    ];

    public function contactPerson(){
        return $this->hasOne(HospitalContactPerson::class,'hospital_id','id');
    }

    public function region(){
        return $this->belongsTo(\App\Models\Entities\Region::class,'region_id','id');
    }

    public function district(){
        return $this->belongsTo(\App\Models\Entities\District::class,'district_id','id');
    }
}
