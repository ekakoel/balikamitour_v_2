<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use App\Models\Category;
use App\Models\Partners;
use App\Models\TourPrices;
use App\Models\ToursImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tours extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'partners_id',
        'destinations',
        'location',
        'code',
        'type', 
        'duration',
        'description',
        'include',
        'itinerary',
        'additional_info', 
        'cancellation_policy',
        'contract_rate',
        'markup',
        'qty',
        'status',
        'author_id',
        'cover',
    ];

    public function images(){
        return $this->hasMany(ToursImages::class);
    }
    public function prices(){
        return $this->hasMany(TourPrices::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function partners()
    {
        return $this->belongsTo(Partners::class,'partners_id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->select(['name as text','id']);
    }


}
