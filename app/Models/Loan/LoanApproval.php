<?php

namespace App\Models\Loan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Management\Agent;
use App\Models\User;

class LoanApproval extends Model
{
    use HasFactory;

    protected $fillable=['loan_application_id','agent_id','status','remark','uuid','view_status','attended_date'];

    public function agent(){
        return $this->hasOne(User::class,'id','agent_id');
    }
}
