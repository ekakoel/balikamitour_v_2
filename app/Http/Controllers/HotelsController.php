<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Tax;
use App\Models\Hotels;
use App\Models\Orders;
use App\Models\UserLog;
use App\Models\ExtraBed;
use App\Models\UsdRates;
use App\Models\Attention;
use App\Models\HotelRoom;
use App\Models\HotelType;
use App\Models\Promotion;
use App\Models\HotelPrice;
use App\Models\HotelPromo;
use App\Models\Transports;
use App\Models\BookingCode;
use Illuminate\Support\Str;
use App\Models\HotelPackage;
use App\Models\HotelsImages;
use App\Models\OptionalRate;
use Illuminate\Http\Request;
use App\Models\BusinessProfile;
use App\Models\OptionalRateOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\StorehotelsRequest;
use App\Http\Requests\UpdatehotelsRequest;

class HotelsController extends Controller
{   
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    // OK
    public function index(Request $request)
    {
        $now = Carbon::now();
        $promotions = Promotion::select('name', 'discounts', 'periode_start', 'periode_end')
            ->where('status', "Active")
            ->where('periode_start', '<=', $now)
            ->where('periode_end', '>=', $now)
            ->get();
        $hotelsQuery = Hotels::select('code', 'name', 'region', 'map', 'cover', 'id')
            ->where('status', 'active')
            ->with(['promos' => function ($query) use ($now) {
                $query->select('promotion_type', 'hotels_id')
                    ->where('status', 'Active')
                    ->where('book_periode_start', '<=', $now)
                    ->where('book_periode_end', '>=', $now)
                    ->latest();
            }]);
        if ($request->filled('hotel_name')) {
            $hotelsQuery->where('name', 'like', '%' . $request->input('hotel_name') . '%');
        }
        if ($request->filled('hotel_region')) {
            $hotelsQuery->where('region', 'like', '%' . $request->input('hotel_region') . '%');
        }
        $hotels = $hotelsQuery->latest()->paginate(12);
        return view('main.hotels', compact('hotels', 'promotions'));
    }

    public function search_hotel(Request $request)
    {
        if (!$request->hotel_name && !$request->hotel_region) {
            return redirect("/hotels");
        }
        $now = Carbon::now();
        $hotels = Hotels::query()
            ->select('code', 'name', 'region', 'map', 'cover', 'id')
            ->where('status', 'active')
            ->when($request->hotel_name, function ($query, $hotel_name) {
                $query->where('name', 'LIKE', "%$hotel_name%");
            })
            ->when($request->hotel_region, function ($query, $hotel_region) {
                $query->where('region', 'LIKE', "%$hotel_region%");
            })
            ->with(['promos' => function ($query) use ($now) {
                $query->select('promotion_type', 'hotels_id')
                    ->where('book_periode_start', '<=', $now)
                    ->where('book_periode_end', '>=', $now)
                    ->where('status', 'Active');
            }])
            ->latest()
            ->get();
        $promotions = Promotion::query()
            ->select('name', 'discounts', 'periode_start', 'periode_end')
            ->where('status', 'Active')
            ->whereBetween('periode_start', [$now->startOfDay(), $now->endOfDay()])
            ->get();
        return view('main.hotelsearch', compact('hotels', 'promotions'));
    }


