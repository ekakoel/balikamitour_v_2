<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tax;
use App\Models\Hotels;
use App\Models\Orders;
use App\Models\ExtraBed;
use App\Models\UsdRates;
use App\Models\Attention;
use App\Models\HotelRoom;
use App\Models\Promotion;
use App\Models\HotelPromo;
use App\Models\Transports;
use App\Models\OptionalRate;
use Illuminate\Http\Request;
use App\Models\TransportPrice;
use App\Models\BusinessProfile;
use App\Models\OrderHotelPromo;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderHotelPromoDetail;
use Illuminate\Support\Facades\Cache;

class OrderHotelPromoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function order_hotel_promo_edit($id)
    { 
        $order = OrderHotelPromo::with(['details'])->where('id',$id)->first();
        $business = Cache::remember('business_profile', 3600, fn() => BusinessProfile::find(1));
        $logoDark = Cache::remember('app.logo_dark', 3600, fn() => config('app.logo_dark'));
        $altLogo = Cache::remember('app.alt_logo', 3600, fn() => config('app.alt_logo'));
        $checkin =  $order->check_in_date;
        $checkout = $order->check_out_date;
        $duration = Carbon::parse($checkin)->diffInDays(Carbon::parse($checkout));
        $hotel = Hotels::with(['promos'])->where('id',$order->hotel_id)->first();
        $order_details = $order->details;
        $prm_n = [];
        $prId = [];
        foreach ($order_details as $room) {
            $pr = json_decode($room->promo_id);
            $prs = array_unique($pr);
            foreach ($prs as $prm) {
                array_push($prId,$prm);
            }
        }
        if ($prId) {
            foreach ($prId as $pro_id) {
                $prm_n[] = $pro_id;
            }
        }
        $total_price_suites_and_villas = $order_details->sum('promo_price');
        $total_price_extra_bed = $order_details->sum('extra_bed_price');
        $total_price_room = $order_details->sum('total_price');
        $promos = HotelPromo::whereIn('id',$prId)->get();
        $promo_id = array_unique($prm_n);
        $pr_name = $promos->pluck('name')->toArray();
        $pr_type = $promos->pluck('promotion_type')->toArray();
        $pr_benefits = $promos->pluck('benefits')->toArray();
        $pr_include = $promos->pluck('include')->toArray();
        $pr_additional_info = $promos->pluck('additional_info')->toArray();

        $promo_names = array_filter($pr_name);
        $promo_types = array_filter($pr_type);
        $promo_benefits = array_filter($pr_benefits);
        $promo_includes = array_filter($pr_include);
        $promo_additional_info = array_filter($pr_additional_info);
        $descriptions = [
            'benefits' => 'benefits',
            'include' =>'include',
            'additional_info' => 'additional_info',
            'cancellation_policy' => 'cancellation_policy',
        ];

        // $optionalRates = OptionalRate::with('hotels')
        //     ->where('hotels_id', $hotel->id)
        //     ->get();

        $transports = Transports::where('status',"Active")->orderBy('capacity', 'DESC')->get();
        return view('order.edit-order-hotel-promo',compact('order'),[
            'order'=>$order,
            'business'=>$business,
            'logoDark'=>$logoDark,
            'altLogo'=>$altLogo,
            'checkin'=>$checkin,
            'checkout'=>$checkout,
            'hotel'=>$hotel,
            'order_details'=>$order_details,
            'promo_names'=>$promo_names,
            'promo_types'=>$promo_types,
            'promo_includes'=>$promo_includes,
            'promo_benefits'=>$promo_benefits,
            'promo_additional_info'=>$promo_additional_info,
            'promo_id'=>$promo_id,
            'promos'=>$promos,
            'descriptions'=>$descriptions,
            'duration'=>$duration,
            'total_price_suites_and_villas'=>$total_price_suites_and_villas,
            'total_price_extra_bed'=>$total_price_extra_bed,
            'total_price_room'=>$total_price_room,
            'transports'=>$transports,
        ]);
    }

    public function create(Request $request, $id)
    {
        $now = Carbon::now();
        $tax = Cache::remember('tax_1', 3600, fn() => Tax::find(1));
        $usdrates = Cache::remember('usd_rate', 3600, fn() => UsdRates::where('name', 'USD')->first());
        $business = Cache::remember('business_profile', 3600, fn() => BusinessProfile::find(1));
        $attentions = Attention::where('page','user-edit-order')->get();
        $user_id = Auth::id();
        $logoDark = Cache::remember('app.logo_dark', 3600, fn() => config('app.logo_dark'));
        $altLogo = Cache::remember('app.alt_logo', 3600, fn() => config('app.alt_logo'));
        $service = __('messages.Hotel Promotion');
        $orderno = OrderHotelPromo::count();
        $orderNumber = "ORD".date('Ymd', strtotime($now)).".HPP".$orderno;
        $checkin = session('booking_dates.checkin');
        $checkout = session('booking_dates.checkout');
        $duration = Carbon::parse($checkin)->diffInDays(Carbon::parse($checkout));
        $room = HotelRoom::with(['hotels'])->find($id);
        $hotel = $room->hotels;
        $prIds = json_decode($request->promo_id);
        $uniqueHotelPromoIds = array_unique($prIds);
        $promos = HotelPromo::whereIn('id',$uniqueHotelPromoIds)->get();
        $promosPrice = HotelPromo::whereIn('id',$prIds)->get();
        $promoName = $promos->pluck('name')->toArray(); 
        $promo_name = implode(', ',$promoName);
        $promoBenefits = $promos->pluck('benefits')->toArray(); 
        $promo_benefits = implode('<br>',$promoBenefits);
        $promoInclude = $promos->pluck('include')->toArray(); 
        $promo_include = implode('<br>',$promoInclude);
        $promoAdditionalInfo = $promos->pluck('additional_info')->toArray(); 
        $promo_additional_info = implode('<br>',$promoAdditionalInfo);
        $transports = Transports::with(['prices'])->select('id','name','brand','capacity')
            ->where('status',"Active")->orderBy('capacity', 'DESC')
            ->get();
        
        $transport_prices = TransportPrice::where('duration',$hotel->airport_duration)->get();
        
        $final_price = 0;
        foreach ($promosPrice as $prPrice) {
            $fp = $prPrice->calculatePrice($usdrates, $tax);
            $final_price += $fp;
        }
        $promo_price = $request->promo_price;
        return view('form.order-hotel-promo', compact(
            'now',
            'usdrates',
            'tax',
            'business',
            'logoDark',
            'altLogo',
            'service',
            'orderNumber',
            'checkin',
            'checkout',
            'duration',
            'hotel',
            'promos',
            'promo_name',
            'room',
            'promo_benefits',
            'promo_include',
            'promo_additional_info',
            'transports',
            'transport_prices',
            'final_price',
            'promo_price',
            'prIds',
        ));
    }



    public function store_order_hotel_promo(Request $request)
    {
        $tax = Cache::remember('tax_1', 3600, fn() => Tax::find(1));
        $usdrates = Cache::remember('usd_rate', 3600, fn() => UsdRates::where('name', 'USD')->first());
        $checkin = session('booking_dates.checkin');
        $checkout = session('booking_dates.checkout');
        $number_of_rooms = count($request->number_of_guests);
        $number_of_guests = array_sum($request->number_of_guests);
        $total_price = $request->final_price;
        $order = new OrderHotelPromo([
            'orderno' => $request->orderno,
            'hotel_id' => $request->hotel_id,
            'room_id' => $request->room_id,
            'user_id' => auth()->id(),
            'booking_code' => $request->booking_code,
            'check_in_date' => $checkin,
            'check_out_date' => $checkout,
            'total_guests' => $number_of_guests,
            'total_price' => $total_price,
            'status' => "Pending",
            'additional_info' => $request->additional_info,
            'promo_id' => $request->promo_id,
            
        ]);
        $order->save();
        for ($i=0; $i < $number_of_rooms; $i++) { 
            $extra_bed_id = $request->extra_bed_id[$i];
            $special_event = $request->special_day[$i];
            $special_event_date = $request->special_date[$i];
            $nog = $request->number_of_guests[$i];
            $guest_detail = $request->guest_detail[$i];
            $promo_price = $request->var_promo_price;
            $extra_bed = ExtraBed::find($extra_bed_id);
            if ($extra_bed) {
                $extra_bed_price = $extra_bed->calculatePrice($usdrates, $tax);
            }else{
                $extra_bed_price = 0;
            }
            $total_price = $promo_price + $extra_bed_price;
            $order_detail[$i] = new OrderHotelPromoDetail([
                'order_id'=> $order->id,
                'promo_id'=> $request->promo_id,
                'extra_bed_id'=> $extra_bed_id,
                'room_number'=> $i+1,
                'special_event'=> $special_event,
                'special_event_date'=> $special_event_date,
                'number_of_guests'=> $nog,
                'guest_name'=> $guest_detail,
                'promo_price'=> $promo_price,
                'extra_bed_price'=> $extra_bed_price,
                'total_price'=> $total_price,
            ]);
            $order_detail[$i]->save();
        }

        // Rentang tanggal
        $startDate = Carbon::parse($request->check_in_date);
        $endDate = Carbon::parse($request->check_out_date);
    
        return redirect()->route('order-hotel-promo-edit.view',$order->id)->with('success', 'Order successfully created!');
    }

    // USER EDIT ORDER ROOM --------------------------------------------------------------------------------------------------------------------------------------------->
    public function edit_order_room_promo($id) 
    {   
        $now = Carbon::now();
        $user_id = Auth::User()->id;
        $usdrates = Cache::remember('usd_rates', 60, function () {return UsdRates::where('name', 'USD')->first();});
        $tax = Cache::remember('tax', 60, function () {return Tax::where('id', 1)->first();});
        $business = Cache::remember('business_profile', 60, function () {return BusinessProfile::where('id', 1)->first();});
        $attentions = Attention::where('page', 'editorder-room')->get();
        $order = OrderHotelPromo::with(['details','room'])->where('id', $id)
            ->where('user_id', $user_id)
            ->first();
        $hotel = $order->hotel;
        $duration = Carbon::parse($order->check_in_date)->diffInDays(Carbon::parse($order->check_out_date));
        // if (!$order || !in_array($order->status, ["draft", "rejected", "invalid"])) {
        //     return redirect('/orders')->with('error', "Sorry we couldn't find the order.");
        // }
        $extrabed = ExtraBed::where('hotels_id', $hotel->id)->get();
        $extrabeds = $extrabed->map(function ($eb) use ($usdrates, $tax, $order) {
            $eb_price = $eb->calculatePrice($usdrates, $tax) * $order->duration;
            $eb->price = $eb_price;
            return $eb;
        });
        $order_rooms = $order->details;
        $promo_id = 
        $date_stay = [];
        $from = $order->check_in_date;
        for ($a = 0; $a < $duration; $a++) {
            $date_stay[] = $from;
            $from = date('Y-m-d', strtotime('+1 days', strtotime($from)));
        }
        
        return view('order.edit-room-promo', compact(
            'order',
            'hotel',
            'extrabed', 
            'extrabeds', 
            'tax', 
            'now', 
            'usdrates', 
            'business', 
            'attentions',
            'date_stay',
            'order_rooms',
        ));

        
        // return redirect('/orders')->with('error', "Sorry we couldn't find the order.");
    }
    

    // Function Update Order Room =============================================================================================================>
    public function func_update_order_promo_room(Request $request,$id){
        $orderPromo=OrderHotelPromo::findOrFail($id);
        $orderRooms = $orderPromo->details;
        $usdrates = Cache::remember('usd_rates', 60, function () {return UsdRates::where('name', 'USD')->first();});
        $tax = Cache::remember('tax', 60, function () {return Tax::where('id', 1)->first();});
        $extrabeds = ExtraBed::where('hotels_id', $hotel->id)->get();

        $number_of_room = count($number_of_guests_room);
        $noor =count($orderRooms);

        for ($i=0; $i < $number_of_room; $i++) { 
            $order_room_id = $request->order_room_id[$i];
            $extra_bed_id = $request->extra_bed_id[$i];
            $special_event = $request->special_event[$i];
            $special_event_date = $request->special_event_date[$i];
            $number_of_guests = $request->number_of_guests[$i];
            $guest_name = $request->guest_detail[$i];

            
            if ($order_room_id) {
                $order_room = $orderRooms->where('id',$order_room_id)->first();
                if ($order_room) {
                    $promo_price = $order_room->calculatePrice($usdrates, $tax);
                    if ($extra_bed_id) {
                        $extra_bed = $extrabeds->where('id', $extra_bed_id)->first();
                        if ($extra_bed) {
                            $extra_bed_price = $extra_bed->calculatePrice($usdrates, $tax);
                        }else{
                            $extra_bed_price = 0;
                        }
                    }else{
                        $extra_bed_price = NULL;
                    }
                    if ($extra_bed_price) {
                        $extrabedprice = $extra_bed_price;
                    }else{
                        $extrabedprice = 0;
                    }
                    $total_price = $promo_price + $extrabedprice;
                    $order_room = $this->update_order_details($order_room, $extra_bed_id, $special_event, $special_event_date, $number_of_guests, $guest_name, $promo_price, $extra_bed_price, $total_price);
                    // $order_room->update([
                    //     "extra_bed_id"=>$extra_bed_id,
                    //     "special_event"=>$special_event,
                    //     "special_event_date"=>$special_event_date,
                    //     "number_of_guests"=>$number_of_guests,
                    //     "guest_name"=>$guest_name,
                    //     "promo_price"=>$promo_price,
                    //     "extra_bed_price"=>$extra_bed_price,
                    //     "total_price"=>$total_price,
                    // ]);
                }else{
                    $order_id = $id;
                    $promo_id = $orderPromo->promo_id;
                    $extra_bed_id = $request->extra_bed_id[$i];
                    $special_event = $request->special_event[$i];
                    $special_event_date = $request->special_event_date[$i];
                    $number_of_guests = $request->number_of_guests[$i];
                    $guest_name = $request->guest_detail[$i];
                    $order_room = $this->create_order_details($order_id, $promo_id, $extra_bed_id, $room_number, $special_event, $special_event_date, $number_of_guests, $guest_name, $promo_price, $extra_bed_price, $total_price);
                }
            }else{
                $special_event = $request->special_day[$i];
                $special_event_date = $request->special_date[$i];
                $nog = $request->number_of_guests[$i];
                $guest_detail = $request->guest_detail[$i];
                $promo_price = $request->var_promo_price;
                $extra_bed = ExtraBed::find($extra_bed_id);
                if ($extra_bed) {
                    $extra_bed_price = $extra_bed->calculatePrice($usdrates, $tax);
                }else{
                    $extra_bed_price = 0;
                }
                $total_price = $promo_price + $extra_bed_price;

                $order_detail[$i] = new OrderHotelPromoDetail([
                    'order_id'=> $orderPromo->id,
                    'promo_id'=> $request->promo_id,
                    'extra_bed_id'=> $extra_bed_id,
                    'room_number'=> $i+1,
                    'special_event'=> $special_event,
                    'special_event_date'=> $special_event_date,
                    'number_of_guests'=> $nog,
                    'guest_name'=> $guest_detail,
                    'promo_price'=> $promo_price,
                    'extra_bed_price'=> $extra_bed_price,
                    'total_price'=> $total_price,
                ]);
                $order_detail[$i]->save();
            }
        }
        dd([$orderPromo]);
        // if ($request->number_of_guests_room > 0) {
        //     $number_of_guests = array_sum($request->number_of_guests_room);
        //     $extra_bed_proses = [];
        //     foreach ($request->number_of_guests_room as $jk) {
        //         if ($jk < 3 ) {
        //             array_push($extra_bed_proses,'No');
        //         }else{
        //             array_push($extra_bed_proses,'Yes');
        //         }
        //     }

        //     $extra_bed_id_price = [];          
        //     for ($i=0; $i < $croom; $i++) { 
        //         if ($extra_bed_proses[$i] == "Yes") {
        //             if ($request->extra_bed_id[$i] == 0) {
        //                 array_push($extra_bed_id_price,null);
        //             }else{
        //                 $extrabeds = ExtraBed::where('id',$request->extra_bed_id[$i])->first();
        //                 $contract_rate_eb = ceil($extrabeds->contract_rate/$usdrates->rate)+$extrabeds->markup;
        //                 $tax_usd_extra_bed = ceil($contract_rate_eb * ($tax->tax/100));
        //                 $price_extra_bed = ($contract_rate_eb + $tax_usd_extra_bed)*$duration; 
        //                 array_push($extra_bed_id_price,$price_extra_bed);
        //             } 
        //         }else{
        //             array_push($extra_bed_id_price,0);
        //         }
        //     }
        //     $extra_bed_id = json_encode($request->extra_bed_id);
        //     $extra_bed_price = json_encode($extra_bed_id_price);
        //     $extra_bed = json_encode($extra_bed_proses);
        //     $guest_detail = json_encode($request->guest_detail);
        //     $special_day = json_encode($request->special_day);
        //     $special_date = json_encode($request->special_date);
        //     $pro_disc = json_decode($order->promotion_disc);
        //     $number_of_guests_room = json_encode($request->number_of_guests_room);
        //     $total_extra_bed = array_sum($extra_bed_id_price);
        //     if (isset($pro_disc)) {
            
        //         $promotion_disc = array_sum($pro_disc);
        //     }else{
        //         $promotion_disc = 0;
        //     }
            
        //     $normal_price = $order->normal_price;
        //     $price_total_ue = ($price_pax * $croom)+$total_extra_bed;
        //     $price_total = (($normal_price * $croom) - $kick_back) + $total_extra_bed;
        //     $final_price = (($price_total + $optional_price) - $order->discounts) - $order->bookingcode_disc - $promotion_disc;
        
        // }else{
        //     $number_of_guests = 0;
        //     $number_of_guests_room = 0;
        //     $croom = 0;
        //     $extra_bed_proses = 0;
        //     $extra_bed_id = 0;
        //     $extra_bed_price = 0;
        //     $extra_bed = 0;
        //     $price_total = 0;
        //     $kick_back = 0;
        //     $guest_detail = 0;
        //     $special_day = 0;
        //     $special_date = 0;
        //     $normal_price = 0;
        //     $airport_shuttle_price = 0;
        //     $final_price = 0;
        // }

        
        // dd([$order]);
        return redirect("/edit-order-$id")->with('success','Your order has been updated');
    }

    private function update_order_details($order_room, $extra_bed_id, $special_event, $special_event_date, $number_of_guests, $guest_name, $promo_price, $extra_bed_price, $total_price)
    {
        $order_room->update([
            "extra_bed_id"=>$extra_bed_id,
            "special_event"=>$special_event,
            "special_event_date"=>$special_event_date,
            "number_of_guests"=>$number_of_guests,
            "guest_name"=>$guest_name,
            "promo_price"=>$promo_price,
            "extra_bed_price"=>$extra_bed_price,
            "total_price"=>$total_price,
        ]);
    }

    private function create_order_details($order_id, $promo_id, $extra_bed_id, $room_number, $special_event, $special_event_date, $number_of_guests, $guest_name, $promo_price, $extra_bed_price, $total_price)
    {
        $order_detail = new OrderHotelPromoDetail([
            'order_id'=> $order_id,
            'promo_id'=> $promo_id,
            'extra_bed_id'=> $extra_bed_id,
            'room_number'=> $room_number,
            'special_event'=> $special_event,
            'special_event_date'=> $special_event_date,
            'number_of_guests'=> $number_of_guests,
            'guest_name'=> $guest_name,
            'promo_price'=> $promo_price,
            'extra_bed_price'=> $extra_bed_price,
            'total_price'=> $total_price,
        ]);
        $order_detail->save();
    }
}
