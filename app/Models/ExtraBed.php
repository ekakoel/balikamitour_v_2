<?php

namespace App\Models;

use App\Models\Hotels;
use App\Models\HotelRoom;
use App\Models\WeddingAccomodations;
use App\Models\OrderHotelPromoDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExtraBed extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'hotels_id',
        'type',
        'max_age',
        'min_age',
        'description',
        'contract_rate',
        'markup',
    ];
    public function hotels(){
        return $this->belongsTo(Hotels::class,'hotels_id');
    }
    public function rooms(){
        return $this->belongsTo(HotelRoom::class,'rooms_id');
    }
    public function wedding_accommodations(){
        return $this->hasMany(WeddingAccomodations::class,'extra_bed_id');
    }
    public function order_hotel_promo_details(){
        return $this->hasMany(OrderHotelPromoDetail::class,'extra_bed_id');
    }
    public function calculatePrice($usdrates, $tax)
    {
        $contract_rate = $this->contract_rate;
        $contract_rate_usd = (ceil($contract_rate / $usdrates->rate)) + $this->markup;
        $service_tax = ceil($contract_rate_usd * ($tax->tax / 100));
        return $contract_rate_usd + $service_tax;
    }
}
