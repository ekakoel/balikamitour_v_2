<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Hotels;
use App\Models\HotelPromo;
use App\Models\BookingCode;
use App\Models\EmailBlasts;
use App\Mail\HotelPromoMail;
use Illuminate\Http\Request;
use App\Mail\HotelPromoSpecificMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreEmailBlastsRequest;
use App\Http\Requests\UpdateEmailBlastsRequest;

class EmailBlastsController extends Controller
{

    public function index()
    {
        $emails = EmailBlasts::all();
        $user = User::where('id',1)->first();
        $title = "We are thrilled to offer you an exclusive promotion for your next stay at our luxury hotel. Don't miss out on these fantastic deals:";
        $promo = HotelPromo::find(51);
        $room = $promo->rooms;
        $link = "http://127.0.0.1:3000/hotel-ozISSJtfc3KVRB6Q8mMZd177UL";
        return view('emails.promoEmailBlast',[
            'emails'=>$emails,
            'user'=>$user,
            'title'=>$title,
            'promo'=>$promo,
            'room'=>$room,
            'link'=>$link,
        ]);
    }

    public function send_email_promo($id)
    {
        $now = Carbon::now();
        $hotel = Hotels::find($id);
        
        $promos = HotelPromo::where('hotels_id',$id)->where('status','Active')->where('book_periode_start','<=',$now)->where('book_periode_end','>=',$now)->get();
        return view('emails.send-email-promo',[
            'hotel'=>$hotel,
            'promos'=>$promos,
        ]);
    }
    public function send_specific_email_promo($id)
    {
        $now = Carbon::now();
        $hotel = Hotels::find($id);
        $bcodes = BookingCode::where('status','Active')
                            ->where('expired_date','>=',$now)
                            ->get();
        $promos = HotelPromo::where('hotels_id',$id)->where('status','Active')->where('book_periode_start','<=',$now)->where('book_periode_end','>=',$now)->get();
        return view('emails.send-specific-email-promo',[
            'hotel'=>$hotel,
            'promos'=>$promos,
            'bcodes'=>$bcodes,
        ]);
    }

    public function func_send_email_promo(Request $request,$id)
    {
        $promo = HotelPromo::find($id);
        $hotel = Hotels::where('id',$promo->hotels->id)->first();
        $link = $request->link;
        $title = $request->title;
        $suggestion = $request->suggestion;
        $subscribedUsers = User::where('is_subscribed', true)
                                ->where('is_approved', true)
                                ->get();
        $promo->update([
            "email_status"=>1,
        ]);
        // Kirim email ke setiap user yang subscribe
        foreach ($subscribedUsers as $user) {
            Mail::to($user->email)->queue(new HotelPromoMail($user, $hotel, $promo, $link, $title, $suggestion));
        }
        return back()->with('Promo Email sent successfully!');
    }

    public function func_send_specific_email_promo(Request $request,$id)
    {
        $emails = $request->input('emails');
        $emailList = array_map('trim', explode(',', $emails));

        $promo = HotelPromo::find($id);
        $hotel = Hotels::where('id',$promo->hotels->id)->first();
        $link = $request->link;
        $title = $request->title;
        if ($request->bookingcode) {
            $bookingcode = $request->bookingcode;
        }else{
            $bookingcode = "none";
        }
        $suggestion = $request->suggestion;

        $promo->update([
            "send_to_specific_email"=>1,
            "specific_email"=>$emails,
        ]);
        // Kirim email ke setiap user yang subscribe
        foreach ($emailList as $user) {
            // Mail::to($user->email)->send(new HotelPromoMail($user, $hotel, $promo, $link, $title, $suggestion));
            Mail::to($user)->send(new HotelPromoSpecificMail($bookingcode, $user, $hotel, $promo, $link, $title, $suggestion));
        }
        return back()->with('Promo Email sent successfully!');
    }
}
