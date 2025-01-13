<?php

namespace App\Models;

use App\Models\User;
use App\Models\Hotels;
use App\Models\HotelRoom;
use App\Models\OrderHotelPromoDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderHotelPromo extends Model
{
    use HasFactory;
    protected $fillable = [
        'orderno',
        'hotel_id',
        'room_id',
        'promo_id',
        'user_id',
        'booking_code',
        'check_in_date',
        'check_out_date',
        'total_guests',
        'total_price',
        'status',
        'additional_info',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotels::class, 'hotel_id');
    }

    public function room()
    {
        return $this->belongsTo(HotelRoom::class, 'room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function details()
    {
        return $this->hasMany(OrderHotelPromoDetail::class, 'order_id');
    }
}
