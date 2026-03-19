<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerMandate extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'customer_id',
        'loan_application_id',
        'payload',
        'mandate_reference',
        'status',
        'uuid'
    ];
}
