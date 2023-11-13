<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable=['student_reg_id','user_id','college_id','image','uuid'];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function college(){
        return $this->belongsTo(College::class);
    }

    public function getStatusFormattedAttribute(){
        switch ($this->status) {
          case 1:
            $label ="<span class='badge bg-success text-white'>Active</span>";
            break;
          case 2:
            $label ="<span class='badge bg-danger text-white'>Inactive</span>";
            break;
          default:
          $label ="<span class='badge bg-success text-white'>Active</span>";
            break;
        }
    
        return $label;
    }
}