    // Detail Hotel =========================================================================================>
    public function hoteldetail(Request $request, $code)
    {
        $now = Carbon::now();
        $hotel = Hotels::with([
            'rooms'=> function ($rm){
                $rm->where('status','Active');
            },
            'promos' => function ($query) use ($now) {
            $query->where('status', 'Active')
                ->where('book_periode_start', '<=', $now)
                ->where('book_periode_end', '>=', $now)
                ->latest();
        }])->where('code', $code)->firstOrFail();

        $business = BusinessProfile::findOrFail(1);
        $usdrates = UsdRates::where('name', 'USD')->first();
        $nearhotels = Hotels::select('code','name','region','cover','id')
            ->with([
                'promos' => function ($query) use ($now) {
                    $query->where('status', 'Active')
                    ->where('book_periode_start', '<=', $now)
                    ->where('book_periode_end', '>=', $now);
            }])->where('status', 'Active')
                ->where('region', $hotel->region)
                ->where('id', '!=', $hotel->id)
                ->take(4)
                ->get();
        $promotions = Promotion::select('name','discounts','periode_start','periode_end')
            ->where('status', "Active")
            ->where('periode_start','<=',$now)
            ->where('periode_end','>=',$now)
            ->get();
        $bookingcode = session('bookingcode');
        return view('main.hoteldetail', compact('hotel', 'business', 'usdrates', 'now', 'nearhotels','promotions','bookingcode'));
    }



// Detail Hotel BOOKING CODE =========================================================================================>
    public function hoteldetail_bookingcode($code,$bcode)
    {
        $promotions = Promotion::where('status',"Active")->get();
        $user_id = Auth::user()->id;
        $ordercode = Orders::where('user_id', $user_id)
            ->where('bookingcode',$bcode)
            ->where('status','!=','Rejected')->first();
        $ascode = Orders::where('bookingcode',$bcode)
            ->where('status','!=','Rejected')->get();
        $cusdcode = count($ascode);
        $business = BusinessProfile::where('id','=',1)->first();
        $now = Carbon::now();
        $hotels = Hotels::with('rooms')->get();
        $hotel = Hotels::where("code",$code)->first();
        if ($hotel) {
            $nearhotels = Hotels::where('status',"Active")->where('region',"like",'%'.$hotel->region.'%')->take(4)->get();
        }else{
            $nearhotels = NULL;

        }
        if ($hotel) {
            $hotelrooms = HotelRoom::where('hotels_id','=',$hotel->id)
            ->where('status', 'Active')
            ->get();
        }else {
            return redirect("/hotels");
        }
        $data = HotelPrice::where('hotels_id','=',$hotel->id)->get();
        $lowerprice = $data->where('contract_rate','>',0)->min('contract_rate');
        $optional_rate = OptionalRate::where('service',"Hotel")
        ->where('service_id',$hotel->id)->get();
        $usdrates = UsdRates::where('name','USD')->first();
        $hotel_promotions = HotelPromo::where('hotels_id',$hotel->id)
        ->where('book_periode_start','<=',$now)
        ->where('book_periode_end','>=',$now)
        ->where('status','Active')
        ->where('periode_start','>=',$now)
        ->orderBy('created_at','desc')
        ->get();
        if ($ordercode) {
            $bookingcode = $bcode;
            $bookingcode_status = "Used";
            return redirect("/hotel-$code")->with('danger','The booking code that you entered has been used!');
        }else{
            $bkingcode = BookingCode::where('code', $bcode)
            ->where('expired_date','>',$now)
            ->where('amount','>',$cusdcode)
            ->first();
            if ($bkingcode) {
                $bookingcode_status = "Valid";
                $bookingcode = $bkingcode;
                return view('main.hoteldetail', compact('hotel'),[
                    'business'=>$business,
                    'optional_rate'=>$optional_rate,
                    'usdrates'=>$usdrates,
                    'hotels'=> $hotels,
                    'now'=>$now,
                    'hotel'=>$hotel,
                    'nearhotels'=>$nearhotels,
                    'lowerprice'=>$lowerprice,
                    'hotelrooms'=>$hotelrooms,
                    'bookingcode'=>$bookingcode,
                    'bookingcode_status'=>$bookingcode_status,
                    'promotions'=>$promotions,
                    'hotel_promotions'=>$hotel_promotions,
                ]);
            }else{
                return redirect("/hotel-$code")->with('danger','Booking code is invalid!');
            }
        }
    }


    public function fcheck_code(Request $request){
        $bcode = $request->bookingcode;
        return redirect('/hotels')->with('bookingcode',$bcode);
    }


