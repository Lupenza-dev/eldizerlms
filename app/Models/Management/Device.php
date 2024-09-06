<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable =['name','price','plan','initial_deposit','device_category_id','user_id','uuid','image'];

    public function device_category(){
        return $this->hasOne(DeviceCategory::class,'id','device_category_id');
    }

}
