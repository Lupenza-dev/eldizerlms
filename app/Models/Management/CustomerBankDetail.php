<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerBankDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'customer_id',
        'bank_name',
        'account_number',
        'uuid'
    ];
}