    public function hotel_price(Request $request, $code)
    {
        session(['previous_url' => url()->previous()]);
        $checkincout = $request->checkincout;
        [$checkin, $checkout] = $this->parseCheckInOut($request->checkincout);
        $duration = Carbon::parse($checkin)->diffInDays(Carbon::parse($checkout));
        Session::put('booking_dates', [
            'checkin' => $checkin,
            'checkout' => $checkout,
            'duration' => $duration,
        ]);
        $now = now()->format('Y-m-d');
        $tax = Cache::remember('tax', 3600, function () {
            return Tax::select('name', 'tax')->where('name', 'tax')->first();
        });
        $business = Cache::remember('business_profile', 3600, function () {
            return BusinessProfile::select('id', 'name', 'address')->first();
        });
        $usdrates = Cache::remember('usd_rates', 3600, function () {
            return UsdRates::select('name', 'rate')->where('name', 'USD')->first();
        });
        $hotel = Hotels::with(['rooms' => function ($query) {
            $query->select('id', 'hotels_id', 'rooms', 'status','cover','include')
                  ->where('status', 'Active');
        }])->where('code', $code)->first();
        
        $nearhotels = Hotels::with([
            'promos' => function ($query) use ($now) {
                $query->where('status', 'Active')
                    ->where('book_periode_start', '<=', $now)
                    ->where('book_periode_end', '>=', $now);
                }])->where('status', 'Active')
            ->where('region', $hotel->region)
            ->where('id', '!=', $hotel->id)
            ->take(8)
            ->get(['id','code','cover', 'name', 'region']);

        $promotions = Promotion::where('status', 'Active')
            ->where('periode_start','<=',$now)
            ->where('periode_end','>=',$now)
            ->get();
        $promotion_price = $promotions->sum('discounts');
        if ($duration < $hotel->min_stay) {
            return view('main.hoteldetail', compact('hotel', 'business', 'usdrates', 'now', 'nearhotels','promotions'))
            ->with('error', [
                __('messages.Minimum stay') . " " . $hotel->min_stay . " " . __('messages.nights')
            ]);
        }
        $promo_colors = [
            "Special Offer" => "bg-blue",
            "Best Choice"   => "bg-green",
            "Best Price"    => "bg-orange",
            "Hot Deal"      => "bg-red",
            "Normal"        => "bg-gray",
        ];
        $displayedPromos = [];
        $room_prices = HotelPrice::
            with([
                'rooms'=> function ($query) {
                        $query->where('status', 'Active'); 
                    },
                'hotels',
            ])
            ->where('hotels_id', $hotel->id)
            ->get();
        
        $roomPrices = HotelPrice::with([
            'rooms'=> function ($query) {
                    $query->where('status', 'Active'); 
                },
            'hotels',
            ])
            ->where('hotels_id', $hotel->id)
            ->get();

        $packages = HotelPackage::with(['hotels', 'room'])
            ->where('hotels_id', $hotel->id)
            ->where('status', 'Active')
            ->forDuration($duration)
            ->validForStay($checkin)
            ->get()
            ->map(function ($package) use ($usdrates, $tax, $duration, $checkin) {
                $package->parsed_checkin = $checkin;
                $package->calculated_price = $package->calculatePrice($usdrates, $tax);
                $package->price_per_day = $package->calculated_price / $duration;
                return $package;
            });
        $user_id = Auth::id();
        $order_select = Orders::where('user_id', $user_id)
            ->where('servicename', $hotel->name)
            ->where('checkin', '>', $now)
            ->get();
        $orders = Orders::where('user_id', $user_id)->get(['id', 'final_price']);
        $orderno = Orders::count() + 1;
        
        $hotel_promotions = HotelPromo::active()
            ->validForBooking($now)
            ->where('hotels_id', $hotel->id)
            ->get();

        $processedPromos = [];
        $normalPriceData = [];
        foreach ($hotel->rooms as $room) {
            $promoDetails = $this->processPromo($room, $room_prices, $hotel_promotions, $checkin, $duration, $usdrates, $tax);
            if ($promoDetails) {
                $processedPromos[] = $promoDetails;
            }
            $totalNormalRoomPrice = 0;
            $totalKickBack = 0;
            $normalPricePerDate = [];
            $has_normal = [];
            for ($k=0; $k < $duration; $k++) { 
                $normal_temp_date = date('Y-m-d',strtotime('+'.$k.'days',strtotime($checkin)));
                $roomPrice = $room_prices->where('rooms_id',$room->id)
                    ->where('start_date','<=',$normal_temp_date)
                    ->where('end_date','>=',$normal_temp_date)
                    ->first();
                if ($roomPrice) {
                    if ($roomPrice->kick_back) {
                        $kick_back = $roomPrice->kick_back;
                    }else{
                        $kick_back = 0;
                    }
                    $normal_price_temporary = $roomPrice->calculatePrice($usdrates, $tax);

                }else{
                    $has_normal[]= 0;
                    $normal_price_temporary = 0;
                    $kick_back = 0;
                }
                $normalPricePerDate[] = [
                    'normal_date' => $normal_temp_date,
                    'normal_price' => $normal_price_temporary,
                    'normal_kick_back' => $kick_back,
                ];
                $totalNormalRoomPrice += $normal_price_temporary;
                $totalKickBack += $kick_back;
            }
            if (!in_array(0,$has_normal)) {
                $normalPriceData[] = [
                    'normal_room_id' => $room->id,
                    'normal_prices' => $normalPricePerDate,
                    'normal_room' => $room,
                    'total_price' => $totalNormalRoomPrice,
                    'total_kick_b' => $totalKickBack,
                    'include' => $room->include,
                ];
            }
        }
        // dd($request->checkincout);
        return view('main.hotelavailability', [
            'tax' => $tax,
            'usdrates' => $usdrates,
            'business' => $business,
            'now' => $now,
            'hotel' => $hotel,
            'nearhotels' => $nearhotels,
            'room_prices' => $room_prices,
            'packages' => $packages,
            'order_select' => $order_select,
            'orders' => $orders,
            'orderno' => $orderno,
            'promotions' => $promotions,
            'promotion_price' => $promotion_price,
            'duration' => $duration,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'processedPromos' => $processedPromos,
            'hotel_promotions' => $hotel_promotions,
            'normalPriceData' => $normalPriceData,
            'totalNormalRoomPrice' => $totalNormalRoomPrice,
            'totalKickBack' => $totalKickBack,
            'roomPrices' => $roomPrices,
            'promo_colors' => $promo_colors,
            'displayedPromos' => $displayedPromos,
        ]);
    }

