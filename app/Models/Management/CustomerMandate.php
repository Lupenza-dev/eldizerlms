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

    public function getCustomerBankNameAttribute(){
        $response =json_decode($this->payload,true);
        return $response['payment_channel'];
    }

    public function getCustomerAccountNumberAttribute(){
        $response =json_decode($this->payload,true);
        return $response['account_number'];
    }
}
