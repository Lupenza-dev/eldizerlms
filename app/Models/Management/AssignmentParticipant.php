<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentParticipant extends Model
{
    use HasFactory,SoftDeletes;

    public $fillable =['assignment_id','user_id','score_gained','total_questions','answers'];

}