    private function processPromo($room, $room_prices, $hotel_promotions, $checkin, $duration, $usdrates, $tax)
    {
        $on_dates = [];
        $promo_type = [];
        $hotel_promo = [];
        $promo_id_list = [];
        $price_list = [];
        $promo_include = [];
        $current_date = strtotime($checkin);
        $promo_off = [];
        $check_availability = 0;

        $valid_promotions = $hotel_promotions->filter(function ($promo) use ($checkin, $duration) {
            return $this->calculatePromoDuration($promo, $checkin, $duration) > 0 
                && $promo->minimum_stay <= $duration;
        });

        $remaining_duration = $duration;

        for ($i = 0; $i < $duration; $i++) {
            $date_in = date('Y-m-d', $current_date + ($i * 86400));
            $has_promo = false;

            foreach ($valid_promotions as $hotel_promotion) {
                if ($hotel_promotion->rooms_id === $room->id) {
                    if ($hotel_promotion->periode_start <= $date_in && $hotel_promotion->periode_end >= $date_in) {
                        $promo_days_left = $this->calculatePromoDuration($hotel_promotion, $date_in, $remaining_duration);
                        if ($promo_days_left >= $hotel_promotion->minimum_stay) {
                            for ($j = 0; $j < $promo_days_left; $j++) {
                                if ($i + $j < $duration) {
                                    $current_date_in = date('Y-m-d', $current_date + (($i + $j) * 86400));
                                    $on_dates[] = $current_date_in;
                                    $promo_type[] = $hotel_promotion->promotion_type;
                                    $hotel_promo[] = $hotel_promotion;
                                    $promo_id_list[] = $hotel_promotion->id;
                                    $price_list[] = $hotel_promotion->calculatePrice($usdrates, $tax);
                                    $promo_include[] = $hotel_promotion->include;
                                    $promo_off[] = 1;
                                    $check_availability++;
                                    $remaining_duration--;
                                }
                            }
                            $has_promo = true;
                            $i += $promo_days_left - 1;
                            break;
                        }
                    }
                }
            }
            if (!$has_promo) {
                $normal_price = $this->getNormalPrice($room, $date_in, $usdrates, $tax);
                $on_dates[] = $date_in;
                $promo_type[] = 'Normal';
                $hotel_promo[] = null;
                $promo_id_list[] = null;
                $promo_include[] = null;
                $promo_off[] = 0;
                $price_list[] = $normal_price;
                $remaining_duration--;
            }
        }
        $total_promo_off = array_sum($promo_off);
        if (in_array(0,$price_list)) {
            return null;
        }else{
            if ($total_promo_off > 0) {
                return [
                    'room' => $room,
                    'on_dates' => $on_dates,
                    'promo_type' => $promo_type,
                    'hotel_promo' => $hotel_promo,
                    'promo_id_list' => $promo_id_list,
                    'price_list' => $price_list,
                    'total_price' => array_sum($price_list),
                    'check_availability' => $check_availability,
                    'total_promo_off' => $total_promo_off,
                    'promo_include' => $promo_include,
                ];
            } else {
                return null;
            }
        }
    }
    private function calculatePromoDuration($hotel_promotion, $checkin, $duration)
    {
        $promo_check_duration = 0;
        for ($j = 0; $j < $duration; $j++) {
            $check_date = date('Y-m-d', strtotime("+$j days", strtotime($checkin)));
            if ($hotel_promotion->periode_start <= $check_date && $hotel_promotion->periode_end >= $check_date) {
                $promo_check_duration++;
            }
        }
        return $promo_check_duration;
    }
    private function getNormalPrice($room, $date_in, $usdrates, $tax)
    {
        $roomList = HotelPrice::where('rooms_id', $room->id)
                                ->where('start_date', '<=', $date_in)
                                ->where('end_date', '>=', $date_in)
                                ->first();
        return $roomList ? $roomList->calculatePrice($usdrates, $tax) : 0;
    }
    private function parseCheckInOut($checkincout)
    {
        [$check_in, $check_out] = explode(' - ', $checkincout);
        return [
            date('Y-m-d', strtotime($check_in)),
            date('Y-m-d', strtotime($check_out))
        ];
    }

