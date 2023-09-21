<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Agent extends Model
{
    use HasFactory;

    protected $fillable=['student_reg_id','user_id','college_id','image','uuid'];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function college(){
        return $this->belongsTo(College::class);
    }
}
