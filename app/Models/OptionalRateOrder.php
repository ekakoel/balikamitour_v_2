<?php

namespace App\Models;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OptionalRateOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'rsv_id',
        'order_id',
        'optional_rate_id',
        'number_of_guest',
        'service_date',
        'price_pax',
        'price_total',
    ];
    public function reservation(){
        return $this->belongsTo(Reservation::class,'rsv_id');
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
