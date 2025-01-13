<?php

namespace App\Models;

use App\Models\Tours;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TourPrices extends Model
{
    use HasFactory;
    protected $fillable=[
        'tours_id',
        'min_qty',
        'max_qty',
        'contract_rate',
        'markup',
        'expired_date',
        'status',
    ];

    public function tours(){
        return $this->belongsTo(Tours::class,'tours_id');
    }
}
