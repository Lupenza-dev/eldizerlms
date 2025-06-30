<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentQuestion extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable =['name','choices','correct_answer','assignment_id','user_id','uuid','assignment_id'];

    public function getStatusFormattedAttribute(){
        switch ($this->status) {
          case 1:
            $label ="<span class='badge bg-success text-white'>Active</span>";
            break;
          default:
          $label ="<span class='badge bg-danger text-white'>Not Active</span>";
            break;
        }
    
        return $label;
    }
}
