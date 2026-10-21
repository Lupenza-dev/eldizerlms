<?php

namespace App\Models\Management;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'plan',
        'initial_deposit',
        'device_category_id',
        'user_id',
        'uuid',
        'image',
        'images',
    ];

    protected $casts = [
        'images'          => 'array',
        'price'           => 'decimal:2',
        'initial_deposit' => 'decimal:2',
        'plan'            => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function device_category()
    {
        return $this->belongsTo(DeviceCategory::class, 'device_category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