    // Hotel Price Booking code =========================================================================================>
    public function hotel_price_bookingcode(Request $request, $code,$bcode){
        
        $checkincout = $request->checkincout;
        $check_in = substr($request->checkincout, 0, 10);
        $check_out = substr($request->checkincout, 13, 23);
        $checkin = date('Y-m-d', strtotime($check_in));
        $checkout = date('Y-m-d', strtotime($check_out));
        $in=Carbon::parse($checkin);
        $out=Carbon::parse($checkout);
        $duration = $in->diffInDays($out);
        $tax = Tax::where('name',"Tax")->first();
        $order = Orders::all();
        $orderno = count($order) + 1;
        $business = BusinessProfile::where('id','=',1)->first();
        $now = date('Y-m-d');
        $datevalidate=date('Y-m-d', strtotime("-1 days", strtotime($checkout)));
        $out_package=date('Y-m-d', strtotime("+".$duration."days", strtotime($checkin)));
        $hotel = Hotels::where("code",$code)->first();
        $nearhotels = Hotels::where('status',"Active")->where('region', $hotel->region)->where('id','!=',$hotel->id)->take(4)->get();
        $optional_rate = OptionalRate::where('service','Hotel')
            ->where('service_id',$hotel->id)->get();
        $hotelrooms = HotelRoom::where('hotels_id','=',$hotel->id)->where('status','Active')->get();
        $usdrates = UsdRates::where('name','USD')->first();
        $room_price = HotelPrice::where('hotels_id',$hotel->id)
        ->get();
        $promos = HotelPromo::where('hotels_id',$hotel->id)
            ->where('status','Active')
            ->where('book_periode_start','<',$now)
            ->where('book_periode_end','>',$now)
            ->where('minimum_stay','<=',$duration)
            ->get();
        $packages = HotelPackage::where('hotels_id',$hotel->id)
            ->where('status','Active')
            ->where('duration','<=',$duration)
            ->where('stay_period_start','<=',$checkin)
            ->where('stay_period_end','>=',$checkin)
            ->get();
        $order_select = Orders::where('user_id', Auth::user()->id)
            ->where('servicename',$hotel->name)
            ->where('checkin','>',$now)->get();
        // $bookingcode = $request->bookingcode;
        $user_id = Auth::user()->id;
        $orders = Orders::where('user_id', $user_id)->get();
        $promotions = Promotion::where('status',"Active")->get();
        $promotion_price = $promotions->sum('discounts');
        $bk_code = BookingCode::where('code', $bcode)->where('status', 'Active')->first();
        $hotel_promotions = HotelPromo::where('hotels_id',$hotel->id)
        ->where('book_periode_start','<=',$now)
        ->where('book_periode_end','>=',$now)
        ->where('status','Active')
        ->get();
        if (isset($bcode)) {
            $bk_code = BookingCode::where('code', $bcode)->where('status', 'Active')->first();
            if (isset($bk_code)) {
                if ($bk_code->used < $bk_code->amount) {
                    if (isset($orders)) {
                        $usedcode = $orders->where('bookingcode', $bk_code->code)->first();
                        if (isset($usedcode)) {
                            $bookingcode_status = "Used";
                            $bookingcode = NULL;
                        }else{
                            if ($bk_code->expired_date >= $now) {
                                $bookingcode_status = "Valid";
                                $bookingcode = $bk_code;
                            }else{
                                $bookingcode_status = "Expired";
                                $bookingcode = NULL ;
                            }
                        }
                    }else{
                        if ($bk_code->expired_date >= $now) {
                            $bookingcode_status = "Valid";
                            $bookingcode = $bk_code;
                        }else{
                            $bookingcode_status = "Expired";
                            $bookingcode = NULL ;
                        }
                    }
                }else{
                    $bookingcode_status = "Expired";
                    $bookingcode = NULL ;
                }
            }else{
                $bookingcode_status = 'Invalid';
                $bookingcode = NULL;
            }
        }else{
            $bookingcode_status = NULL;
            $bookingcode = NULL;
        }
        return view('main.hotelavailability', compact('hotel'),[
            'tax'=>$tax,
            'day'=>$now,
            'packages'=>$packages,
            'promos'=>$promos,
            'order_select'=>$order_select,
            'optional_rate'=>$optional_rate,
            'orderno'=>$orderno,
            'business'=>$business,
            'now'=>$now,
            'room_price'=>$room_price,
            'in'=>$in,
            'out'=>$out,
            'usdrates'=>$usdrates,
            'duration'=>$duration,
            'hotelrooms'=>$hotelrooms,
            'hotel'=>$hotel,
            'checkin'=>$checkin,
            'checkout'=>$checkout,
            'checkincout'=>$checkincout,
            'bookingcode'=>$bookingcode,
            'bookingcode_status'=>$bookingcode_status,
            'promotions'=>$promotions,
            'promotion_price'=>$promotion_price,
            'orders'=>$orders,
            'nearhotels'=>$nearhotels,
            'hotel_promotions'=>$hotel_promotions,
        ]);
    }



// Function add optional rate to Order ======================================================================================= ==>
    public function func_add_optional_rate_order(Request $request){
        $service_date = date("Y-m-d", strtotime($request->service_date));
        $hotels_id= $request->hotels_id;
        $code = $request->code;
        $checkin = $request->checkin;
        $checkout = $request->checkout;
        $order =new OptionalRateOrder([
            
            "order_id"=>$request->order_id,
            "type"=>$request->type,
            "name"=>$request->name,
            "qty"=>$request->qty,
            "price_unit"=>$request->price_unit,
            "description" =>$request->description,
            "note"=>$request->note,
            "status"=>$request->status,
            "author"=>$request->author,
            "service_date"=>$service_date,
            "optional_rate_id"=>$request->optional_rate_id,
        ]);
        $order->save();
        // USER LOG
        $note = "Add Optional Rate Order";
        $user_log =new UserLog([
            "action"=>$request->action,
            "service"=>$request->service,
            "subservice"=>$request->subservice,
            "subservice_id"=>$request->subservice_id,
            "page"=>$request->page,
            "user_id"=>$request->author,
            "user_ip"=>$request->getClientIp(),
            "note" =>$note, 
        ]);
        // @dd($order);
        return redirect("/orders")->with('success','Your order has been submited, and we will validate your order',[

       
            'hotels_id'=> $hotels_id,
            'code'=> $code,
            'checkin'=>$checkin,
            'checkout'=>$checkout,
        ])->with('success', 'Package added successfully');
    }

}