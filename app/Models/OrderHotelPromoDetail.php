<?php

namespace App\Models;

use App\Models\ExtraBed;
use App\Models\HotelPromo;
use App\Models\OrderHotelPromo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderHotelPromoDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'promo_id',
        'extra_bed_id',
        'room_number',
        'special_event',
        'special_event_date',
        'number_of_guests',
        'guest_name',
        'benefits',
        'include',
        'additional_info',
        'cancellation_policy',
        'extra_bed_price',
        'promo_price',
        'total_price',
    ];

    public function order()
    {
        return $this->belongsTo(OrderHotelPromo::class, 'order_id');
    }
    public function extra_bed()
    {
        return $this->belongsTo(ExtraBed::class, 'extra_bed_id');
    }
}
