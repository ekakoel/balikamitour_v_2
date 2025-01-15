<?php

namespace App\Models;

use App\Models\Orders;
use App\Models\Transports;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AirportShuttle extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'transport_id',
        'price_id',
        'src',
        'dst',
        'duration',
        'distance',
        'price',
        'order_id',
        'order_wedding_id',
        'nav',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
    public function transport()
    {
        return $this->belongsTo(Transports::class, 'transport_id');
    }
}
