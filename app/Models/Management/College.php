<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class College extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable=['name','location','logo','created_by','uuid'];

    public function representative(){
        return $this->hasOne(CollegeRepresentative::class);
    }

    public function getStatusFormattedAttribute(){
        switch ($this->status) {
          case 'active':
            $label ="<span class='badge bg-success text-white'>Active</span>";
            break;
          case 'Inactive':
            $label ="<span class='badge bg-danger text-white'>Inactive</span>";
            break;
          default:
          $label ="<span class='badge bg-success text-white'>Active</span>";
            break;
        }
    
        return $label;
    }

    public function getCollegeRepresentativeAttribute(){
        $name =$this->representative?->name." </br>".$this->representative?->phone_number."</br>".$this->representative?->position;
        return $name;
    }
} 
