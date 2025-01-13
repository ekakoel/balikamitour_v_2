<?php

namespace App\Models;

use App\Models\Hotels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OptionalRate extends Model
{
    use HasFactory;
    protected $fillable = [
        'hotels_id',
        'name',
        'service',
        'service_id',
        'type',
        'contract_rate',
        'markup',
        'description',
    ];
    public function hotels(){
        return $this->belongsTo(Hotels::class,'hotels_id');
    }
    public function calculatePrice($usdrates, $tax)
    {
        $contractRate = $this->contract_rate;
        $contractRateUsd = (ceil($contractRate / $usdrates->rate)) + $this->markup;
        $rateTax = ceil($contractRateUsd * ($tax->tax / 100));
        return $contractRateUsd + $rateTax;
    }
}
