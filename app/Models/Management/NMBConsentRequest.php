<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NMBConsentRequest extends Model
{
    use HasFactory,SoftDeletes;

    public $table ='nmb_consent_requests';

    public $fillable =['consent_type','nmb_subscriber_id','consent_request_id',
   'from_account_bank_scheme','from_bank_id','from_account_scheme','from_account_number','to_account_counterparty_name','to_account_bank_scheme',
   'to_account_bank_scheme','to_bank_id','to_account_scheme','to_account_number',
    'currency','max_single_amount','max_monthly_amount','max_number_of_monthly_transactions','max_yearly_amount',
'max_number_of_yearly_transactions','max_total_amount','max_number_of_transactions','valid_from','time_to_live',
'email','phone_number','consumer_id','uuid'];





}
