<?php

namespace App\Models;

use App\Models\InvoiceAdmin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentConfirmation extends Model
{
    use HasFactory;
    protected $fillable=[
        'receipt_img',
        'inv_id',
        'payment_date',
        'kurs_name',
        'kurs_rate',
        'amount',
        'note',
        'status',
    ];
    public function invoice(){
        return $this->belongsTo(InvoiceAdmin::class,'inv_id');
    }
}
