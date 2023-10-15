<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use Storage;
use App\Models\Entities\Gender;
use App\Models\Loan\LoanApplication;
use App\Models\Loan\LoanContract;
use App\Models\Entities\Region;
use App\Models\Entities\District;
use App\Models\Entities\Ward;
use App\Models\Entities\MaritalStatus;

class Customer extends Model
{
    use HasFactory;

    protected $fillable=['first_name','middle_name','last_name','phone_number','email','dob','gender_id','marital_status_id','region_id','district_id',
    'ward_id','street','resident_since','image','uuid','other_name','id_number'];


    public static function StoreCustomer($valid_data,$other_name){
        $id_number =$valid_data['id_number'];
        $year = substr($id_number, 0, 4);
        $month = substr($id_number, 4, 2);
        $day = substr($id_number, 6, 2);
        $code =255;
        $formattedDate = "$year-$month-$day";
        $customer =Customer::create([
            'first_name'   =>$valid_data['first_name'],
            'middle_name'  =>$valid_data['middle_name'],
            'last_name'    =>$valid_data['last_name'],
            'other_name'   =>$other_name ?? null,
            'phone_number' =>$code.''.$valid_data['phone_number'],
            'email'        =>$valid_data['email'],
            'id_number'    =>$valid_data['id_number'],
            'dob'          =>date('Y-m-d',strtotime($formattedDate)),
            'gender_id'    =>substr($valid_data['id_number'], -2, 1),
            'marital_status_id'    =>$valid_data['marital_status_id'] ?? null,
            'region_id'            =>$valid_data['region_id'],
            'district_id'          =>$valid_data['district_id'],
            'ward_id'                  =>$valid_data['ward_id'],
            'street'                   =>$valid_data['street'],
            'resident_since'           =>$valid_data['resident_since'] ?? null,
            'other_name'               =>$other_name ?? null,
            'uuid'                     =>(string)Str::orderedUuid()
        ]);

        return $customer;


    }

    public function formatDate($inputString) {
        $year = substr($inputString, 0, 4);
        $month = substr($inputString, 4, 2);
        $day = substr($inputString, 6, 2);
    
        $formattedDate = "$year-$month-$day";
        return $formattedDate;
    }

    public function marital_status(){
        return $this->belongsTo(MaritalStatus::class);
    }

    public function gender(){
        return $this->belongsTo(Gender::class);
    }

    public function initiated_loan_application(){
        return $this->hasMany(LoanApplication::class)->where('level','Application');
    }

    public function region(){
        return $this->belongsTo(Region::class);
    }

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function ward(){
        return $this->belongsTo(Ward::class);
    }

    public function outstanding_loan(){
        return $this->hasOne(LoanContract::class)->where('status','GRANTED');
    }

    public function student(){
        return $this->hasOne(Student::class,'customer_id','id');
    }

    public function loan_contracts(){
        return $this->hasMany(LoanContract::class,'customer_id','id')->where('status','GRANTED')->latest();
    }

    public function scopeWithFilters($query,$request){
        
        $phone_number    =$request['phone_number'] ?? null;
        $start_date      =$request['start_date'] ?? null;
        $end_date        =$request['end_date'] ?? null;
        $id_number       =$request['id_number'] ?? null;
        $region_id       =$request['region_id'] ?? null;
        $gender_id       =$request['gender_id'] ?? null;
        
        return $query->when($phone_number,function($query) use ($phone_number){
                $query->where('phone_number','like','%'.$phone_number.'%');
              })
              ->when($start_date,function($query) use ($start_date,$end_date){
                if ($start_date != null || $end_date != null) {
                    if ($start_date != null && $end_date != null)
                        $query->whereBetween('created_at', [$start_date, $end_date]);
        
                    else if ($start_date != null)
                        $query->where('created_at', '>=', $start_date);
        
                    else if ($end_date != null)
                        $query->where('created_at', '<=', $end_date);
                }
                })
                ->when($id_number,function($query) use ($id_number){
                    $query->where('id_number',$id_number);
                })
                ->when($region_id,function($query) use ($region_id){
                    $query->where('region_id',$region_id);
                })
                ->when($gender_id,function($query) use ($gender_id){
                    $query->where('gender_id',$gender_id);
                });
    }
}
