<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tax;
use App\Models\Tours;
use App\Models\Orders;
use App\Models\TourType;
use App\Models\UsdRates;
use App\Models\Attention;
use App\Models\Promotion;
use App\Models\TourPrices;
use App\Models\BookingCode;
use App\Models\ToursImages;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BusinessProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoretoursRequest;
use App\Http\Requests\UpdatetoursRequest;

class ToursController extends Controller

{   
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    public function index()
    {   
        $tours=Tours::where('status','active')->paginate(12)->withQueryString();
        $promotions = Promotion::where('status',"Active")->get();
        return view('main.tours', compact('tours'),[
            "promotions" => $promotions,
        ]);
    }
    // Search Tours =========================================================================================>
    public function search_tour(Request $request){
        $now = Carbon::now();
        $user_id = Auth::user()->id;
        $taxes = Tax::where('id',1)->first();
        $usdrates = UsdRates::where('id',1)->first();
        $tour_location = $request->tour_location;
        $tour_type = $request->tour_type;
        $type=TourType::all();
        $orders = Orders::where('user_id', $user_id)->get();
        $bcode = $request->bookingcode;
        $tours = Tours::where('status', '=','Active')
            ->where('location','LIKE','%'.$tour_location.'%')
            ->where('type','LIKE', '%'.$tour_type.'%')
            ->paginate(12)->withQueryString();
        $promotions = Promotion::where('status', 'Active')->get();
        $promotion_price = $promotions->sum('discounts');
        $bcode = $request->bookingcode;
        if (isset($bcode)) {
            try {
                $bk_code = BookingCode::where('code', $bcode)
                    ->where('status', 'Active')
                    ->firstOrFail();
                if ($bk_code->used < $bk_code->amount) {
                    $usedcode = $orders->where('bookingcode', $bk_code->code)->first();
                    if (isset($usedcode)) {
                        $bookingcode_status = "Used";
                        $bookingcode = "";
                    } elseif ($bk_code->expired_date >= $now) {
                        $bookingcode_status = "Valid";
                        $bookingcode = $bk_code;
                    } else {
                        $bookingcode_status = "Expired";
                        $bookingcode = "";
                    }
                } else {
                    $bookingcode_status = "Expired";
                    $bookingcode = "";
                }
            } catch (ModelNotFoundException $e) {
                $bookingcode_status = 'Invalid';
                $bookingcode = "";
            }
        }else{
            $bookingcode_status = "";
            $bookingcode = "";
        }
        return view('main.toursearch', compact('tours'),[
            'tour_location'=>$tour_location,
            'tour_type'=>$tour_type,
            'type'=>$type,
            'promotion_price'=>$promotion_price,
            'bookingcode'=>$bookingcode,
            'bookingcode_status'=>$bookingcode_status,
            'usdrates'=>$usdrates,
            'taxes'=>$taxes,
            'promotions'=>$promotions,
        ]);
    }
    public function view_tour_detail($code)
    {
        $promotions = Promotion::where('status',"Active")->get();
            if (isset($promotions)) {
                $count_promotions = count($promotions);
            }else{
                $count_promotions = 0;
            }
        $business = BusinessProfile::where('id','=',1)->first();
        $taxes = Tax::where('id',1)->first();
        $now = Carbon::now();
        $tour = Tours::where('code',$code)->first();
        $orders = Orders::all();
        $ordernotours = count($orders) + 1;
        $attentions = Attention::where('page','tour-detail')->get();
        $usdrates = UsdRates::where('name','USD')->first();
        $agents = Auth::user()->where('status','Active')->get();
        $neartours = Tours::where('status',"Active")->where('location','=',$tour->location)
        ->where('code','!=',$code)
        ->get();

        $promotions = Promotion::where('status',"Active")->get();
        if (isset($promotions)){
            $pr = count($promotions);
            $promotion_price = 0;
            for ($i=0; $i < $pr; $i++) { 
                $promotion_price = $promotion_price + $promotions[$i]->discounts;
            }
            $promotion_price = $promotion_price;
        }else{
            $promotion_price = 0;
        }
        
        $tour_prices = TourPrices::where('tours_id',$tour->id)
        ->where('status',"Active")
        ->orderBy('max_qty','ASC')->get();
        $bookingcode_status = null;
        $bookingcode = null;
        $harga_per_pax = [];
        $start_qty = [];
        $end_qty = [];
        foreach ($tour_prices as $key => $tour_price) {
            $contract_rate = $tour_price->contract_rate;
            $price_no_tax = (ceil($contract_rate / $usdrates->rate))+$tour_price->markup;
            $tax_price = ceil(($taxes->tax/100) * $price_no_tax);
            $p_pax = $price_no_tax + $tax_price;
        }
        $ppp = json_encode($harga_per_pax);
        $min_qty = $start_qty;
        $max_qty = json_encode($end_qty);
        $qty = TourPrices::max('max_qty');


        return view('main.tourdetail',[
            'taxes'=>$taxes,
            'usdrates'=>$usdrates,
            'agents'=>$agents,
            'attentions'=>$attentions,
            'ordernotours' => $ordernotours,
            'tour'=>$tour,
            'neartours'=>$neartours,
            'now'=>$now,
            'business'=>$business,
            'bookingcode'=>$bookingcode,
            'bookingcode_status'=>$bookingcode_status,
            'promotions'=>$promotions,
            'promotion_price'=>$promotion_price,
            'count_promotions'=>$count_promotions,
            'tour_prices'=>$tour_prices,
            'qty'=>$qty,
        ]);
    }
    public function tour_check_code(Request $request){
        $now = Carbon::now();
        $tour = Tours::where('id',$request->tour_id)->first();
        $bcode = $request->bookingcode;
        $user_id = Auth::user()->id;
        $orders = Orders::where('user_id', $user_id)->get();
        $bk_code = BookingCode::where('code', $bcode)->where('status', 'Active')->first();
        if (isset($bk_code)) {
            if ($bk_code->used < $bk_code->amount) {
                if (isset($orders)) {
                    $usedcode = $orders->where('bookingcode', $bk_code->code)->first();
                    if (isset($usedcode)) {
                        $bookingcode_status = "Used";
                        $bookingcode = null;
                    }else{
                        if ($bk_code->expired_date >= $now) {
                            $bookingcode_status = "Valid";
                            $bookingcode = $bk_code;
                        }else{
                            $bookingcode_status = "Expired";
                            $bookingcode = null ;
                        }
                    }
                }else{
                    if ($bk_code->expired_date >= $now) {
                        $bookingcode_status = "Valid";
                        $bookingcode = $bk_code;
                    }else{
                        $bookingcode_status = "Expired";
                        $bookingcode = null ;
                    }
                }
            }else{
                $bookingcode_status = "Expired";
                $bookingcode = null ;
            }
        }else{
            $bookingcode_status = 'Invalid';
            $bookingcode = null;
        }
        if (isset($bookingcode)) {
            return redirect("/tour-$tour->code-$bookingcode->code");
        }else{
            return redirect("/tour-$tour->code")->with('danger', $bookingcode_status.' Code');
        }
    }

