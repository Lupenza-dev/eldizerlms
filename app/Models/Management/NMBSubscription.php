<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NMBSubscription extends Model
{
    use HasFactory,SoftDeletes;

    public $table='nmb_subscriptions';
    public $fillable=['nmb_account','nmb_username','nmb_password','token','uuid'];

    public function consent_request(){
        return $this->hasOne(NMBConsentRequest::class,'nmb_subscriber_id','id')->latest();
    }
}
