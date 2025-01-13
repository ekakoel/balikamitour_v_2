<?php
namespace App\Mail;
use App\Models\User;
use App\Models\Hotels;
use App\Models\HotelPromo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
class HotelPromoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $hotel;
    public $promo;
    public $link;
    public $title;
    public $suggestion;

    public function __construct(User $user, Hotels $hotel, HotelPromo $promo, string $link, string $title, string $suggestion)
    {
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
                    ->view('emails.promoEmailBlast')
                    ->with([
                        'promo'=>$this->promo,
                        'hotel'=>$this->hotel,
                        'user'=>$this->user,
                        'link'=>$this->link,
                        'title'=>$this->title,
                        'suggestion'=>$this->suggestion,
                    ]);
    }
}

