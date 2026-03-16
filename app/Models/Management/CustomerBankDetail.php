<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'bank_name',
        'account_number',
        'uuid'
    ];
}
