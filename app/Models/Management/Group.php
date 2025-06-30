<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Group extends Model implements HasMedia
{
    use HasFactory,SoftDeletes,InteractsWithMedia;

    protected $fillable =['name','link','user_id','college_id','uuid'];

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

    public function college(){
        return $this->belongsTo(College::class);
    }
}
