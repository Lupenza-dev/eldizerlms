<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcoWaterCampus extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'campus_name',
        'campus_code',
    ];
}