    public function view_tour_detail_bookingcode($code,$bcode)
    {
        $business = BusinessProfile::where('id','=',1)->first();
        $taxes = Tax::where('id',1)->first();
        $now = Carbon::now();
        $tour = Tours::where('code',$code)->first();
        $orders = Orders::all();
        $ordernotours = count($orders) + 1;
        $attentions = Attention::where('page','tour-detail')->get();
        $usdrates = UsdRates::where('name','USD')->first();
        $agents = Auth::user()->where('status','Active')->get();
        $neartours = Tours::where('status',"Active")->where('location','=',$tour->location)
        ->where('code','!=',$code)
        ->get();
        $tour_prices = TourPrices::where('tours_id',$tour->id)
        ->where('status',"Active")->get();
        $promotions = Promotion::where('status',"Active")->get();
        if (isset($promotions)) {
            $count_promotions = count($promotions);
        }else{
            $count_promotions = 0;
        }
        if (isset($bcode)) {
            $user_id = Auth::user()->id;
            $orders = Orders::where('user_id', $user_id)->get();
            $bk_code = BookingCode::where('code', $bcode)->where('status', 'Active')->first();
            if (isset($bk_code)) {
                if ($bk_code->used < $bk_code->amount) {
                    if (isset($orders)) {
                        $usedcode = $orders->where('bookingcode', $bk_code->code)->first();
                        if (isset($usedcode)) {
                            $bookingcode_status = "Used";
                            $bookingcode = null;
                        }else{
                            if ($bk_code->expired_date >= $now) {
                                $bookingcode_status = "Valid";
                                $bookingcode = $bk_code;
                            }else{
                                $bookingcode_status = "Expired";
                                $bookingcode = null ;
                            }
                        }
                    }else{
                        if ($bk_code->expired_date >= $now) {
                            $bookingcode_status = "Valid";
                            $bookingcode = $bk_code;
                        }else{
                            $bookingcode_status = "Expired";
                            $bookingcode = null ;
                        }
                    }
                }else{
                    $bookingcode_status = "Expired";
                    $bookingcode = null ;
                }
            }else{
                $bookingcode_status = 'Invalid';
                $bookingcode = null;
            }
        }else{
            $bookingcode_status = null;
            $bookingcode = null;
        }

        if (isset($promotions)){
            $pr = count($promotions);
            $promotion_price = 0;
            for ($i=0; $i < $pr; $i++) { 
                $promotion_price = $promotion_price + $promotions[$i]->discounts;
            }
        }else{
            $promotion_price = 0;
        }

        $price_non_tax = (ceil($tour->contract_rate / $usdrates->rate))+$tour->markup;
        $tax = ceil(($taxes->tax/100) * $price_non_tax);
        $normal_price = ($price_non_tax + $tax);
        $qty = TourPrices::max('max_qty');
        if (isset($bookingcode->code) or isset($promotions)) {
            if (isset($bookingcode->code)) {
                $price_per_pax = $normal_price;
                
                if (isset($promotions)) {
                    $final_price = $normal_price - $bookingcode->discounts - $promotion_price;
                }else{
                    $final_price = $normal_price - $bookingcode->discounts;
                }
            }else{
                $price_per_pax = $normal_price ;
                $final_price = $normal_price  - $promotion_price;
            }
        }else {
            $price_per_pax = $normal_price;
            $final_price = $normal_price;
        }
        if (isset($bookingcode)) {
            return view('main.tourdetail',[
                'taxes'=>$taxes,
                'usdrates'=>$usdrates,
                'agents'=>$agents,
                'attentions'=>$attentions,
                'ordernotours' => $ordernotours,
                'tour'=>$tour,
                'neartours'=>$neartours,
                'now'=>$now,
                'business'=>$business,
                'bookingcode'=>$bookingcode,
                'bookingcode_status'=>$bookingcode_status,
                'promotions'=>$promotions,
                'promotion_price'=>$promotion_price,
                'count_promotions'=>$count_promotions,
                'tour_prices'=>$tour_prices,
                'qty'=>$qty,
                'fprice'=>$final_price,
            ]);
        }else{
            return redirect("/tour-$code")->with('danger','The booking code that you entered has been used!');
        }
    }

    
}
