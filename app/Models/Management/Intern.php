<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intern extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable =['hospital_id','customer_id','start_date','end_date','letter','professional','uuid'];

    public function hospital(){
        return $this->hasOne(Hospital::class,'id','hospital_id');
    }
}
