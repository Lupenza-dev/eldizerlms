<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;

class AgentCollege extends Model
{
    public $table ='agent_college';
    protected $fillable =['agent_id','college_id'];

    public function agent(){
        return $this->hasOne(Agent::class,'id','agent_id');
    }
}
