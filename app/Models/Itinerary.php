<?php

namespace App\Models;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Itinerary extends Model
{
    use HasFactory;
    protected $fillable = [
        'author_id',
        'rsv_id',
        'order_id',
        'date',
        'day',
        'time',
        'duration',
        'itinerary',
    ];
    public function reservation(){
        return $this->belongsTo(Reservation::class,'rsv_id');
    }
}
