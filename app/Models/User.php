<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Loan\LoanContract;
use App\Models\Management\Agent;
use App\Models\Management\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'uuid',
        'phone_number',
        'customer_id',
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function loan_contracts(){
        return $this->hasMany(LoanContract::class,'customer_id','customer_id')->where('status','GRANTED')->latest();
    }

    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function agent(){
        return $this->hasOne(Agent::class);
    }

    public function getStatusFormattedAttribute(){
        switch ($this->active) {
          case 1:
            $label ="<span class='badge bg-success text-white'>Active</span>";
            break;
          case 2:
            $label ="<span class='badge bg-danger text-white'>Inactive</span>";
            break;
          default:
          $label ="<span class='badge bg-success text-white'>Active</span>";
            break;
        }
    
        return $label;
    }
}
