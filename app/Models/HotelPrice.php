<?php

namespace App\Models;

use App\Models\Hotels;
use App\Models\HotelRoom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'hotels_id',
        'rooms_id',
        'start_date',
        'end_date',
        'markup',
        'contract_rate',
        'kick_back',
        'author',
    ];

    public function hotels(){
        return $this->belongsTo(Hotels::class,'hotels_id');
    }

    public function rooms(){
        return $this->belongsTo(HotelRoom::class,'rooms_id');
    }

    public function calculatePrice($usdrates, $tax)
    {
        $room_c_rate = $this->contract_rate;
        $room_usd = (ceil($room_c_rate / $usdrates->rate)) + $this->markup;
        $room_tax = ceil($room_usd * ($tax->tax / 100));
        return $room_usd + $room_tax;
    }
}
