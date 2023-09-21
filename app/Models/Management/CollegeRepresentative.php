<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeRepresentative extends Model
{
    use HasFactory;

    protected $fillable=['name','phone_number','position','college_id','uuid'];
}
