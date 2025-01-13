<?php
namespace App\Mail;
use App\Models\User;
use App\Models\Hotels;
use App\Models\HotelPromo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
class HotelPromoSpecificMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookingcode;
    public $user;
    public $hotel;
    public $promo;
    public $link;
    public $title;
    public $suggestion;

    public function __construct(string $bookingcode, string $user, Hotels $hotel, HotelPromo $promo, string $link, string $title, string $suggestion)
    {
        $this->bookingcode = $bookingcode;
        $this->user = $user;
        $this->hotel = $hotel;
        $this->promo = $promo;
        $this->link = $link;
        $this->title = $title;
        $this->suggestion = $suggestion;
    }

    public function build()
    {
        return $this->subject('Exclusive '. $this->hotel->name .' Promo Just for You!')
                    ->view('emails.promoSpecificEmailBlast')
                    ->with([
                        'bookingcode'=>$this->bookingcode,
                        'promo'=>$this->promo,
                        'hotel'=>$this->hotel,
                        'user'=>$this->user,
                        'link'=>$this->link,
                        'title'=>$this->title,
                        'suggestion'=>$this->suggestion,
                    ]);
    }
}

