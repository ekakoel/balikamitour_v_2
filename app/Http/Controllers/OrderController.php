<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Tax;
use App\Models\User;
use App\Models\Guide;
use App\Models\Tours;
use App\Models\Brides;
use App\Models\Guests;
use App\Models\Hotels;
use App\Models\Orders;
use App\Models\Drivers;
use App\Models\LogData;
use App\Models\UserLog;
use App\Models\ExtraBed;
use App\Models\OrderLog;

use App\Models\UsdRates;
use App\Models\Weddings;
use App\Models\Attention;
use App\Models\HotelRoom;
use App\Models\Promotion;
use App\Models\HotelPrice;
use App\Models\HotelPromo;
use App\Models\TourPrices;
use App\Models\Transports;
use App\Models\BookingCode;
use App\Models\Reservation;

use App\Models\HotelPackage;
use App\Models\InvoiceAdmin;
use App\Models\OptionalRate;
use App\Models\OrderWedding;
use Illuminate\Http\Request;
use App\Mail\ReservationMail;
use App\Models\VendorPackage;
use App\Models\AirportShuttle;
use App\Models\TransportPrice;
use App\Models\BusinessProfile;
use App\Models\AdditionalService;
use App\Models\OptionalRateOrder;
use App\Models\PaymentConfirmation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Notifications\NotifikasiWhatsApp;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    public function index()
    {   
        $ord = Orders::all();
        $orderno = count($ord);
        $business = BusinessProfile::where('id','=',1)->first();
        $now = Carbon::now();
        $archived = date('Y-m-d',strtotime('+7 days',strtotime($now)));
        $userid = Auth::user()->id;
        $attentions = Attention::where('page','orders')->get();
        $optional_rate_order = OptionalRateOrder::all();
        $optionalrates = OptionalRate::all();
        $wedding_order = OrderWedding::all();
        $brides = Brides::all();
        $tourorders = Orders::where('service','Tour Package')
            ->where('sales_agent','=', $userid)
            ->where('checkin', '>=' , $now)
            ->where('status','!=',"Removed")
            ->where('status','!=',"Archive")
            ->orderBy('updated_at','DESC')
            ->get();
        $hotelorders = Orders::where(function($result) {
                $result->where("service" , "Hotel")
                ->orWhere('service' , "Hotel Promo")
                ->orWhere('service', "Hotel Package");
            })
            ->where('status','!=',"Removed")
            ->where('status','!=',"Archive")
            ->where('sales_agent','=', $userid)
            ->where('checkin', '>=' , $now)
            ->orderBy('updated_at','DESC')
            ->get();
        // $hotelguestname = json_decode($hotelorders->guest_detail);

        $activityorders = Orders::where('service','Activity')
            ->where('sales_agent','=', $userid)
            ->where('checkin', '>=' , $now)
            ->where('status','!=',"Removed")
            ->where('status','!=',"Archive")
            ->orderBy('updated_at','DESC')
            ->get();
        $transportorders = Orders::where('service','Transport')
            ->where('sales_agent','=', $userid)
            ->where('checkin', '>=' , $now)
            ->where('status','!=',"Removed")
            ->where('status','!=',"Archive")
            ->orderBy('updated_at','DESC')
            ->get();

        $weddingorders = OrderWedding::where('agent_id', $userid)
            ->orderBy('updated_at','DESC')
            ->get();
        $vendorPackages = VendorPackage::all();
        $hotelRooms = HotelRoom::all();


        $orders = Orders::where('sales_agent','=', $userid)
            ->where("status",'!=',"Archive")
            ->where("status",'!=',"Removed")
            ->where('checkin', '>=' , $now)
            ->orderBy('updated_at','DESC')->get();
        
        if (isset($orders->checkin) == "")
            $checkin = $now;
        else
            $checkin = $orders->checkin;

        $activeorders = Orders::where('status','!=', 'Accepted')
            ->where('status','!=', 'Draft')
            ->where('sales_agent','=', $userid)
            ->where('checkin','>', $archived)
            ->orderBy('updated_at', 'DESC')
            ->get();

        $archivedorders = Orders::where('sales_agent','=', $userid)
            ->where('checkin','<', $now)
            ->orderBy('created_at', 'desc')
            ->get();
        $rejectedorders = Orders::where('sales_agent','=', $userid)
            ->where('status', 'Rejected')
            ->orderBy('created_at', 'desc')
            ->get();
        $reservations = Reservation::all();
        return view('main.order',compact('orders'),[
            'orderno'=>$orderno,
            'optionalrates'=>$optionalrates,
            'optional_rate_order'=>$optional_rate_order,
            'attentions'=>$attentions,
            'archivedorders'=>$archivedorders,
            'rejectedorders'=>$rejectedorders,
            'business'=>$business,
            'now'=>$now,
            "checkin"=> $checkin,
            'orders'=> $orders,
            "activeorders"=>$activeorders,
            'weddingorders'=>$weddingorders,
            'vendorPackages'=>$vendorPackages,
            'hotelRooms'=>$hotelRooms,
            'transportorders'=>$transportorders,
            'activityorders'=>$activityorders,
            'hotelorders'=>$hotelorders,
            'tourorders'=>$tourorders,
            'reservations'=>$reservations,
            'wedding_order'=>$wedding_order,
            'brides'=>$brides,
            'userid'=>$userid,
        ]);
    }
    // ORDER ROOM
    public function order_room_promo(Request $request, $id){
        $now = Carbon::now();
        $tax = Cache::remember('tax_1', 3600, fn() => Tax::find(1));
        $usdrates = Cache::remember('usd_rate', 3600, fn() => UsdRates::where('name', 'USD')->first());
        $business = Cache::remember('business_profile', 3600, fn() => BusinessProfile::find(1));
        $attentions = Attention::where('page','user-edit-order')->get();
        $user_id = Auth::id();
        $logoDark = Cache::remember('app.logo_dark', 3600, fn() => config('app.logo_dark'));
        $altLogo = Cache::remember('app.alt_logo', 3600, fn() => config('app.alt_logo'));
        $room = HotelRoom::find($id);
        $service = $request->input('service');
        $orderno = Orders::count();
        if ($service == "Hotel") {
            $orderType ="HNP";
        }elseif ($service == "Hotel Promo") {
            $orderType ="HPP";
        }elseif($service == "Hotel Package"){
            $orderType ="HPA";
        }
        $orderNumber = "ORD".date('Ymd', strtotime($now)).".".$orderType.$orderno;
        $promotions = Promotion::where('status', 'Active')
            ->where('periode_start','<=',$now)
            ->where('periode_end','>=',$now)
            ->get();
        $promotion_price = $promotions->sum('discounts');
        $checkin = dateFormat($request->input('checkin'));
        $checkout = dateFormat($request->input('checkout'));
        if ($service == "Hotel Promo") {
            $hotelPromoId = $request->input('promo_id');
            $uniqueHotelPromoIds = array_unique(json_decode($hotelPromoId));
            $hotelPromos = HotelPromo::whereIn('id',$uniqueHotelPromoIds)->get();
        }else{
            $hotelPromos = NULL;
        }
        $hotel_id = $request->hotel_id;
        $hotel = Hotels::select('id','name','code','region')->find($hotel_id);
        $promo_id = $request->input('promo_id');
        $final_price = $request->input('final_price');
        $package_id = $request->input('package_id');
        $duration = $request->input('duration');

        $price_pax = $request->input('price_pax');
        $normal_price = $request->input('normal_price');
        $kick_back = $request->input('kick_back');
        $kick_back_per_pax = $request->input('kick_back_per_pax');
        $f_price = $normal_price - $kick_back - $promotion_price;
        
        $transports = Transports::select('id','name','brand','capacity')->where('status',"Active")->orderBy('capacity', 'DESC')->get();
        $package = HotelPackage::find($package_id);
        $extrabed = ExtraBed::where('hotels_id',$hotel_id)->get();
        $agents = Auth::User()->where('status',"Active")->where('email_verified_at','!=',"")->get();

        return view('form.order-room', compact('hotel'),[
            'now'=>$now,
            'tax'=>$tax,
            'usdrates'=>$usdrates,
            'business'=>$business,
            'logoDark'=>$logoDark,
            'altLogo'=>$altLogo,
            'extrabed'=>$extrabed,

            'hotel' => $hotel,
            'service' => $service,
            'room' => $room,
            'package' => $package,
            // 'promo_details' => $promoDetails,
            // 'duration' => $request->input('duration', 1),
            // 'checkin' => $request->input('checkin'),
            // 'checkout' => $request->input('checkout'),
            'hotelPromos'=>$hotelPromos,
            'normal_price'=>$normal_price,
            'f_price'=>$f_price,
            'duration'=>$duration,
            'checkin'=>$checkin,
            'checkout'=>$checkout,
            'price_pax'=>$price_pax,
            'kick_back'=>$kick_back,
            'kick_back_per_pax'=>$kick_back_per_pax,
            'agents'=>$agents,
            'final_price'=>$final_price,
            'promotions'=>$promotions,
            'promotion_price'=>$promotion_price,
            'promo_id'=>$promo_id,
            'orderno'=>$orderno,
            'transports'=>$transports,
            'orderNumber'=>$orderNumber,
        ]);
    }

    public function detail_order($id)
    {   
        $order = Orders::find($id);
        if ($order) {
            $usdrates = UsdRates::where('name','USD')->first();
            $attentions = Attention::where('page','orders-detail')->get();
            $business = BusinessProfile::where('id','=',1)->first();
            $optional_rate_order = OptionalRateOrder::all();
            $optionalrates = OptionalRate::all();
            if ($order->status == "Draft") {
                return redirect('/orders')->with('warning',"Submit your order to see order detail");
            }else{
                return view('main.orderdetail',compact('order'),[
                    'usdrates'=>$usdrates,
                    'order'=> $order,
                    'business'=>$business,
                    'optional_rate_order'=>$optional_rate_order,
                    'attentions'=>$attentions,
                ]);
            }
        }else{
            return redirect('/orders')->with(['error_messages',"Order not found"]);
        }
    }

    // USER EDIT ORDER -------------------------------------------------------------------------------------------------------------------------------------------------->
    public function user_edit_order($id)
    {   
        $now = Carbon::now();
        $tax = Cache::remember('tax_1', 3600, fn() => Tax::find(1));
        $usdrates = Cache::remember('usd_rate', 3600, fn() => UsdRates::where('name', 'USD')->first());
        $business = Cache::remember('business_profile', 3600, fn() => BusinessProfile::find(1));
        $attentions = Attention::where('page','user-edit-order')->get();
        $user_id = Auth::id();
        $logoDark = Cache::remember('app.logo_dark', 3600, fn() => config('app.logo_dark'));
        $altLogo = Cache::remember('app.alt_logo', 3600, fn() => config('app.alt_logo'));
        $order = Orders::with([
            'airport_shuttles' => function ($ap_query) {
                $ap_query->select('id','date','price_id','transport_id','src','dst','price','nav', 'order_id');
            },
        ])
        ->where('id', $id)
        ->where('user_id', $user_id)
        ->where('checkin', '>', $now)
        ->first();
        $total_price_airport_shuttle = $order->airport_shuttles->sum('price');
        $hotel = Hotels::find($order->service_id);
        $order_status = "Valid";
        if (isset($order)) {
            $orderData = [
                'nor' => $order->number_of_room,
                'nogr' => json_decode($order->number_of_guests_room, true),
                'guest_detail' => json_decode($order->guest_detail, true),
                'special_day' => json_decode($order->special_day, true),
                'special_date' => json_decode($order->special_date, true),
                'extra_bed' => json_decode($order->extra_bed, true),
                'extra_bed_id' => json_decode($order->extra_bed_id, true),
                'extra_bed_price' => json_decode($order->extra_bed_price, true),
                'order_promos' => json_decode($order->promo_name, true),
                'promotion_discounts' => json_decode($order->promotion_disc, true),
            ];
            $room_price_normal = $orderData['nor'] * $order->normal_price;
            $order_kick_back = $orderData['nor'] * $order->kick_back;
            $optional_service_total_price = ($orderData['nor'] != "" && $order->number_of_guests >= 1) ? $order->optional_price : 0;
            $created_at = date('m/d/Y', strtotime($order->created_at));
            $checkin = date('m/d/Y', strtotime($order->checkin));
            $checkout = date('m/d/Y', strtotime($order->checkout));
            $nor = $orderData['nor'];
            $nogr = $orderData['nogr'];
            $special_date = $orderData['special_date'];
            $special_day = $orderData['special_day'];
            $guest_detail = $orderData['guest_detail'];
            $extra_bed = $orderData['extra_bed'];
            $extra_bed_id = $orderData['extra_bed_id'];
            $extra_bed_price = $orderData['extra_bed_price'];
            $order_promos = $orderData['order_promos'];

            $total_promotion_disc = $orderData['promotion_discounts']?array_sum($orderData['promotion_discounts']):NULL;

            $optionalRates = OptionalRate::with('hotels')
                                ->where('hotels_id', $order->service_id)
                                ->get();
            $optionalRateOrders = $order->optionalRateOrder;
            $decodedOptionalRates = null;
            $optionalServiceTotalPrice = 0;
            if ($optionalRateOrders) {
                $decodedOptionalRates = [
                    'or_id' => json_decode($optionalRateOrders->optional_rate_id, true),
                    'number_of_guest' => json_decode($optionalRateOrders->number_of_guest, true),
                    'or_service_date' => json_decode($optionalRateOrders->service_date, true),
                    'or_service_date' => json_decode($optionalRateOrders->service_date, true),
                    'or_price_pax' => json_decode($optionalRateOrders->price_pax, true),
                    'or_price_total' => json_decode($optionalRateOrders->price_total, true),
                ];
                $optionalServiceTotalPrice = array_sum($decodedOptionalRates['or_price_total']);
            }
            $services = [
                'Hotel' => 'order.edit-order-hotel',
                'Hotel Promo' => 'order.edit-order-hotel',
                'Hotel Package' => 'order.edit-order-hotel',
                'Tour Package' => 'order.edit-order-tour',
                'Activity' => 'order.edit-order-activity',
                'Transport' => 'order.edit-order-transport',
                'Wedding Package' => 'order.edit-order-wedding',
            ];
            $descriptions = [
                'benefits' => __('messages.Benefit'),
                'include' => __('messages.Include'),
                'additional_info' => __('messages.Additional Information'),
                'cancellation_policy' => __('messages.Cancelation Policy'),
            ];
            $isRoomInfoComplete = $nor && $nogr && $guest_detail;
            $pricePerPax = $order->price_pax * $order->duration;
            $totalExtraBedPrice = $order->status !== "Invalid" ? array_sum($extra_bed_price) : 0;
            $totalRoomAndSuite = ($pricePerPax * $nor) + $totalExtraBedPrice;
            $roomDetails = [];
            for ($i = 0; $i < $nor; $i++) {
                $extraBedData = ExtraBed::find($extra_bed_id[$i]);
                $extraBed = $order->extra_beds[$i] ?? null;
                $extraBedName = $extraBedData->name ?? "-";
                $extraBedType = $extraBedData->type ?? "-";
                $extraBedPrice = $extra_bed_price[$i] ?? 0;
                $roomDetails[] = [
                    'room' => $i + 1,
                    'number_of_guests' => $nogr[$i] ?? NULL,
                    'guest_name' => $guest_detail[$i] ?? NULL,
                    'special_date' => $special_date[$i] ?? NULL,
                    'special_day' => $special_day[$i] ?? NULL,
                    'price' => "$" . number_format($pricePerPax, 0, ".", ","),
                    'extra_bed' => $extraBedPrice > 0
                        ? "$extraBedName ($extraBedType) $" . number_format($extraBedPrice, 0, ".", ",")
                        : NULL
                ];
            }
            $extra_beds = ExtraBed::all();
            $transports = Transports::with([
                'prices'=> function ($ap_query) use($hotel){
                    $ap_query->where('duration',$hotel->airport_duration);
                },
                ])->where('status',"Active")->orderBy('capacity', 'DESC')->get();
            $optionalrate_meals = OptionalRate::with('hotels')->where('type',"Meals")->get();
            $optional_rate_orders = OptionalRateOrder::where('order_id', $id)->first();
            $tour_price = TourPrices::where('id',$order->price_id)->first();
            $tour_prices = TourPrices::where('tours_id',$order->subservice_id)
            ->where('status',"Active")
            ->orderBy('max_qty','ASC')->get();
            $qty = TourPrices::max('max_qty');
            if ($order->service == "Tour Package") {
                $tour = Tours::where('id', $order->service_id)->first();
            }else{
                $tour = null;
            }

            if ($order->status == "Draft" or $order->status == "Invalid"){
                return view('order.user-edit-order',compact(
                        'order',
                        'nor',
                        'nogr',
                        'guest_detail',
                        'extra_bed',
                        'extra_bed_id',
                        'extra_bed_price',
                        'orderData',
                        'room_price_normal',
                        'order_kick_back', 
                        'optional_service_total_price',
                        'checkin',
                        'checkout',
                        'services',
                        'descriptions',
                        'isRoomInfoComplete',
                        'pricePerPax',
                        'totalExtraBedPrice',
                        'totalRoomAndSuite',
                        'roomDetails',
                        'total_promotion_disc',
                        'total_price_airport_shuttle',
                    ),[
                    'tax'=>$tax,
                    'now'=>$now,
                    'usdrates'=>$usdrates,
                    'business'=>$business,
                    'attentions'=>$attentions,
                    'logoDark'=>$logoDark,
                    'altLogo'=>$altLogo,
                    'order_status'=>$order_status,
                    'hotel'=>$hotel,
                    'optionalRates'=>$optionalRates,
                    'decodedOptionalRates' => $decodedOptionalRates,
                    'optionalServiceTotalPrice' => $optionalServiceTotalPrice,
                    'transports'=>$transports,
                    'order_promos'=>$order_promos,
                    'created_at'=>$created_at,
                        
                    
                    // 'extra_beds'=>$extra_beds,
                    // 'special_day'=>$orderData['special_day'],
                    // 'optional_rate_orders'=>$optional_rate_orders,
                    // 'optionalrate_meals'=>$optionalrate_meals,
                    // 'tour'=>$tour,
                    // 'order_wedding'=>$order_wedding,
                    // 'bride'=>$bride,
                    // 'wedding'=>$wedding,
                    // 'vendor_wedding_fixed_services'=>$vendor_wedding_fixed_services,
                    // 'vendor_wedding_transports'=>$vendor_wedding_transports,
                    // 'vendor_suites_and_villas'=>$vendor_suites_and_villas,
                    // 't_prices'=>$t_prices,
                    // 'tour_price'=>$tour_price,
                    // 'tour_prices'=>$tour_prices,
                    // 'qty'=>$qty,
                    // 'vendor_services'=>$vendor_services,
                    // 'entertainment_services'=>$entertainment_services,
                    // 'decoration_services'=>$decoration_services,
                ]);
            }else{
                return redirect('/orders')->with('warning',"Your order was not found!");
            }
        }else{
            return redirect('/orders')->with('warning',"Your order was not found!");
        }
    }

    // USER DETAIL ORDER -------------------------------------------------------------------------------------------------------------------------------------------------->
    public function user_detail_order($id)
    {   
        $user_id = Auth::User()->id;
        $now = Carbon::now();
        $order = Orders::where('id', $id)->where('user_id', $user_id)->where('status', "!=", "Removed")->first();
        if ($order){
            $usdrates = UsdRates::where('name','USD')->first();
            $tax = Tax::where('id',1)->first();
            $attentions = Attention::where('page','user-order-detail')->get();
            $business = BusinessProfile::where('id','=',1)->first();
            // $optional_rate_order = OptionalRateOrder::all();
            $optionalrates = OptionalRate::with('hotels')->get();
            $optionalrate_meals = OptionalRate::with('hotels')->where('type',"Meals")->get();
            $optional_rate_orders = OptionalRateOrder::where('order_id', $id)->first();
            $extra_beds = ExtraBed::all();
            $hotel = Hotels::where('id',$order->service_id)->first();
            // $hotel_wedding = Hotels::where('id',$order->subservice_id)->first();
            $reservation = Reservation::where('id', $order->rsv_id)->first();
            $additional_service = json_decode($order->additional_service);
            $additional_service_date = json_decode($order->additional_service_date);
            $additional_service_qty = json_decode($order->additional_service_qty);
            $additional_service_price = json_decode($order->additional_service_price);
            $tour_price = TourPrices::where('id',$order->price_id)->first();
            $transport_in = Transports::where('id',$order->airport_shuttle_in)->first();
            $transport_out = Transports::where('id',$order->airport_shuttle_out)->first();
            $transports = Transports::where('status',"Active")->get();
            $airport_shuttles = AirportShuttle::where('order_id', $order->id)->get();
            $guide = Guide::where('id', $order->guide_id)->first();
            $driver = Drivers::where('id', $order->driver_id)->first();
            $invoice = InvoiceAdmin::where('rsv_id',$order->rsv_id)->first();
           
            if ($invoice) {
                if ($order->service == "Wedding Package") {
                    $receipt = PaymentConfirmation::where('inv_id',$invoice->id)->get();
                }else {
                    $receipt = PaymentConfirmation::where('inv_id',$invoice->id)->first();
                }
            }else{
                $receipt = null;
            }
            if (isset($invoice->due_date)) {
                $due_date = Carbon::parse($invoice->due_date);
                $order_checkin = Carbon::parse($order->checkin);
                $payment_period = $due_date->diffInDays($now);
            }else{
                $due_date = $now;
                $payment_period = 0;
            }

            if ($order->additional_service != "") {
                $additional_service_total_price = [];
                if (is_array($additional_service_price) == 1) {
                    $x = count($additional_service_price);
                    for ($i=0; $i < $x; $i++) { 
                        $price = $additional_service_price[$i] * $additional_service_qty[$i];
                        array_push($additional_service_total_price, $price);
                    }
                    $total_additional_service = array_sum($additional_service_total_price);
                }else{
                    $total_additional_service = 0;
                }
            }else{
                $total_additional_service = 0;
            }
            $guests = Guests::where('rsv_id',$order->rsv_id)->get();

            if (isset($reservation)) {
                $inv_no = "INV-".$reservation->rsv_no;
                if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_en.pdf")) {
                    $status_contract = 1;
                }else{
                    $status_contract = 0;
                }
            }else {
                $inv_no = 0;
                $status_contract = 0;
            }

            if ($order->status == "Draft") {
                return redirect('/orders')->with('warning',"Please submit your order at first, to see order detail");
            }else{
                return view('order.user-detail-order',compact('order'),[
                    'extra_beds'=>$extra_beds,
                    'order'=>$order,
                    'tax'=>$tax,
                    'optionalrates'=>$optionalrates,
                    'now'=>$now,
                    'usdrates'=>$usdrates,
                    'business'=>$business,
                    'optional_rate_orders'=>$optional_rate_orders,
                    'attentions'=>$attentions,
                    'optionalrate_meals'=>$optionalrate_meals,
                    'guests'=>$guests,
                    'additional_service'=>$additional_service,
                    'additional_service_price'=>$additional_service_price,
                    'additional_service_qty'=>$additional_service_qty,
                    'additional_service_date'=>$additional_service_date,
                    'total_additional_service'=>$total_additional_service,
                    'reservation'=>$reservation,
                    'tour_price'=>$tour_price,
                    'inv_no'=>$inv_no,
                    'status_contract'=>$status_contract,
                    'hotel'=>$hotel,
                    'transport_in'=>$transport_in,
                    'transport_out'=>$transport_out,
                    'airport_shuttles'=>$airport_shuttles,
                    'invoice'=>$invoice,
                    'guide'=>$guide,
                    'driver'=>$driver,
                    'due_date'=>$due_date,
                    'payment_period'=>$payment_period,
                    'receipt'=>$receipt,
                ]);
            }
        }else{
            return redirect('/orders')->with('warning',"Not found!");
        }
      
       
    }

    
    // USER EDIT ORDER ROOM --------------------------------------------------------------------------------------------------------------------------------------------->
    public function editorder_room($id) 
    {   
        $now = Carbon::now();
        $user_id = Auth::User()->id;
        $usdrates = Cache::remember('usd_rates', 60, function () {
            return UsdRates::where('name', 'USD')->first();
        });
        $tax = Cache::remember('tax', 60, function () {
            return Tax::where('id', 1)->first();
        });
        $business = Cache::remember('business_profile', 60, function () {
            return BusinessProfile::where('id', 1)->first();
        });
        $attentions = Attention::where('page', 'editorder-room')->get();
        $order = Orders::where('id', $id)
            ->where('user_id', $user_id)
            ->first();
        $duration = Carbon::parse($order->checkin)->diffInDays(Carbon::parse($order->checkout));
        if (!$order || !in_array($order->status, ["Draft", "Rejected", "Invalid"])) {
            return redirect('/orders')->with('error', "Sorry we couldn't find the order.");
        }
        $extrabed = ExtraBed::where('hotels_id', $order->service_id)->get();
        $extrabeds = $extrabed->map(function ($eb) use ($usdrates, $tax, $order) {
            $eb_price = $eb->calculatePrice($usdrates, $tax) * $order->duration;
            $eb->price = $eb_price;
            return $eb;
        });
        $orderData = [
            'number_of_room' => $order->number_of_room,
            'number_of_guests_room' => json_decode($order->number_of_guests_room),
            'guest_detail' => json_decode($order->guest_detail),
            'special_day' => json_decode($order->special_day),
            'special_date' => json_decode($order->special_date),
            'extra_bed' => json_decode($order->extra_bed),
            'extra_bed_id' => json_decode($order->extra_bed_id),
            'extra_bed_price' => json_decode($order->extra_bed_price),
            'price_pax' => json_decode($order->price_pax),
        ];
        $date_stay = [];
        $from = $order->checkin;
        for ($a = 0; $a < $duration; $a++) {
            $date_stay[] = $from;
            $from = date('Y-m-d', strtotime('+1 days', strtotime($from)));
        }
        if (in_array($order->status, ['Draft', 'Rejected', 'Invalid'])) {
            return view('order.edit-room', compact('order','extrabed', 'extrabeds', 'tax', 'now', 'usdrates', 'business', 'attentions','orderData','date_stay'));
        }

        return redirect('/orders')->with('error', "Sorry we couldn't find the order.");
    }


    // USER EDIT ORDER OPTIONAL SERVICE ---------------------------------------------------------------------------------------------------------------------------------> (OPTIMIZED)
    public function editorder_optionalservice($id)
    {
        $now = Carbon::now();
        $tax = Cache::remember('tax_1', 3600, fn() => Tax::find(1));
        $usdrates = Cache::remember('usd_rate', 3600, fn() => UsdRates::where('name', 'USD')->first());
        $business = Cache::remember('business_profile', 3600, fn() => BusinessProfile::find(1));
        $user_id = Auth::id();
        $attentions = Attention::where('page', 'editorder-optionalservice')->get();
        $order = Orders::with([
            'optionalRateOrder' => function ($or_query) {
                $or_query->select('id','order_id','rsv_id','optional_rate_id','number_of_guest','service_date');
            }
        ])
        ->where('id', $id)
        ->where('user_id', $user_id)
        ->where('checkin', '>', $now)
        ->first();

        if (!$order || $order->status == 'Pending' || $order->status == 'Active') {
            return redirect('/orders')->with('error', "Sorry we couldn't find the order.");
        }


        $optional_services = OptionalRate::where('hotels_id', $order->service_id)->get();
        $duration = Carbon::parse($order->checkin)->diffInDays(Carbon::parse($order->checkout));
        $date_stay = [];
        $from = $order->checkin;
        for ($a = 0; $a < $duration; $a++) {
            $date_stay[] = $from;
            $from = date('Y-m-d', strtotime('+1 days', strtotime($from)));
        }
        $optional_rate_orders = $order->optionalRateOrder;
        $service_date = $optional_rate_id = $oro_nog = $oro = null;
        if ($optional_rate_orders) {
            $service_date = json_decode($optional_rate_orders->service_date);
            $oro_nog = json_decode($optional_rate_orders->number_of_guest);
            $optional_rate_id = json_decode($optional_rate_orders->optional_rate_id);
            
        }
        $optionalServicesMap = $optional_services->keyBy('id');
        $formAction = $optional_rate_orders ? 
        route('fupdate.optional.service.order', $optional_rate_orders->id) :
        route('fadd.optional.service.order');
        return view('order.edit-order-optional-service', compact(
            'order',
            'now',
            'usdrates',
            'tax',
            'business',
            'attentions',
            'optional_rate_orders',
            'duration',
            'optional_services',
            'date_stay',
            'service_date',
            'optional_rate_id',
            'oro_nog',
            'formAction',
            'optionalServicesMap',
        ));
        
    }
    
    // FUNCTION UPDATE OPTIONAL SERVICE ORDER ---------------------------------------------------------------------------------------------------------------------------->
    public function func_update_optional_service_order(Request $request, $id)
    {
        // Retrieve the necessary models in bulk
        $optionalrateorder = OptionalRateOrder::findOrFail($id);
        $order = $optionalrateorder->order; // Use relationship to load the order
        $usdrates = UsdRates::where('name', 'USD')->first();
        $tax = Tax::find(1); // Directly use find since there's only one tax entry

        if ($order->status != "Draft") {
            return redirect("/detail-order-$order->id")->with('warning', 'Your order cannot be changed');
        }

        // Avoid multiple json_encode calls by storing data in arrays directly
        $optional_rate_id = $request->optional_rate_id;
        $number_of_guest = $request->number_of_guest;
        $service_date = $request->service_date;

        // Extract promotion discount and extra bed price more efficiently
        $promotion_disc = $order->promotion_disc ? array_sum(json_decode($order->promotion_disc)) : 0;
        $extra_bed_price = array_sum(json_decode($order->extra_bed_price));

        // Calculate optional rates only if there are optional rates
        if ($optional_rate_id) {
            $price_pax_nd = [];
            $price_total_nd = [];
            foreach ($optional_rate_id as $index => $oprid) {
                $optional_rate = OptionalRate::find($oprid); // Direct find to avoid unnecessary queries
                $cr = ceil($optional_rate->contract_rate / $usdrates->rate) + $optional_rate->markup;
                $cr_pajak = ceil(($cr * $tax->tax) / 100);
                $cr_pax = $cr + $cr_pajak;

                // Calculate prices for each service
                $price_pax_nd[] = $cr_pax;
                $price_total_nd[] = $number_of_guest[$index] * $cr_pax;
            }
        }

        // Calculate total price sums
        $or_total_price = array_sum($price_total_nd);
        $price_pax = json_encode($price_pax_nd);
        $or_price_total = json_encode($price_total_nd);

        // Update the OptionalRateOrder
        $optionalrateorder->update([
            "order_id" => $request->order_id,
            "optional_rate_id" => json_encode($optional_rate_id),
            "number_of_guest" => json_encode($number_of_guest),
            "service_date" => json_encode($service_date),
            "price_pax" => $price_pax,
            "price_total" => $or_price_total,
        ]);

        // Calculate the final order prices
        $order_price_total = (($order->normal_price * $order->number_of_room) - $order->kick_back) + $extra_bed_price + $or_total_price;
        $order_final_price = $order_price_total - $order->discounts - $order->bookingcode_disc - $promotion_disc;

        // Update the Order model with new pricing details
        $order->update([
            "optional_price" => $or_total_price,
            "price_total" => $order_price_total,
            "final_price" => $order_final_price,
        ]);

        return redirect("/edit-order-$order->id")->with('success', 'Optional Rate has been updated');
    }

    // USER ADD ORDER ---------------------------------------------------------------------------------------------------------------------------------------------------->
    public function func_add_order_hotel_promo(Request $request){
        if (Auth::user()->position == "developer" || Auth::user()->position == "reservation" || Auth::user()->position == "author") {
            $sales_agent = $request->user_id;
            $user_id = Auth::user()->id;
            $agent = Auth::user()->where('id',$user_id)->first();
            $email = $agent->email;
            $name = $agent->name;
            $status = "Pending";
        }else{
            $sales_agent = Auth::user()->id;
            $user_id = Auth::user()->id;
            $name= Auth::user()->name;
            $email= Auth::user()->email;
            $status = "Draft";
        }
        $orderno = $request->orderno;
        $order =new Orders([
            'user_id'=>$user_id,
            // 'rsv_id'=>$rsv_id,
            'orderno'=>$orderno,
            // 'confirmation_order'=>$confirmation_order,
            'name'=>$name,
            'email'=>$email,
            'servicename'=>$servicename,
            'service'=>$service,
            // 'service_type'=>$service_type,
            'service_id'=>$service_id,
            'subservice'=>$subservice,
            'subservice_id'=>$subservice_id,
            // 'extra_time'=>$extra_time,
            // 'price_id'=>$price_id,
            'checkin'=>$checkin,
            'checkout'=>$checkout,
            // 'traveldate'=>$traveldate,
            'location'=>$location,
            // 'src'=>$src,
            // 'dst'=>$dst,
            // 'tour_type'=>$tour_type,
            // 'itinerary'=>$itinerary,
            'number_of_guests'=>$number_of_guests,
            'number_of_guests_room'=>$number_of_guests_room,
            'guest_detail'=>$guest_detail,
            'request_quotation'=>$request_quotation,
            // 'wedding_order_id'=>$wedding_order_id,
            // 'wedding_date'=>$wedding_date,
            // 'bride_name'=>$bride_name,
            // 'groom_name'=>$groom_name,
            'special_day'=>$special_day,
            'special_date'=>$special_date,
            'extra_bed'=>$extra_bed,
            'capacity'=>$capacity,
            'benefits'=>$benefits,
            'booking_code'=>$booking_code,
            'include'=>$include,
            'additional_info'=>$additional_info,
            // 'destinations'=>$destinations,
            // 'msg'=>$msg,
            'number_of_room'=>$number_of_room,
            'duration'=>$duration,
            'price_pax'=>$price_pax,
            'normal_price'=>$normal_price,
            'kick_back'=>$kick_back,
            'kick_back_per_pax'=>$kick_back_per_pax,
            'extra_bed_id'=>$extra_bed_id,
            'extra_bed_price'=>$extra_bed_price,
            'extra_bed_total_price'=>$extra_bed_total_price,
            'price_total'=>$price_total,
            'optional_price'=>$optional_price,
            'alasan_discounts'=>$alasan_discounts,
            'discounts'=>$discounts,
            'bookingcode'=>$bookingcode,
            'bookingcode_disc'=>$bookingcode_disc,
            'promotion'=>$promotion,
            'promotion_disc'=>$promotion_disc,
            'additional_service_date'=>$additional_service_date,
            'additional_service'=>$additional_service,
            'additional_service_qty'=>$additional_service_qty,
            'additional_service_price'=>$additional_service_price,
            'airport_shuttle_price'=>$airport_shuttle_price,
            'order_tax'=>$order_tax,
            'final_price'=>$final_price,
            'usd_rate'=>$usd_rate,
            'cny_rate'=>$cny_rate,
            'twd_rate'=>$twd_rate,
            // 'package_name'=>$package_name,
            'promo_name'=>$promo_name,
            'book_period_start'=>$book_period_start,
            'book_period_end'=>$book_period_end,
            'period_start'=>$period_start,
            'period_end'=>$period_end,
            'status'=>$status,
            'sales_agent'=>$sales_agent,
            'arrival_flight'=>$arrival_flight,
            'arrival_time'=>$arrival_time,
            'airport_shuttle_in'=>$airport_shuttle_in,
            'departure_flight'=>$departure_flight,
            'departure_time'=>$departure_time,
            'airport_shuttle_out'=>$airport_shuttle_out,
            'notification'=>$notification,
            'note'=>$note,
            'cancelation_policy'=>$cancelation_policy,
            'verified_by'=>$verified_by,
            'handled_by'=>$handled_by,
            'handled_date'=>$handled_date,
            'driver_id'=>$driver_id,
            'guide_id'=>$guide_id,
            // 'pickup_name'=>$pickup_name,
            // 'pickup_date'=>$pickup_date,
            // 'pickup_location'=>$pickup_location,
            // 'dropoff_date'=>$dropoff_date,
            // 'dropoff_location'=>$dropoff_location,
        ]);
        $user_log =new UserLog([
            "action"=>$request->action,
            "service"=>$request->service,
            "subservice"=>$request->subservice,
            "subservice_id"=>$order->id,
            "page"=>$request->page,
            "user_id"=>$user_id,
            "user_ip"=>$request->getClientIp(),
            "note" =>$note, 
        ]);
        $user_log->save();
        $order_log =new OrderLog([
            "order_id"=>$order->id,
            "action"=>"Create Order",
            "url"=>$request->getClientIp(),
            "method"=>"Create",
            "agent"=>$order->name,
            "admin"=>Auth::user()->id,
        ]);
        $order_log->save();
        session()->forget('booking_dates');

        if (Auth::user()->position == "developer" || Auth::user()->position == "reservation" || Auth::user()->position == "author") {
            $rquotation = $request->request_quotation;
            
            Mail::to(config('app.reservation_mail'))
            ->send(new ReservationMail($order->id,$rquotation));
            return redirect('/orders-admin-'.$order->id)->with('success',__('messages.The order has been successfully created'));
        }else{
            return redirect('/edit-order-'.$order->id)->with('success',__('messages.Your order has been added to the order basket. Please ensure that all details are entered correctly before you confirm the order for further processing.'));
        }
    }

    // USER ADD ORDER ---------------------------------------------------------------------------------------------------------------------------------------------------->
    public function func_add_order(Request $request){
        if (Auth::user()->position == "developer" || Auth::user()->position == "reservation" || Auth::user()->position == "author") {
            $sales_agent = $request->user_id;
            $user_id = Auth::user()->id;
            $agent = Auth::user()->where('id',$user_id)->first();
            $email = $agent->email;
            $name = $agent->name;
            $status = "Pending";
        }else{
            $sales_agent = Auth::user()->id;
            $user_id = Auth::user()->id;
            $name= Auth::user()->name;
            $email= Auth::user()->email;
            $status = "Draft";
        }

        $now = Carbon::now();
        $nog = $request->number_of_guests;
        $service = $request->service;
        $service_type = $request->service_type;
        $usdrates = UsdRates::where('name','USD')->first();
        $cnyrates = UsdRates::where('name','CNY')->first();
        $twdrates = UsdRates::where('name','TWD')->first();
        $idrrates = UsdRates::where('name','IDR')->first();
        $tax = Tax::where('id',1)->first();
        $hotel = Hotels::find($request->service_id);
        if ($service != "Hotel Promo") {
            $prms = Promotion::where('status','Active')
            ->where("periode_start",'<=',$now)
            ->where('periode_end','>=',$now)
            ->get();
            if(count($prms)>0){
                $p_name = [];
                $p_disc = [];
                foreach ($prms as $prm) {
                    array_push($p_name,$prm->name);
                    array_push($p_disc,$prm->discounts);
                }
                $promotion_total_disc = array_sum($p_disc);
                $promotion = json_encode($p_name);
                $promotion_disc = json_encode($p_disc);
            }else{
                $promotion_total_disc = 0;
                $promotion= null;
                $promotion_disc = null;
            }
        }else{
            $promotion_total_disc = null;
            $promotion= null;
            $promotion_disc = null;
        }
        
        $bcode = BookingCode::where('id',$request->bookingcode_id)->first();
        $wedding_date = date('Y-m-d',strtotime($request->wedding_date))." ".date('H.i',strtotime($request->wedding_date));
        if (isset($bcode)) {
            if ($bcode->expired_date > $now) {
                if ($bcode->amount == 0) {
                    $bookingcode = $bcode->code;
                    $bookingcode_disc = $bcode->discounts;
                    $bookingcode_status = "Valid";
                }elseif ($bcode->used < $bcode->amount) {
                    $ordercode = Orders::where('user_id',$user_id)
                    ->where('bookingcode', $bcode->code)->first();
                    if (isset($ordercode)) {
                        $bookingcode = null;
                        $bookingcode_disc = 0;
                        $bookingcode_status = "Used"; //code telah digunakan
                    }else{
                        $bookingcode = $bcode->code;
                        $bookingcode_disc = $bcode->discounts;
                        $bookingcode_status = "Valid";
                    }
                }else{
                    $bookingcode = null;
                    $bookingcode_disc = 0;
                    $bookingcode_status = "Expired"; //code habis digunakan
                }
            }else{
                $bookingcode = null;
                $bookingcode_disc = 0;
                $bookingcode_status = "Expired"; //code kedaluarsa
            }
        }else{
            $bookingcode = null;
            $bookingcode_disc = 0;
            $bookingcode_status = "Invalid"; //code habis digunakan
        }

        if ($service == "Tour Package") {
            // REQUEST 
            $number_of_guests_room = null;
            $number_of_room = null;
            $extra_bed = null;
            $extra_bed_price = null;
            $special_date = null;
            $special_day = null;
            $kick_back = null;
            $kick_back_per_pax = null;

            $tp_id = Tours::where('id',$request->tour_id)->first();
            $pickup_name = null;
            $number_of_guests = $request->number_of_guests;
            $price_pax = $request->price_pax;
            $normal_price = $price_pax * $number_of_guests;
            $price_total = $normal_price;
            $final_price = $normal_price - $bookingcode_disc - $promotion_total_disc;
            $guest_detail = $request->guest_detail;
            $include = $request->include;
            $benefits = $request->benefits;
            $additional_info = $request->additional_info;
            $cancellation_policy = $request->cancellation_policy;
            $checkin = date('Y-m-d', strtotime($request->travel_date));
            if ($request->duration == "1D"){
                $checkout = date('Y-m-d',strtotime($checkin));
                $duration = 1;
            } elseif ($request->duration == "2D/1N"){
                $checkout = date('Y-m-d',strtotime('+1 days',strtotime($checkin)));
                $duration = 2;
            } elseif ($request->duration == "3D/2N"){
                $checkout = date('Y-m-d',strtotime('+2 days',strtotime($checkin)));
                $duration = 3;
            } elseif ($request->duration == "4D/3N"){
                $checkout = date('Y-m-d',strtotime('+3 days',strtotime($checkin)));
                $duration = 4;
            } elseif ($request->duration == "5D/4N"){
                $checkout = date('Y-m-d',strtotime('+4 days',strtotime($checkin)));
                $duration = 5;
            } elseif ($request->duration == "6D/5N"){
                $checkout = date('Y-m-d',strtotime('+5 days',strtotime($checkin)));
                $duration = 6;
            } else {
                $checkout = date('Y-m-d',strtotime('+6 days',strtotime($checkin)));
                $duration = 7;
            }
            $travel_date = $checkin;
            $orderWedding_id = "";
        } elseif ($service == "Activity") {
            $special_date = $request->special_date;
            $special_day = $request->special_day;
            $number_of_guests_room = $request->number_of_guests_room;
            $number_of_room = $request->number_of_room;
            $guest_detail = $request->guest_detail;
            $number_of_guests = $request->number_of_guests;
            $extra_bed = $request->extra_bed;
            $price_total = $request->price_pax * $nog;
            $checkin = date('Y-m-d', strtotime($request->travel_date));
            $checkout = date('Y-m-d', strtotime($request->checkout));
            $duration = $request->duration;
            $price_pax = $request->price_pax;
            $kick_back = $request->kick_back;
            $kick_back_per_pax = $request->kick_back_per_pax;
            $travel_date = $checkin;
            $extra_bed_price = $request->extra_bed_price;
            $normal_price = $price_total;
            $final_price = $normal_price - $bookingcode_disc - $promotion_total_disc;
            $include = $request->include;
            $benefits = $request->benefits;
            $additional_info = $request->additional_info;
            $cancellation_policy = $request->cancellation_policy;
            $pickup_name = null;
            $orderWedding_id = "";
        } elseif ($service == "Hotel") {
            $duration = $request->duration;
            $number_of_room = count($request->number_of_guests);
            $extra_bed_proses = [];
            foreach ($request->number_of_guests as $jk) {
                if ($jk < 3 ) {
                    array_push($extra_bed_proses,'No');
                }else{
                    array_push($extra_bed_proses,'Yes');
                }
            }
            $extra_bed_id_price = [];          
            for ($i=0; $i < $number_of_room; $i++) { 
                if ($extra_bed_proses[$i] == "Yes") {
                    if ($request->extra_bed_id[$i] == 0) {
                        array_push($extra_bed_id_price,0);
                    }else{
                        $extrabeds = ExtraBed::where('id',$request->extra_bed_id[$i])->first();
                        if (isset($extrabeds->contract_rate)) {
                            $contract_rate_eb = ceil($extrabeds->contract_rate/$usdrates->rate)+$extrabeds->markup;
                            $tax_usd_extra_bed = ceil(($contract_rate_eb * $tax->tax)/100);
                            $price_extra_bed = ($contract_rate_eb + $tax_usd_extra_bed)*$duration; 
                            array_push($extra_bed_id_price,$price_extra_bed);
                        }else{
                            array_push($extra_bed_id_price,0);
                        }
                    } 
                }else{
                    array_push($extra_bed_id_price,0);
                }
            }
            $promo_name = NULL;
            $book_period_start = NULL;
            $book_period_end = NULL;
            $period_start = NULL;
            $period_end = NULL;

            $include = $request->include;
            $benefits = $request->benefits;
            $additional_info = $request->additional_info;
            $cancellation_policy = $request->cancellation_policy;
            $extra_bed_id = json_encode($request->extra_bed_id);
            $extra_bed_price = json_encode($extra_bed_id_price);
            $extra_bed = json_encode($extra_bed_proses);
            $number_of_guests_room_array = array_sum($request->number_of_guests);
            $number_of_guests_room = json_encode($request->number_of_guests);
            $number_of_guests = json_encode($number_of_guests_room_array);
            $guest_detail = json_encode($request->guest_detail);
            $special_day = json_encode($request->special_day);
            $special_date = json_encode($request->special_date);
            $extra_bed_sum= array_sum($extra_bed_id_price);
            $extra_bed_total = json_encode($extra_bed_sum);
            $checkin = date('Y-m-d', strtotime($request->checkin));
            $checkout = date('Y-m-d', strtotime($request->checkout));
            $pickup_name = null;
            $kick_back_per_pax = $request->kick_back_per_pax;
            $kick_back = $request->kick_back;
            $normal_price = $request->normal_price;
            $price_pax = $normal_price / $duration;
            $price_total = ($normal_price * $number_of_room) + $extra_bed_sum - $kick_back ;
            $final_price = $price_total - $bookingcode_disc - $promotion_total_disc;
            $orderWedding_id = "";
        } elseif ($service == "Hotel Promo") {
            $duration = $request->duration;
            $number_of_room = count($request->number_of_guests);
            $proId = json_decode($request->promo_id);
            $prId = array_unique($proId); 
            $extra_bed_proses = [];
            $extra_bed_id_price = [];
            $extrabed_id = [];
            $promoName = [];

            $bookPeriodStart = [];
            $bookPeriodEnd = [];
            $periodStart = [];
            $periodEnd = [];
            $promoBenefits = [];
            $promoInclude = [];
            $room = HotelRoom::find($request->subservice_id);
            foreach ($request->number_of_guests as $jk) {
                if ($jk < 3 ) {
                    array_push($extra_bed_proses,'No');
                }else{
                    array_push($extra_bed_proses,'Yes');
                }
            }
            
            for ($i=0; $i < $number_of_room; $i++) { 
                if ($extra_bed_proses[$i] == "Yes") {
                    $extrabeds = ExtraBed::find($request->extra_bed_id[$i]);
                    $price_extra_bed = $extrabeds->calculatePrice($usdrates, $tax)*$duration; 
                    array_push($extra_bed_id_price,$price_extra_bed);
                    array_push($extrabed_id,$extrabeds->id);
                }else{
                    array_push($extra_bed_id_price,0);
                    array_push($extrabed_id,NULL);
                }
            }
            foreach ($prId as $pr_id) {
                $h_promo = HotelPromo::find($pr_id);
                if ($h_promo) {
                    array_push($promoName,$h_promo->name);
                    array_push($bookPeriodStart,$h_promo->book_periode_start);
                    array_push($bookPeriodEnd,$h_promo->book_periode_end);
                    array_push($periodStart,$h_promo->periode_start);
                    array_push($periodEnd,$h_promo->periode_end);
                    array_push($promoBenefits,$h_promo->benefits);
                    array_push($promoInclude,$h_promo->include);
                }
            }
            $promo_id = json_encode($prId);
            $promo_name = json_encode($promoName);
            $book_period_start = json_encode($bookPeriodStart);
            $book_period_end = json_encode($bookPeriodEnd);
            $period_start = json_encode($periodStart);
            $period_end = json_encode($periodEnd);
            $benefits = json_encode($promoBenefits);
            $extra_bed_id = json_encode($extrabed_id);
            $extra_bed_price = json_encode($extra_bed_id_price);
            $extra_bed = json_encode($extra_bed_proses);
            $additional_info = $hotel->additionalInfo;
            $cancellation_policy = $hotel->cancellation_policy;
            $include = json_encode($promoInclude);
            $special_day = json_encode($request->special_day);
            $special_date = json_encode($request->special_date);

            $number_of_guests_room_array = array_sum($request->number_of_guests);
            $number_of_guests_room = json_encode($request->number_of_guests);
            $number_of_guests = json_encode($number_of_guests_room_array);
            $guest_detail = json_encode($request->guest_detail);
            $total_extra_bed_price= array_sum($extra_bed_id_price);
            $checkin = date('Y-m-d', strtotime(session("booking_dates.checkin")));
            $checkout = date('Y-m-d', strtotime(session("booking_dates.checkout")));
            // $checkin = date('Y-m-d', strtotime($request->checkin));
            // $checkout = date('Y-m-d', strtotime($request->checkout));
            $pickup_name = null;
            $orderWedding_id = "";
            // if ($room) {
            //     $include = $room->include;
            // }
            // if (isset($request->benefits)) {
            //     $bnf = json_decode($request->benefits);
            //     if (isset($bnf)) {
            //         if (count($bnf)>0) {
            //             $benefits = implode($bnf);
            //         }else{
            //             $benefits = $request->benefits;
            //         }
            //     }else{
            //         $benefits = $request->benefits;
            //     }
            //     $benefits = $request->benefits;
            // }else{
            //     $benefits = $request->benefits;
            // }
            // if (isset($request->additional_info)) {
            //     $addinf = json_decode($request->additional_info);
            //     if (isset($addinf)) {
            //         if (count($addinf)>0) {
            //             $additional_info = implode($addinf);
            //         }else{
            //             $additional_info = $request->cancellation_policy;
            //         }
            //     }else{
            //         $additional_info = $request->additional_info;
            //     }
            // }else{
            //     $additional_info = $request->additional_info;
            // }
        } elseif ($service == "Hotel Package") {
            $duration = $request->duration;
            $number_of_room = count($request->number_of_guests);
            $extra_bed_proses = [];
            foreach ($request->number_of_guests as $jk) {
                if ($jk < 3 ) {
                    array_push($extra_bed_proses,'No');
                }else{
                    array_push($extra_bed_proses,'Yes');
                }
            }
            $extra_bed_id_price = [];          
            for ($i=0; $i < $number_of_room; $i++) { 
                if ($extra_bed_proses[$i] == "Yes") {
                    if ($request->extra_bed_id[$i] == 0) {
                        array_push($extra_bed_id_price,null);
                    }else{
                        $extrabeds = ExtraBed::where('id',$request->extra_bed_id[$i])->first();
                        $contract_rate_eb = ceil($extrabeds->contract_rate/$usdrates->rate)+$extrabeds->markup;
                        $tax_usd_extra_bed = ceil(($contract_rate_eb * $tax->tax)/100);
                        $price_extra_bed = ($contract_rate_eb + $tax_usd_extra_bed)*$duration; 
                        array_push($extra_bed_id_price,$price_extra_bed);
                    } 
                }else{
                    array_push($extra_bed_id_price,0);
                }
            }
            $include = $request->include;
            $benefits = $request->benefits;
            $additional_info = $request->additional_info;
            $cancellation_policy = $request->cancellation_policy;
            $duration = $request->duration;
            $number_of_room = count($request->number_of_guests);
            $extra_bed_proses = [];
            foreach ($request->number_of_guests as $jk) {
                if ($jk < 3 ) {
                    array_push($extra_bed_proses,'No');
                }else{
                    array_push($extra_bed_proses,'Yes');
                }
            }
            $extra_bed_id_price = [];          
            for ($i=0; $i < $number_of_room; $i++) { 
                if ($extra_bed_proses[$i] == "Yes") {
                    if ($request->extra_bed_id[$i] == 0) {
                        array_push($extra_bed_id_price,null);
                    }else{
                        $extrabeds = ExtraBed::where('id',$request->extra_bed_id[$i])->first();
                        $contract_rate_eb = ceil($extrabeds->contract_rate/$usdrates->rate)+$extrabeds->markup;
                        $tax_usd_extra_bed = ceil(($contract_rate_eb * $tax->tax)/100);
                        $price_extra_bed = ($contract_rate_eb + $tax_usd_extra_bed)*$duration; 
                        array_push($extra_bed_id_price,$price_extra_bed);
                    } 
                }else{
                    array_push($extra_bed_id_price,0);
                }
            }
            $extra_bed_id = json_encode($request->extra_bed_id);
            $extra_bed_price = json_encode($extra_bed_id_price);
            $extra_bed = json_encode($extra_bed_proses);
            $number_of_guests_room_array = array_sum($request->number_of_guests);
            $number_of_guests_room = json_encode($request->number_of_guests);
            $number_of_guests = json_encode($number_of_guests_room_array);
            $guest_detail = json_encode($request->guest_detail);
            $special_day = json_encode($request->special_day);
            $special_date = json_encode($request->special_date);
            $extra_bed_sum= array_sum($extra_bed_id_price);
            $extra_bed_total = json_encode($extra_bed_sum);
            $checkin = date('Y-m-d', strtotime($request->checkin));
            $checkout = date('Y-m-d', strtotime($request->checkout));
            $pickup_name = null;
            $kick_back_per_pax = $request->kick_back_per_pax;
            $kick_back = $request->kick_back;
            $normal_price = $request->normal_price;
            $price_pax = $normal_price / $duration;
            $price_total = ($normal_price * $number_of_room) + $extra_bed_sum ;
            $final_price = $price_total - $bookingcode_disc - $promotion_total_disc;
            $orderWedding_id = "";
        } elseif ($service == "Transport") {
            $checkin = date('Y-m-d H.i', strtotime($request->travel_date));
            if ($service_type == "Daily Rent") {
                $price_pax = $request->price_pax;
                $normal_price = $request->normal_price * $request->duration;
                $price_total = $request->price_total * $request->duration;
                $final_price = $price_total - $promotion_total_disc - $bookingcode_disc;
                $checkout = date('Y-m-d H.i',strtotime('+'.$request->duration.'days',strtotime($checkin)));
            } else {
                $normal_price = $request->normal_price;
                $price_pax = $request->price_pax;
                $price_total = $request->price_total;
                $final_price = $price_total - $promotion_total_disc - $bookingcode_disc;
                $checkout = date('Y-m-d H.i', strtotime('+'.$request->duration.'hours',strtotime($checkin)));
            }
            $special_date = $request->special_date;
            $special_day = $request->special_day;
            $number_of_guests_room = $request->number_of_guests_room;
            $number_of_room = $request->number_of_room;
            $guest_detail = $request->guest_detail;
            $extra_bed = $request->extra_bed;
            $number_of_guests = $request->number_of_guests;
            $duration = $request->duration;
            $pickup_name = null;
            $kick_back = $request->kick_back;
            $kick_back_per_pax = $request->kick_back_per_pax;
            $extra_bed_price = $request->extra_bed_price;
            $include = $request->include;
            $benefits = $request->benefits;
            $additional_info = $request->additional_info;
            $cancellation_policy = $request->cancellation_policy;
            $orderWedding_id = "";
        } elseif ($service == "Wedding Package") {
            $brides =new Brides([
                "bride"=>$request->bride_name,
                "bride_chinese"=>$request->bride_chinese,
                "bride_contact"=>$request->bride_contact,
                "groom"=>$request->groom_name,
                "groom_chinese"=>$request->groom_chinese,
                "groom_contact"=>$request->groom_contact,
            ]);
            $brides->save();
            $special_date = $request->special_date;
            $special_day = $request->special_day;
            $number_of_guests = $request->number_of_guests;
            $checkin = date('Y-m-d', strtotime($request->checkin));
            if ($request->duration == "1D"){
                $checkout = date('Y-m-d',strtotime($checkin));
            } elseif ($request->duration == "2D/1N"){
                $checkout = date('Y-m-d',strtotime('+1 days',strtotime($checkin)));
            } elseif ($request->duration == "3D/2N"){
                $checkout = date('Y-m-d',strtotime('+2 days',strtotime($checkin)));
            } elseif ($request->duration == "4D/3N"){
                $checkout = date('Y-m-d',strtotime('+3 days',strtotime($checkin)));
            } elseif ($request->duration == "5D/4N"){
                $checkout = date('Y-m-d',strtotime('+4 days',strtotime($checkin)));
            } elseif ($request->duration == "6D/5N"){
                $checkout = date('Y-m-d',strtotime('+5 days',strtotime($checkin)));
            } elseif ($request->duration == "7D/6N"){
                $checkout = date('Y-m-d',strtotime('+6 days',strtotime($checkin)));
            } elseif ($request->duration == "8D/7N"){
                $checkout = date('Y-m-d',strtotime('+7 days',strtotime($checkin)));
            } else {
                $checkout = date('Y-m-d',strtotime('+8 days',strtotime($checkin)));
            }

            
            $number_of_guests_room = $request->number_of_guests_room;
            $number_of_room = $request->number_of_room;
            $guest_detail = $request->guest_detail;
            $extra_bed = $request->extra_bed;
            $in=Carbon::parse($checkin);
            $out=Carbon::parse($checkout);
            $duration = $in->diffInDays($out);
            $pickup_name = $brides->id;
            $include = $request->include;
            $benefits = $request->benefits;
            $additional_info = $request->additional_info;
            $cancellation_policy = $request->cancellation_policy;
            $wedding = Weddings::where('id',$request->wedding_id)->first();
            $hotel = Hotels::where('id',$wedding->hotel_id)->firstOrFail();
            if ($wedding->fixed_services_id !== null or $wedding->fixed_services_id) {
                $wed_fixed_services_id = json_decode($wedding->fixed_services_id);
                $fixed_services_p = [];
                if ($wed_fixed_services_id) {
                    foreach ($wed_fixed_services_id as $w_fixed_service_id) {
                        $wedding_fixed_services = VendorPackage::where('id',$w_fixed_service_id)->first();
                        if ($wedding_fixed_services) {
                            array_push($fixed_services_p,$wedding_fixed_services->publish_rate);
                        }
                    }
                    if ($fixed_services_p) {
                        $fixed_service_price = array_sum($fixed_services_p);
                    }else{
                        $fixed_service_price = 0;
                    }
                }else{
                    $fixed_service_price = 0;
                }
            }else{
                $fixed_service_price = 0;
            }
            if ($request->wedding_venue_id !== null or $request->wedding_venue_id) {
                $wedding_venue_id = json_encode($request->wedding_venue_id);
                $wed_venue_id = $request->wedding_venue_id;
                $venue_p = [];
                if ($wed_venue_id) {
                    foreach ($wed_venue_id as $w_venue_id) {
                        $wedding_venue = VendorPackage::where('id',$w_venue_id)->first();
                        if ($wedding_venue) {
                            array_push($venue_p,$wedding_venue->publish_rate);
                        }
                    }
                    if ($venue_p) {
                        $venue_price = array_sum($venue_p);
                    }else{
                        $venue_price = 0;
                    }
                }else{
                    $venue_price = 0;
                }
            }else{
                $venue_price = 0;
                $wedding_venue_id = $request->wedding_venue_id;
            }
            // WEDDING MAKEUP
            if ($request->makeup_id !== null or $request->makeup_id) {
                $makeup_id = json_encode($request->makeup_id);
                $wed_makeup_id = $request->makeup_id;
                if ($wed_makeup_id) {
                    $makeup_p = [];
                    foreach ($wed_makeup_id as $w_makeup_id) {
                        $wedding_makeup = VendorPackage::where('id',$w_makeup_id)->first();
                        if ($wedding_makeup) {
                            array_push($makeup_p,$wedding_makeup->publish_rate);
                        }
                    }
                    if ($makeup_p) {
                        $makeup_price = array_sum($makeup_p);
                    }else{
                        $makeup_price = 0;
                    }
                }else{
                    $makeup_price = 0;
                }
            }else{
                $makeup_price = 0;
                $makeup_id = $request->makeup_id;
            }
            // WEDDING SUITES AND VILLAS
            if ($request->suite_and_villas_id !== null or $request->suite_and_villas_id) {
                $suite_and_villas_id = json_encode($request->suite_and_villas_id);
                $wed_room_id = $request->suite_and_villas_id;
                if ($wed_room_id) {
                    $room_p = [];
                    foreach ($wed_room_id as $w_room_id) {
                        $hotel_room_price = HotelPrice::where('rooms_id',$w_room_id)
                        ->where('start_date','<',$wedding_date)
                        ->where('end_date','>',$wedding_date)
                        ->first();
                        if ($hotel_room_price) {
                            $cr_mr_room = ($hotel_room_price->contract_rate / $usdrates->rate) + $hotel_room_price->markup;
                            $room_tax = $cr_mr_room * ($tax->tax/100);
                            $hotel_r_p = ceil($cr_mr_room + $room_tax) * $duration;
                            array_push($room_p,$hotel_r_p);
                        }
                    }
                    if ($room_p) {
                        $room_price = array_sum($room_p);
                    }else{
                        $room_price = 0;
                    }
                }else{
                    $room_price = 0;
                }
            }else{
                $room_price = 0;
                $suite_and_villas_id = $request->suite_and_villas_id;
            }
            // WEDDING DOCUMENTATION
            if ($request->documentations_id !== null or $request->documentations_id) {
                $documentations_id = json_encode($request->documentations_id);
                $wed_documentations_id = $request->documentations_id;
                if ($wed_documentations_id) {
                    $documentations_p = [];
                    foreach ($wed_documentations_id as $w_documentations_id) {
                        $wedding_documentations = VendorPackage::where('id',$w_documentations_id)->first();
                        if ($wedding_documentations) {
                            array_push($documentations_p,$wedding_documentations->publish_rate);
                        }
                    }
                    if ($documentations_p) {
                        $documentation_price = array_sum($documentations_p);
                    }else{
                        $documentation_price = 0;
                    }
                }else{
                    $documentation_price = 0;
                }
            }else{
                $documentation_price = 0;
                $documentations_id = $request->documentations_id;
            }
            // WEDDING DECORATION
            if ($request->decorations_id !== null or $request->decorations_id) {
                $decorations_id = json_encode($request->decorations_id);
                $wed_decorations_id = $request->decorations_id;
                if ($wed_decorations_id) {
                    $decorations_p = [];
                    foreach ($wed_decorations_id as $w_decorations_id) {
                        $wedding_decorations = VendorPackage::where('id',$w_decorations_id)->first();
                        if ($wedding_decorations) {
                            array_push($decorations_p,$wedding_decorations->publish_rate);
                        }
                    }
                    if ($decorations_p) {
                        $decoration_price = array_sum($decorations_p);
                    }else{
                        $decoration_price = 0;
                    }
                }else{
                    $decoration_price = 0;
                }
            }else{
                $decoration_price = 0;
                $decorations_id = $request->decorations_id;
            }
            // WEDDING DINNER VENUE
            if ($request->dinner_venue_id !== null or $request->dinner_venue_id) {
                $dinner_venue_id = json_encode($request->dinner_venue_id);
                $wed_dinner_venue_id = $request->dinner_venue_id;
                if ($wed_dinner_venue_id) {
                    $dinner_venue_p = [];
                    foreach ($wed_dinner_venue_id as $w_dinner_venue_id) {
                        $wedding_dinner_venue = VendorPackage::where('id',$w_dinner_venue_id)->first();
                        if ($wedding_dinner_venue) {
                            array_push($dinner_venue_p,$wedding_dinner_venue->publish_rate);
                        }
                    }
                    if ($dinner_venue_p) {
                        $dinner_venue_price = array_sum($dinner_venue_p);
                    }else{
                        $dinner_venue_price = 0;
                    }
                }else{
                    $dinner_venue_price = 0;
                }
            }else{
                $dinner_venue_price = 0;
                $dinner_venue_id = $request->dinner_venue_id;
            }
            // WEDDING ENTERTAINMENT
            if ($request->entertainments_id !== null or $request->entertainments_id) {
                $entertainments_id = json_encode($request->entertainments_id);
                $wed_entertainment_id = $request->entertainments_id;
                if ($wed_entertainment_id) {
                    $entertainment_p = [];
                    foreach ($wed_entertainment_id as $w_entertainment_id) {
                        $wedding_entertainment = VendorPackage::where('id',$w_entertainment_id)->first();
                        if ($wedding_entertainment) {
                            array_push($entertainment_p,$wedding_entertainment->publish_rate);
                        }
                    }
                    if ($entertainment_p) {
                        $entertainment_price = array_sum($entertainment_p);
                    }else{
                        $entertainment_price = 0;
                    }
                }else{
                    $entertainment_price = 0;
                }
            }else{
                $entertainment_price = 0;
                $entertainments_id = $request->entertainments_id;
            }

            // WEDDING TRANSPORT
            if ($request->transport_id !== null or $request->transport_id) {
                $transport_id = json_encode($request->transport_id);
                $wed_transport_id = $request->transport_id;
                if ($wed_transport_id) {
                    $transport_p = [];
                    foreach ($wed_transport_id as $w_transport_id) {
                        $wedding_transport = TransportPrice::where('transports_id',$w_transport_id)
                        ->where('type','Airport Shuttle')
                        ->where('duration',$hotel->airport_duration)
                        ->first();
                        if ($wedding_transport) {
                            $trans_cr_mr = ceil($wedding_transport->contract_rate / $usdrates->rate)+$wedding_transport->markup;
                            $trans_tax = $trans_cr_mr * ($tax->tax/100);
                            $transport_p_price = ceil($trans_cr_mr + $trans_tax);
                            array_push($transport_p,$transport_p_price);
                        }
                    }
                    if ($transport_p) {
                        $transport_price = array_sum($transport_p);
                    }else{
                        $transport_price = 0;
                    }
                }else{
                    $transport_price = 0;
                }
            }else{
                $transport_price = 0;
                $transport_id = $request->transport_id;
            }

            // WEDDING OTHER
            if ($request->other_service_id !== null or $request->other_service_id) {
                $other_service_id = json_encode($request->other_service_id);
                $wed_other_id = $request->other_service_id;
                if ($wed_other_id) {
                    $other_p = [];
                    foreach ($wed_other_id as $w_other_id) {
                        $wedding_other = VendorPackage::where('id',$w_other_id)->first();
                        if ($wedding_other) {
                            array_push($other_p,$wedding_other->publish_rate);
                        }
                    }
                    if ($other_p) {
                        $other_price = array_sum($other_p);
                    }else{
                        $other_price = 0;
                    }
                }else{
                    $other_price = 0;
                }
            }else{
                $other_price = 0;
                $other_service_id = $request->other_service_id;
            }

            $kick_back_per_pax = $request->kick_back_per_pax;
            $extra_bed_price = $request->extra_bed_price;
            $price_pax = $request->price_pax;
            $kick_back = $request->kick_back;
            $normal_price = $wedding->markup + $fixed_service_price + $venue_price + $makeup_price + $room_price + $documentation_price  + $decoration_price + $dinner_venue_price + $entertainment_price + $transport_price + $other_price - $bookingcode_disc - $promotion_total_disc;
            $price_total = $normal_price;
            $final_price = $normal_price;
            $markup = $wedding->markup;

            $orderWedding =new OrderWedding([
                "wedding_id"=>$wedding->id,
                "hotel_id"=>$wedding->hotel_id,
                "duration"=>$duration,
                "wedding_date"=>$wedding_date,
                "brides_id"=>$brides->id,
                "number_of_invitation"=>$number_of_guests,

                "wedding_fixed_service_id"=>$wedding->fixed_services_id,
                "wedding_venue_id"=>$wedding_venue_id,
                "wedding_makeup_id"=>$makeup_id,
                "wedding_room_id"=>$suite_and_villas_id,
                "wedding_documentation_id"=>$documentations_id,
                "wedding_decoration_id"=>$decorations_id,
                "wedding_dinner_venue_id"=>$dinner_venue_id,
                "wedding_entertainment_id"=>$entertainments_id,
                "wedding_transport_id"=>$transport_id,
                "wedding_other_id"=>$other_service_id,

                "fixed_service_price"=>$fixed_service_price,
                "venue_price"=>$venue_price,
                "makeup_price"=>$makeup_price,
                "room_price"=>$room_price,
                "documentation_price"=>$documentation_price,
                "decoration_price"=>$decoration_price,
                "dinner_venue_price"=>$dinner_venue_price,
                "entertainment_price"=>$entertainment_price,
                "transport_price"=>$transport_price,
                "other_price"=>$other_price,
                "markup"=>$markup,
            ]);
            // dd($orderWedding);
            $orderWedding->save();
            $orderWedding_id = $orderWedding->id;
        } else {
            $special_date = $request->special_date;
            $special_day = $request->special_day;
            $number_of_guests_room = $request->number_of_guests_room;
            $number_of_room = $request->number_of_room;
            $guest_detail = $request->guest_detail;
            $extra_bed = $request->extra_bed;
            $number_of_guests = $request->number_of_guests;
            $price_total = $request->price_pax;
            $checkin = date('Y-m-d', strtotime($request->checkin));
            $checkout = date('Y-m-d', strtotime($request->travel_date));
            $duration = $request->duration;
            $price_pax = $request->price_pax;
            $kick_back = $request->kick_back;
            $kick_back_per_pax = $request->kick_back_per_pax;
            $extra_bed_price = $request->extra_bed_price;
            $final_price = $request->final_price - $promotion_total_disc;
            $normal_price = $request->normal_price;
            $include = $request->include;
            $benefits = $request->benefits;
            $additional_info = $request->additional_info;
            $cancellation_policy = $request->cancellation_policy;
            $orderWedding_id = "";
            $order_tax = 0;
        }


        $arrival_date_time = date('Y-m-d H:i',strtotime($request->arrival_time));
        $arrival_flight = $request->arrival_flight?$request->arrival_flight:"Insert flight number";
        $arrival_time = $arrival_date_time?$arrival_date_time:$checkin." 11:00";

        $airport_shuttle_out = $request->airport_shuttle_out;
        $departure_date_time = date('Y-m-d H:i',strtotime($request->departure_time));
        $departure_flight = $request->departure_flight?$request->departure_flight:"Insert flight number";
        $departure_time = $departure_date_time?$departure_date_time:$checkout." 11:00";

       
        $travel_date = date('Y-m-d H.i', strtotime($request->travel_date));
        $price_id = $request->price_id;
        

        if ($service == "Hotel" or $service == "Hotel Promo" or $service == "Hotel Package") {
            $airportShuttlePrice = 0;
            $transport_in = Transports::where('id',$request->airport_shuttle_in)->first();
            if ($transport_in) {
                $transport_in_price = TransportPrice::where('transports_id',$transport_in->id)->where('type',"Airport Shuttle")->where('duration',$hotel->airport_duration)->first();
                if ($transport_in_price) {
                    $airport_shuttle_in_price = $transport_in_price->calculatePrice($usdrates,$tax);
                    $airportShuttlePrice += $airport_shuttle_in_price;
                }
            }
            $transport_out = Transports::where('id',$request->airport_shuttle_out)->first();
            if ($transport_out) {
                $transport_out_price = TransportPrice::where('transports_id',$transport_out->id)->where('type',"Airport Shuttle")->where('duration',$hotel->airport_duration)->first();
                if ($transport_out_price) {
                    $airport_shuttle_out_price = $transport_out_price->calculatePrice($usdrates,$tax);
                    $airportShuttlePrice += $airport_shuttle_out_price;
                }
            }
            if ($transport_in || $transport_out) {
                $airport_shuttle_price = $airportShuttlePrice;
            }else{
                $airport_shuttle_price = NULL;
            }
        }else{
            $airport_shuttle_price = NULL;
        }
        $kick_back_per_pax = $request->kick_back_per_pax;
        $kick_back = $request->kick_back;
        
        $normal_price = $request->promo_price;
        $price_pax = $normal_price / $duration;
        $price_total = ($normal_price * $number_of_room) + $total_extra_bed_price ;
        $final_price = $price_total + $airport_shuttle_price - $bookingcode_disc;
        $order =new Orders([
            "user_id"=>$user_id,
            "name"=>$name,
            "email"=>$email,
            "orderno"=>$request->orderno,
            "service"=>$request->service,
            "service_id"=>$request->service_id,
            "service_type"=>$request->service_type,
            "servicename" =>$request->servicename,
            "subservice"=>$request->subservice,
            "subservice_id"=>$request->subservice_id,
            "package_name"=>$request->package_name,

            "promo_id"=>$promo_id,
            "promo_name"=>$promo_name,
            "book_period_start"=>$book_period_start,
            "book_period_end"=>$book_period_end,
            "period_start"=>$period_start,
            "period_end"=>$period_end,
            "number_of_guests"=>$number_of_guests,
            "number_of_guests_room"=>$number_of_guests_room,
            "number_of_room"=>$number_of_room,
            "guest_detail"=>$guest_detail,
            "request_quotation"=>$request->request_quotation,
            "extra_bed"=>$extra_bed,
            "extra_bed_id"=>$extra_bed_id,
            "extra_bed_price"=>$extra_bed_price,
            "special_day"=>$special_day,
            "special_date"=>$special_date,
            "extra_time"=>$request->extra_time,
            "price_id"=>$request->price_id,
            "checkin"=>$checkin,
            "checkout"=>$checkout,
            "src"=>$request->src,
            "dst"=>$request->dst,
            "sales_agent"=>$sales_agent,

            "pickup_name"=>$pickup_name,
            "pickup_date"=>$checkin,
            "pickup_location"=>$request->pickup_location,
            "dropoff_date"=>$checkout,
            "dropoff_location"=>$request->dropoff_location,
            "bookingcode"=>$bookingcode,
            "bookingcode_disc"=>$bookingcode_disc,
            "travel_date"=>$travel_date,
            "tour_type"=>$request->tour_type,
            "location"=>$request->location,
            "capacity"=>$request->capacity,
            "destinations" =>$request->destinations,

            "include" =>$include,
            "benefits" =>$benefits,
            "additional_info"=>$additional_info,
            "cancellation_policy"=>$cancellation_policy,
            "duration"=>$duration,
            "price_total" =>$price_total, 
            "promotion" =>$promotion, 
            "promotion_disc" =>$promotion_disc, 
            "final_price" =>$final_price, 
            "usd_rate" =>$usdrates->rate, 
            "cny_rate" =>$cnyrates->rate, 
            "twd_rate" =>$twdrates->rate, 
            "normal_price" =>$normal_price,
            "price_pax" =>$price_pax,
            "kick_back" =>$kick_back, 
            "kick_back_per_pax" =>$kick_back_per_pax, 
            "status"=>$status,
            "itinerary"=>$request->itinerary,
            "wedding_order_id"=>$orderWedding_id,
            "wedding_date"=>$wedding_date,
            "bride_name"=>$request->bride_name,
            "groom_name"=>$request->groom_name,
            "airport_shuttle_price"=>$airport_shuttle_price,
            
            "arrival_flight"=>$arrival_flight,
            "arrival_time"=>$arrival_time,
            "airport_shuttle_in"=>$request->airport_shuttle_in,
            "departure_flight"=>$departure_flight,
            "departure_time"=>$departure_time,
            "airport_shuttle_out"=>$request->airport_shuttle_out,
            "note"=>$request->note,
        ]);
        // dd($order);
        $order->save();
        if (isset($bcode)) {
            $cbcode = $bcode->used + 1;
            $bcode->update([
                "used"=>$cbcode,
            ]);
        }
        $note = "Created Order with order no: ".$request->orderno;
        if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package") {
            $transport_in = Transports::where('id',$request->airport_shuttle_in)->first();
            if ($transport_in) {
                $transport_in_price = TransportPrice::select('id','contract_rate','markup')->where('transports_id',$transport_in->id)->where('type',"Airport Shuttle")->where('duration',$hotel->airport_duration)->first();
                $transport_date_in = $request->arrival_time?$request->arrival_time:$checkin;
                if ($transport_in_price) {
                    $airport_shuttle_in_price = $transport_in_price->calculatePrice($usdrates, $tax);
                    $price_id = $transport_in_price->id;
                }else{
                    $airport_shuttle_in_price = 0;
                    $price_id = NULL;
                }
                $airport_shuttle_in =new AirportShuttle([
                    "date"=>$transport_date_in,
                    "transport_id"=>$request->airport_shuttle_in,
                    "price_id"=>$price_id,
                    "src"=>"Airport",
                    "dst"=>$hotel->name,
                    "duration"=>$hotel->airport_duration,
                    "distance"=>$hotel->airport_distance,
                    "price"=>$airport_shuttle_in_price,
                    "order_id"=>$order->id,
                    "nav"=>"In",
                ]);
                $airport_shuttle_in->save();
                
            }
            $transport_out = Transports::where('id',$request->airport_shuttle_out)->first();
            if ($transport_out) {
                $transport_out_price = TransportPrice::select('id','contract_rate','markup')->where('transports_id',$transport_out->id)->where('type',"Airport Shuttle")->where('duration',$hotel->airport_duration)->first();
                $transport_date_out = $request->departure_time?$request->departure_time:$checkout;
                if ($transport_out_price) {
                    $airport_shuttle_out_price = $transport_out_price->calculatePrice($usdrates, $tax);
                    $price_id = $transport_out_price->id;
                }else{
                    $airport_shuttle_out_price = 0;
                    $price_id = NULL;
                }
                if ($request->airport_shuttle_out) {
                    $airport_shuttle_out =new AirportShuttle([
                        "date"=>$transport_date_out,
                        "transport_id"=>$request->airport_shuttle_out,
                        "price_id"=>$price_id,
                        "src"=>$hotel->name,
                        "dst"=>"Airport",
                        "duration"=>$hotel->airport_duration,
                        "distance"=>$hotel->airport_distance,
                        "price"=>$airport_shuttle_out_price,
                        "order_id"=>$order->id,
                        "nav"=>"Out",
                    ]);
                    $airport_shuttle_out->save();
                }
            }
          
        }

        $user_log =new UserLog([
            "action"=>"Create Order",
            "service"=>$request->service,
            "subservice"=>$request->subservice,
            "subservice_id"=>$order->id,
            "page"=>$request->page,
            "user_id"=>$user_id,
            "user_ip"=>$request->getClientIp(),
            "note" =>$note, 
        ]);
        $user_log->save();
        $order_log =new OrderLog([
            "order_id"=>$order->id,
            "action"=>"Create Order",
            "url"=>$request->getClientIp(),
            "method"=>"Create",
            "agent"=>$order->name,
            "admin"=>Auth::user()->id,
        ]);
        $order_log->save();
        session()->forget('booking_dates');
        $subject = $request->orderno;
        if (Auth::user()->position == "developer" || Auth::user()->position == "reservation" || Auth::user()->position == "author") {
            $rquotation = $request->request_quotation;
            Mail::to(config('app.reservation_mail'))
            ->send(new ReservationMail($order->id,$rquotation));
            return redirect('/orders-admin-'.$order->id)->with('success','The order has been successfully created!');
        }else{
            return redirect('/edit-order-'.$order->id)->with('success','Your order has been added to the order basket. Please ensure that all details are entered correctly before you confirm the order for further processing.');
        }
    }

    

    // FUNCTION ADD OPTIONAL SERVICE ORDER ------------------------------------------------------------------------------------------------------------------------------->
    public function fadd_optional_service_order(Request $request){
        $order = Orders::where('id',$request->order_id)->first();
        $or_nog = json_encode($request->optional_rate_id);
        $optional_rate_id = json_encode($request->optional_rate_id);
        $number_of_guest = json_encode($request->number_of_guest);
        $service_date = json_encode($request->service_date);
        $pro_disc = json_decode($order->promotion_disc);
        $extra_bed_price = json_decode($order->extra_bed_price);
        if (isset($pro_disc)) {
            $promotion_disc = array_sum($pro_disc);
        }else {
           $promotion_disc = 0;
        }
        
        $usdrates = UsdRates::where('name','USD')->first();
        $tax = Tax::where('id',1)->first();
        if ($order->status == "Draft") {
            $price_pax_nd = [];
            $price_total_nd = [];
            if ($request->optional_rate_id != "") {
                foreach ($request->optional_rate_id as $x=>$oprid) {
                    $optional_rate = OptionalRate::where('id', $oprid)->first();
                    $cr = ceil($optional_rate->contract_rate / $usdrates->rate)+$optional_rate->markup;
                    $cr_pajak = ceil(($cr * $tax->tax)/100);
                    $cr_pax = $cr + $cr_pajak;
                    array_push($price_pax_nd, $cr_pax);
                    array_push($price_total_nd, $request->number_of_guest[$x] * $cr_pax);
                }
            }
            $order_extra_bed = array_sum($extra_bed_price);
            $or_total_price = array_sum($price_total_nd);
            $price_pax = json_encode($price_pax_nd);
            $or_price_total = json_encode($price_total_nd);

            $optional_rate_orders =new OptionalRateOrder([
                "order_id"=>$request->order_id,
                "optional_rate_id"=>$optional_rate_id,
                "number_of_guest"=>$number_of_guest,
                "service_date"=>$service_date,
                "price_pax"=>$price_pax,
                "price_total"=>$or_price_total,
            ]);
            $order_price_total = (($order->normal_price * $order->number_of_room) - $order->kick_back)+$order_extra_bed + $or_total_price;
            $order_final_price = $order_price_total - $order->discounts - $order->bookingcode_disc - $promotion_disc;
            $order->update([
                "optional_price"=>$or_total_price,
                "price_total"=> $order_price_total,
                "final_price"=> $order_final_price,
            ]);
            //dd($optional_rate_orders);
            $optional_rate_orders->save();
            $order_log =new OrderLog([
                "order_id"=>$order->id,
                "action"=>"Update Optional Service",
                "url"=>$request->getClientIp(),
                "method"=>"Create",
                "agent"=>$order->name,
                "admin"=>Auth::user()->id,
            ]);
            $order_log->save();
            return redirect("/edit-order-$request->order_id")->with('success','Optional service added successfully!');
        }else{
            return redirect("/orders")->with('warning','Your order cannot be changed!');
        }
    }

    

    // FUNCTION REMOVE ORDER ---------------------------------------------------------------------------------------------------------------------------------------------->
    public function func_remove_order(Request $request,$id){
        $order=Orders::findOrFail($id);
        $orderno = $order->orderno;
        $service = "Order";
        $action = "Remove Order";
        $msg = "User = Remove Order <br>Admin = ".$request->admin_msg;

        $order->update([
            "status"=>$request->status,
        ]);

        $log= new LogData ([
            'service' =>$orderno,
            'service_name'=>$service,
            'action'=>$action,
            'user_id'=>$request->author,
        ]);
        $log->save();
        return redirect("/orders")->with('success','Your order has been successfully removed!');
    }

    // FUNCTION DESTROY ORDER --------------------------------------------------------------------------------------------------------------------------------------------->
    public function destroy_order(Request $request,$id) {
        $order = Orders::findOrFail($id);
        $order->delete();
        return redirect("/orders")->with('success','Order has been deleted!');
    }

    // Function Remove optional service =============================================================================================================>
    public function destroy_opser_order(Request $request,$id) {
        $order = $request->order_id;
        $optional_rate_order = OptionalRateOrder::findOrFail($id);
        $optional_rate_order->delete();
        return redirect("/order-$order")->with('success','Optional service has been removed!');
    }


    // Function Update Order Room =============================================================================================================>
    public function func_update_order_room(Request $request,$id){
        $order=Orders::findOrFail($id);
        $croom = count($request->number_of_guests_room);
        $usdrates = UsdRates::where('name','USD')->first();
        $tax = Tax::where('id',1)->first();
        $duration = $order->duration;
        $optional_price = $order->optional_price;
        $price_pax = $order->price_pax;
        $kick_back = ($order->kick_back_per_pax * $duration)*$croom;
        
        if ($request->number_of_guests_room > 0) {
            $number_of_guests = array_sum($request->number_of_guests_room);
            $extra_bed_proses = [];
            foreach ($request->number_of_guests_room as $jk) {
                if ($jk < 3 ) {
                    array_push($extra_bed_proses,'No');
                }else{
                    array_push($extra_bed_proses,'Yes');
                }
            }

            $extra_bed_id_price = [];          
            for ($i=0; $i < $croom; $i++) { 
                if ($extra_bed_proses[$i] == "Yes") {
                    if ($request->extra_bed_id[$i] == 0) {
                        array_push($extra_bed_id_price,null);
                    }else{
                        $extrabeds = ExtraBed::where('id',$request->extra_bed_id[$i])->first();
                        $contract_rate_eb = ceil($extrabeds->contract_rate/$usdrates->rate)+$extrabeds->markup;
                        $tax_usd_extra_bed = ceil($contract_rate_eb * ($tax->tax/100));
                        $price_extra_bed = ($contract_rate_eb + $tax_usd_extra_bed)*$duration; 
                        array_push($extra_bed_id_price,$price_extra_bed);
                    } 
                }else{
                    array_push($extra_bed_id_price,0);
                }
            }
            $extra_bed_id = json_encode($request->extra_bed_id);
            $extra_bed_price = json_encode($extra_bed_id_price);
            $extra_bed = json_encode($extra_bed_proses);
            $guest_detail = json_encode($request->guest_detail);
            $special_day = json_encode($request->special_day);
            $special_date = json_encode($request->special_date);
            $pro_disc = json_decode($order->promotion_disc);
            $number_of_guests_room = json_encode($request->number_of_guests_room);
            $total_extra_bed = array_sum($extra_bed_id_price);
            if (isset($pro_disc)) {
            
                $promotion_disc = array_sum($pro_disc);
            }else{
                $promotion_disc = 0;
            }
            
            $normal_price = $order->normal_price;
            $price_total_ue = ($price_pax * $croom)+$total_extra_bed;
            $price_total = (($normal_price * $croom) - $kick_back) + $total_extra_bed;
            $final_price = (($price_total + $optional_price) - $order->discounts) - $order->bookingcode_disc - $promotion_disc;
        
        }else{
            $number_of_guests = 0;
            $number_of_guests_room = 0;
            $croom = 0;
            $extra_bed_proses = 0;
            $extra_bed_id = 0;
            $extra_bed_price = 0;
            $extra_bed = 0;
            $price_total = 0;
            $kick_back = 0;
            $guest_detail = 0;
            $special_day = 0;
            $special_date = 0;
            $normal_price = 0;
            $airport_shuttle_price = 0;
            $final_price = 0;
        }

        $order->update([
            "number_of_guests"=>$number_of_guests,
            "number_of_guests_room"=>$number_of_guests_room,
            "number_of_room"=>$croom,
            "guest_detail"=>$guest_detail,
            "request_quotation"=>$request->request_quotation,
            "extra_bed"=>$extra_bed,
            "extra_bed_id"=>$extra_bed_id,
            "extra_bed_price"=>$extra_bed_price,
            "special_day"=>$special_day,
            "special_date"=>$special_date,
            "price_total"=>$price_total,
            "final_price"=>$final_price,
            "kick_back"=>$kick_back,
        
        ]);
        // dd([$order]);
        return redirect("/edit-order-$id")->with('success','Your order has been updated');
    }

    // Function Updated Activated =============================================================================================================>
    public function func_update_order(Request $request,$id){
        try {
            // Batas waktu eksekusi khusus untuk fungsi ini
            ini_set('max_execution_time', 60);
            $order=Orders::findOrFail($id);
            $orderno = $order->orderno;
            $checkin = date('Y-m-d', strtotime($request->checkin));
            $checkout = date('Y-m-d', strtotime($request->checkout));
            $usdrate = UsdRates::where('id',1)->first();
            $tax = Tax::where('id',1)->first();
            $arrival_time = date('Y-m-d H:i',strtotime($request->arrival_time));
            $departure_time = date('Y-m-d H:i',strtotime($request->departure_time));
            $travel_date = date('Y-m-d H.i',strtotime($request->travel_date));
            $wedding_date = date('Y-m-d',strtotime($request->wedding_date))." ".date('H.i',strtotime($request->wedding_time));
            if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package") {
                $hotel = Hotels::where('id',$order->service_id)->first();
                $transport_in = Transports::where('id',$request->airport_shuttle_in)->first();
                if ($transport_in) {
                    $transport_in_price = TransportPrice::where('transports_id',$transport_in->id)->where('type',"Airport Shuttle")->where('duration',$hotel->airport_duration)->first();
                    if ($transport_in_price) {
                        $c_price_usd = ceil($transport_in_price->contract_rate/$usdrate->rate);
                        $c_price_markup = $c_price_usd + $transport_in_price->markup;
                        $c_price_tax = ceil($c_price_markup*($tax->tax/100));
                        $airport_shuttle_in_price = $c_price_markup + $c_price_tax;
                    }else{
                        $airport_shuttle_in_price = 0;
                    }
                }else {
                    $transport_in_price = TransportPrice::where('transports_id',$request->airport_shuttle_in)->where('type',"Airport Shuttle")->where('duration',$hotel->airport_duration)->first();
                    if ($transport_in_price) {
                        $c_price_usd = ceil($transport_in_price->contract_rate/$usdrate->rate);
                        $c_price_markup = $c_price_usd + $transport_in_price->markup;
                        $c_price_tax = ceil($c_price_markup*($tax->tax/100));
                        $airport_shuttle_in_price = $c_price_markup + $c_price_tax;
                    }else{
                        $airport_shuttle_in_price = 0;
                    }
                }
                $transport_out = Transports::where('id',$request->airport_shuttle_out)->first();
                if ($transport_out) {
                    $transport_out_price = TransportPrice::where('transports_id',$transport_out->id)->where('type',"Airport Shuttle")->where('duration',$hotel->airport_duration)->first();
                    if ($transport_out_price) {
                        $o_price_usd = ceil($transport_out_price->contract_rate/$usdrate->rate);
                        $o_price_markup = $o_price_usd + $transport_out_price->markup;
                        $o_price_tax = ceil($o_price_markup*($tax->tax/100));
                        $airport_shuttle_out_price = $o_price_markup + $o_price_tax;
                    }else{
                        $airport_shuttle_out_price = 0;
                    }
                }else{
                    $transport_out_price = TransportPrice::where('transports_id',$request->airport_shuttle_out)->where('type',"Airport Shuttle")->where('duration',$hotel->airport_duration)->first();
                    if ($transport_out_price) {
                        $o_price_usd = ceil($transport_out_price->contract_rate/$usdrate->rate);
                        $o_price_markup = $o_price_usd + $transport_out_price->markup;
                        $o_price_tax = ceil($o_price_markup*($tax->tax/100));
                        $airport_shuttle_out_price = $o_price_markup + $o_price_tax;
                    }else{
                        $airport_shuttle_out_price = 0;
                    }
                }
                if ($airport_shuttle_in_price == 0 or $airport_shuttle_out_price == 0) {
                    $airport_shuttle_price = 0;
                }else{
                    $airport_shuttle_price = $airport_shuttle_in_price + $airport_shuttle_out_price;
                }
            }else{
                $airport_shuttle_price = 0;
            }
            if ($order->service == "Activity") {
                $normal_price =$order->price_pax * $request->number_of_guests;
                $price_total = $normal_price;
                $final_price = $price_total - $order->bookingcode_disc - $request->promotion_disc - $order->discounts;
                $number_of_guests = $request->number_of_guests;
                $price_pax = $order->price_pax;
            }elseif($order->service == "Tour Package"){
                $pr_disc = json_decode($order->promotion_disc);
                if (isset($pr_disc)) {
                    $promotion_disc = array_sum($pr_disc);
                }else{
                    $promotion_disc = 0;
                }
                $ad_ser = json_decode($order->additional_service_price);
                if (isset($ad_ser)) {
                    $additional_service_price = array_sum($ad_ser);
                }else{
                    $additional_service_price = 0;
                }
                $normal_price = $request->normal_price;
                $price_total = $request->price_total;
                // $final_price = $request->final_price;
                $number_of_guests = $request->number_of_guests;
                $price_pax = $request->price_pax;
                $final_price = ($price_total - $order->discounts - $order->bookingcode_disc - $promotion_disc) + $additional_service_price + $order->airport_shuttle_price;
            }elseif($order->service == "Wedding Package"){
                $order_wedding = OrderWedding::where('id',$order->wedding_order_id)->firstOrFail();
                $bride = Brides::where('id',$order_wedding->brides_id)->firstOrFail();
                $number_of_guests = $request->number_of_guests;
                $final_price = $request->final_price;
                $price_total = $request->price_total;
                $normal_price = $request->normal_price;
                $price_pax = $request->price_pax;

                $order_wedding->update([
                    "wedding_date"=>$wedding_date,
                    "number_of_invitation"=>$number_of_guests,
                ]);
                $bride->update([
                    "bride"=>$request->bride_name,
                    "bride_chinese"=>$request->bride_chinese,
                    "bride_contact"=>$request->bride_contact,
                    "groom"=>$request->groom_name,
                    "groom_chinese"=>$request->groom_chinese,
                    "groom_contact"=>$request->groom_contact,
                ]);

            }else{
                $normal_price = $order->normal_price;
                $price_total = $order->price_total;
                $final_price = $order->price_total + $airport_shuttle_price;
                $number_of_guests = $order->number_of_guests;
                $price_pax = $order->price_pax;
            }
            $order->update([
                "status"=>$request->status,
                "guest_detail"=>$request->guest_detail,
                "arrival_flight"=>$request->arrival_flight,
                "arrival_time"=>$arrival_time,
                "departure_flight"=>$request->departure_flight,
                "departure_time"=>$departure_time,
                "airport_shuttle_in"=>$request->airport_shuttle_in,
                "airport_shuttle_out"=>$request->airport_shuttle_out,
                "note"=>$request->note,
                "kick_back"=>$request->kick_back,
                "request_quotation"=>$request->request_quotation,
                "travel_date"=>$travel_date,
                "number_of_guests"=>$number_of_guests,
                "final_price"=>$final_price,
                "bookingcode"=>$order->bookingcode,
                "bookingcode_disc"=>$order->bookingcode_disc,
                "airport_shuttle_price"=>$airport_shuttle_price,
                "price_total"=>$price_total,
                "normal_price"=>$normal_price,
                "price_pax"=>$price_pax,
                "pickup_location"=>$request->pickup_location,
                "dropoff_location"=>$request->dropoff_location,
                "groom_name"=>$request->groom_name,
                "bride_name"=>$request->bride_name,
                "wedding_date"=>$wedding_date,
                
            ]);
            // dd($order,$order_wedding,$bride);
            
            if (isset($request->airport_shuttle_in) or isset($request->airport_shuttle_out)) {
                $asins = AirportShuttle::where('order_id',$order->id)->get();
                if(isset($asins)){
                    foreach ($asins as $asin) {
                        $in_asin = $asins->where('nav',"In")->first();
                        $out_asin = $asins->where('nav',"Out")->first();
                        if (isset($in_asin)) {
                            if ($asin->nav == "In") {
                                $asin->update([
                                    "date"=>$request->arrival_time,
                                    "transport"=>$transport_in->name,
                                    "src"=>"Airport",
                                    "dst"=>$hotel->name,
                                    "duration"=>$hotel->airport_duration,
                                    "distance"=>$hotel->airport_distance,
                                    "price"=>$airport_shuttle_in_price,
                                ]);
                            }
                        }else{
                            $airport_shuttle_in =new AirportShuttle([
                                "date"=>$request->arrival_time,
                                "transport"=>$transport_in->name,
                                "src"=>"Airport",
                                "dst"=>$hotel->name,
                                "duration"=>$hotel->airport_duration,
                                "distance"=>$hotel->airport_distance,
                                "price"=>$airport_shuttle_in_price,
                                "order_id"=>$order->id,
                                "nav"=>"In",
                            ]);
                            $airport_shuttle_in->save();
                        }
                        if (isset($out_asin)) {
                            $out_asin->update([
                                "date"=>$request->departure_time,
                                "transport"=>$transport_out->name,
                                "src"=>$hotel->name,
                                "dst"=>"Airport",
                                "duration"=>$hotel->airport_duration,
                                "distance"=>$hotel->airport_distance,
                                "price"=>$airport_shuttle_out_price,
                            ]);
                        }else{
                            $airport_shuttle_out =new AirportShuttle([
                                "date"=>$request->departure_time,
                                "transport"=>$transport_out->name,
                                "src"=>$hotel->name,
                                "dst"=>"Airport",
                                "duration"=>$hotel->airport_duration,
                                "distance"=>$hotel->airport_distance,
                                "price"=>$airport_shuttle_out_price,
                                "order_id"=>$order->id,
                                "nav"=>"Out",
                            ]);
                            $airport_shuttle_out->save();
                        }
                    }
                }else {
                    if ($request->airport_shuttle_in) {
                        $airport_shuttle_in =new AirportShuttle([
                            "date"=>$request->arrival_time,
                            "transport"=>$transport_in->name,
                            "src"=>"Airport",
                            "dst"=>$hotel->name,
                            "duration"=>$hotel->airport_duration,
                            "distance"=>$hotel->airport_distance,
                            "price"=>$airport_shuttle_in_price,
                            "order_id"=>$order->id,
                            "nav"=>"In",
                        ]);
                        $airport_shuttle_in->save();
                    }
                    if ($request->airport_shuttle_out) {
                        $airport_shuttle_out =new AirportShuttle([
                            "date"=>$request->departure_time,
                            "transport"=>$transport_out->name,
                            "src"=>$hotel->name,
                            "dst"=>"Airport",
                            "duration"=>$hotel->airport_duration,
                            "distance"=>$hotel->airport_distance,
                            "price"=>$airport_shuttle_out_price,
                            "order_id"=>$order->id,
                            "nav"=>"Out",
                        ]);
                        $airport_shuttle_out->save();
                    }
                }
            }
            // dd($order, $asins);
            //Mail
            $rquotation = $request->request_quotation;
            $agent = User::where('id',$order->user_id)->first();
            Mail::to(config('app.reservation_mail'))
            ->send(new ReservationMail($id,$rquotation));
            $note = "Submited order no: ".$order->orderno;
            // dd($order);
            $user_log =new UserLog([
                "action"=>$request->action,
                "service"=>$order->service,
                "subservice"=>$order->subservice,
                "subservice_id"=>$id,
                "page"=>$request->page,
                "user_id"=>$request->author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            $order_log =new OrderLog([
                "order_id"=>$order->id,
                "action"=>'Submit Order',
                "url"=>$request->getClientIp(),
                "method"=>"Archive",
                "agent"=>$order->name,
                "admin"=>Auth::user()->id,
            ]);
            $order_log->save();
            return redirect("/detail-order-$order->id")->with('success','Your order has been submited, and we will validate your order');
        } catch (\Exception $e) {
            Log::error('Error updating order: ' . $e->getMessage());
            if ($e instanceof \Symfony\Component\HttpFoundation\File\Exception\FileException) {
                return redirect()->back()->with('error', 'Please try again, your order has not been submitted due to a network issue.');
            } else {
                return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
            }
        }
    }


    public function func_approve_order(Request $request,$id){
        $order=Orders::findOrFail($id);
        $order->update([
            "status"=>"Approved",
        ]);
        $order_log =new OrderLog([
            "order_id"=>$order->id,
            "action"=>"Approve Order",
            "url"=>$request->getClientIp(),
            "method"=>"Approve",
            "agent"=>$order->name,
            "admin"=>Auth::user()->id,
        ]);
        $order_log->save();
        return redirect("/detail-order-$id")->with('success','Your order has been approved');
        // return redirect()->back()->with('success','Your order has been approved');
    }

    // Function Reupload accepted =============================================================================================================>
    public function func_reupload_order(Request $request,$id){
        $order=Orders::findOrFail($id);
        $orderno = $order->orderno;
        $service = "Order";
        $action = "Reupload Order";
        $checkin = date('Y-m-d', strtotime($request->checkin));
        $checkout = date('Y-m-d', strtotime($request->checkout));
        $msg = "";

        $order->update([
            "status"=>$request->status,
            "msg"=>$msg,
        ]);

        // USER LOG
        $note = "Resubmit order no: ".$order->orderno;
        $user_log =new UserLog([
            "action"=>$action,
            "service"=>$service,
            "subservice"=>$request->subservice,
            "subservice_id"=>$request->subservice_id,
            "page"=>$request->page,
            "user_id"=>$request->author,
            "user_ip"=>$request->getClientIp(),
            "note" =>$note, 
        ]);
        $user_log->save();
        return redirect("/orders")->with('success','Your order has been resubmited, and we will validate your order');
    }

    // Function add optional rate to Order ======================================================================================= ==>
    public function func_add_optional_rate(Request $request){

        $usdrates = UsdRates::where('name','USD')->first();
        $opti_rate = OptionalRate::where('id','=',$request->optional_rate_id)->first();
        $type = $opti_rate->type;
        $name = $opti_rate->name;
        $price_unit = (ceil($opti_rate->contract_rate / $usdrates->rate))+$opti_rate->markup;
        $total_price = $price_unit * $request->qty;
        $description = $opti_rate->description;
        $status = "Active";
        $service_date = date("Y-m-d",strtotime($request->service_date));
        $optionalrateorder =new OptionalRateOrder([
            "order_id"=>$request->order_id,
            "type"=>$type,
            "name"=>$name,
            "qty"=>$request->qty,
            "price_unit"=>$total_price,
            "description" =>$description,
            "note"=>$request->note,
            "status"=>$status,
            "author"=>$request->author,
            "service_date"=>$service_date,
            "optional_rate_id"=>$request->optional_rate_id,
        ]);
        // @dd($order);
        $optionalrateorder->save();
        return redirect("/order-$request->order_id")->with('success','Optional service added successfully');
    
    }
    

}
