<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsdRates extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'rate',
        'sell',
        'buy',
        'difference',
    ];
}
