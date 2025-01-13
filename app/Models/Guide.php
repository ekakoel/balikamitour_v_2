<?php

namespace App\Models;

use App\Models\Orders;
use App\Models\Reservation;
use App\Models\WeddingPlannerTransport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guide extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'sex',
        'language',
        'phone',
        'email',
        'address',
        'country',
    ];
    public function reservation(){
        return $this->belongsToMany(Reservation::class,'guide_id');
    }
    public function wp_transports(){
        return $this->hasMany(WeddingPlannerTransport::class,'guide_id');
    }
    public function orders()
    {
        return $this->hasMany(Orders::class, 'guide_id');
    }
}
