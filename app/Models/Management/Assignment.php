<?php

namespace App\Models\Management;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Assignment extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,SoftDeletes;

    protected $fillable = ['name','start_time','end_time','total_questions','uuid','college_id','user_id'];

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

    public function questions(){
      return $this->hasMany(AssignmentQuestion::class);
    }

    public function getProgressFormatAttribute(){
      $now = now(); 
      $start = \Carbon\Carbon::parse($this->start_time);
      $end = \Carbon\Carbon::parse($this->end_time);
  
      if ($now->lt($start)) {
          return 'Not Started';
      } elseif ($now->between($start, $end)) {
          return 'On Progress';
      } else {
          return 'Completed';
      }
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'assignment_participants', 'assignment_id', 'user_id');
    }
}
