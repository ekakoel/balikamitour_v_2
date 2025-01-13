<?php

namespace App\Models;

use App\Models\Hotels;
use App\Models\HotelRoom;
use App\Models\OrderHotelPromo;
use App\Models\OrderHotelPromoDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelPromo extends Model
{
    use HasFactory;
    protected $fillable = [
        'promotion_type',
        'quotes',
        'hotels_id',
        'rooms_id',
        'name',
        'book_periode_start',
        'book_periode_end',
        'periode_start',
        'periode_end',
        'contract_rate',
        'minimum_stay',
        'markup',
        'booking_code',
        'benefits',
        'email_status',
        'send_to_specific_email',
        'specific_email',
        'status',
        'include',
        'author',
        'additional_info',
    ];

    public function hotels(){
        return $this->belongsTo(Hotels::class,'hotels_id');
    }

    public function rooms(){
        return $this->belongsTo(HotelRoom::class,'rooms_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderHotelPromoDetail::class, 'promo_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeValidForBooking($query, $now)
    {
        return $query->where('book_periode_start', '<=', $now)
                    ->where('book_periode_end', '>=', $now);
    }
    public function scopeValidForStay($query, $checkin)
    {
        return $query->where('periode_start', '<=', $checkin)
                    ->where('periode_end', '>=', $checkin);
    }
    public function calculatePrice($usdrates, $tax)
    {
        $promo_c_rate = $this->contract_rate;
        $promo_usd = (ceil($promo_c_rate / $usdrates->rate)) + $this->markup;
        $promo_tax = ceil($promo_usd * ($tax->tax / 100));
        return $promo_usd + $promo_tax;
    }
}
