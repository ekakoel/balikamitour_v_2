<?php

namespace App\Models;

use App\Models\Orders;
use App\Models\WeddingPlannerTransport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Drivers extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'license',
        'address',
        'country',
    ];
    public function reservation(){
        return $this->belongsToMany(Reservation::class,'rsv_id');
    }
    public function wp_transports(){
        return $this->hasMany(WeddingPlannerTransport::class,'driver_id');
    }
    public function orders()
    {
        return $this->hasMany(Orders::class, 'driver_id');
    }
}
