<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tax;
use App\Models\Orders;
use App\Models\UsdRates;
use App\Models\Promotion;
use App\Models\Transports;
use App\Models\BookingCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransportType;
use App\Models\TransportPrice;
use App\Models\BusinessProfile;
use App\Models\TransportsImages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoretransportsRequest;
use App\Http\Requests\UpdatetransportsRequest;

class TransportsController extends Controller
{   
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    public function index()
    {   
        $transports=Transports::where('status', '=','active')->paginate(12)->withQueryString();
        $type = TransportType::all();
        $promotions = Promotion::where('status',"Active")->get();
        return view('main.transports', compact('transports'),[
            'type'=> $type,
            "promotions" => $promotions,
        ]);
    }

    public function transportdetail($code)
    {
        $transport = Transports::where('code',$code)->first();
        $agents = Auth::user()->where('status',"Active")->get();
        return view('main.transportdetail',[
                'transport'=>$transport,      
                'agents'=>$agents,      
            ]);
        }

// Search Transport =========================================================================================>
    public function search_transports(Request $request){
        $taxes = Tax::where('id',1)->first();
        $usdrates = UsdRates::where('name','USD')->first();
        $now = Carbon::now();
        $user_id = Auth::user()->id;
        $orders = Orders::where('user_id', $user_id)->get();
        $type = TransportType::all();
        $transports=Transports::where('status', '=','Active')->get();
        $transport_type = $request->get('type');
        $brand = $request->get('brand');
        $promotions = Promotion::where('status',"Active")->get();
        $bk_code = $request->bookingcode;
        if (isset($request->bookingcode)) {
            $bk_code = BookingCode::where('code', $request->bookingcode)->where('status', 'Active')->first();
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
            $pp = 0;
            $promotion_price = 0;
            for ($i=0; $i < $pr; $i++) { 
                $pp = $promotion_price + $promotions[$i]->discounts;
            }
            $promotion_price = $pp;
        }else{
            $promotion_price = 0;
        }
        
        $transports_result = Transports::where('status','Active')
            ->where('brand','LIKE','%'.$brand.'%')
            ->where('type','LIKE','%'. $transport_type.'%')
            ->get();
        if(isset($transports_result) > 0)
            return view('main.transportssearch', compact('transports_result'),[
                'type' => $type,
                'brand' => $brand,
                'transport_type'=>$transport_type,
                'transports_result' => $transports_result,
                'transports' => $transports,
                'bookingcode'=>$bookingcode,
                'bookingcode_status'=>$bookingcode_status,
                'usdrates'=>$usdrates,
                'taxes'=>$taxes,
                'orders'=>$orders,
                'promotions'=>$promotions,
                'promotion_price'=>$promotion_price,
            ]);
        else
            return view('main.transportssearch', compact('transports'),[
                'type' => $type,
                'brand' => $brand,
                'transport_type'=>$transport_type,
                'transports_result' => $transports_result,
                'transports' => $transports,
                'bookingcode'=>$bookingcode,
                'bookingcode_status'=>$bookingcode_status,
                'usdrates'=>$usdrates,
                'taxes'=>$taxes,
                'orders'=>$orders,
                'orders'=>$orders,
                'promotions'=>$promotions,
                'promotions'=>$promotions,
                'promotion_price'=>$promotion_price,
                
        ]);
    }
// View Detail Transport =========================================================================================>
    public function transport_detail($code)
    {
        $usdrates = UsdRates::where('name','USD')->first();
        $business = BusinessProfile::where('id','=',1)->first();
        $now = Carbon::now();
        $transport = Transports::where('code',$code)->first();
        $orders = Orders::all();
        $orderno = count($orders) + 1;
        $similartransports = Transports::where('status',"Active")
                ->where('code','!=',$code)
                ->where('type', $transport->type)->get();
        $prices_airport_shuttle = TransportPrice::where('transports_id','=',$transport->id)
        ->where('type','Airport Shuttle')
        ->get();
        $prices_daily_rent = TransportPrice::where('transports_id','=',$transport->id)
        ->where('type','Daily Rent')
        ->get();
        $prices_transfers = TransportPrice::where('transports_id','=',$transport->id)
        ->where('type','Transfers')
        ->get();
        $harga_sebelum_pajak = (ceil($transport->contract_rate / $usdrates->rate))+$transport->markup;
        $taxes = Tax::where('id',1)->first();
        $agents = Auth::user()->where('status',"Active")->get();
        $promotions = Promotion::where('status',"Active")->get();
        $bookingcode_status = null;
        $bookingcode = null;
        if (isset($promotions)){
            $pr = count($promotions);
            $promotion_price = 0;
            for ($i=0; $i < $pr; $i++) { 
                $promotion_price = $promotion_price + $promotions[$i]->discounts;
            }
        }else{
            $promotion_price = 0;
        }
        return view('main.transportdetail',[
            'taxes'=>$taxes,
            'usdrates'=>$usdrates,
            'prices_airport_shuttle'=>$prices_airport_shuttle,
            'prices_daily_rent'=>$prices_daily_rent,
            'prices_transfers'=>$prices_transfers,
            'orderno'=>$orderno,
            'transport'=>$transport,
            'agents'=>$agents,      
            'business'=>$business,
            'now' => $now,
            'similartransports' => $similartransports,   
            'harga_sebelum_pajak' => $harga_sebelum_pajak,   
            'promotions' => $promotions,
            'promotion_price' => $promotion_price,
            'bookingcode'=>$bookingcode,
            'bookingcode_status'=>$bookingcode_status,
        ]);
    }
// View Detail Transport Bookingcode =========================================================================================>
    public function transport_detail_bookingcode($code,$bcode)
    {
        $user_id = Auth::user()->id;
        $order = Orders::where('user_id', $user_id)->get();
        $usdrates = UsdRates::where('name','USD')->first();
        $business = BusinessProfile::where('id','=',1)->first();
        $now = Carbon::now();
        $transport = Transports::where('code',$code)->first();
        $orders = Orders::all();
        $orderno = count($orders) + 1;
        $similartransports = Transports::where('status',"Active")
                ->where('code','!=',$code)
                ->where('type', $transport->type)->get();
        $prices_airport_shuttle = TransportPrice::where('transports_id','=',$transport->id)
        ->where('type','Airport Shuttle')
        ->get();
        $prices_daily_rent = TransportPrice::where('transports_id','=',$transport->id)
        ->where('type','Daily Rent')
        ->get();
        $prices_transfers = TransportPrice::where('transports_id','=',$transport->id)
        ->where('type','Transfers')
        ->get();
        $harga_sebelum_pajak = (ceil($transport->contract_rate / $usdrates->rate))+$transport->markup;
        $prices = TransportPrice::where('transports_id','=',$transport->id)->get();
        $taxes = Tax::where('id',1)->first();
        $tax = ceil($taxes->tax * $harga_sebelum_pajak);
        $agents = Auth::user()->where('status','Active')->get();
        $harga_sebelum_pajak = (ceil($transport->contract_rate / $usdrates->rate))+$transport->markup;
        $promotions = Promotion::where('status',"Active")->get();
        $bk_code = $bcode;
        if (isset($bk_code)) {
            $find_bookingcode = BookingCode::where('code',$bk_code)->where('status', 'Active')->first();
            if (isset($find_bookingcode)) {
                $usedcode = $order->where('bookingcode', $find_bookingcode->code)->first();
                if (isset($usedcode)) {
                    $bookingcode_status = "Expired";
                    $bookingcode = $find_bookingcode->code;
                }else{
                    
                    if ($find_bookingcode->expired_date > $now) {
                        $bookingcode_status = "Valid";
                        $bookingcode = $find_bookingcode;
                    }else{
                        $bookingcode_status = "Expired";
                        $bookingcode = $find_bookingcode->code ;
                    }
                }
            }else {
                $bookingcode_status = "Invalid";
                $bookingcode = $bk_code ;
            }
        }else{
            $bookingcode_status = "";
            $bookingcode = "" ;
        }
        return view('main.transportdetail',[
            'taxes'=>$taxes,
            'usdrates'=>$usdrates,
            'prices'=>$prices,
            'orderno'=>$orderno,
            'transport'=>$transport,
            'agents'=>$agents,      
            'business'=>$business,
            'now' => $now,
            'similartransports' => $similartransports,
            'prices_airport_shuttle'=>$prices_airport_shuttle,
            'prices_daily_rent'=>$prices_daily_rent,
            'prices_transfers'=>$prices_transfers,
            'harga_sebelum_pajak'=>$harga_sebelum_pajak,
            'bookingcode'=>$bookingcode,
            'bookingcode_status'=>$bookingcode_status,
            'usdrates'=>$usdrates,
            'tax'=>$tax,
            'order'=>$order,
            'promotions'=>$promotions,
        ]);
    }
    public function transport_check_code(Request $request){
        $transport = Transports::where('id',$request->transport_id)->first();
        $bk_code = $request->bookingcode;
        if (isset($bk_code)) {
            return redirect("/transport-$transport->code-$bk_code");
        }else{
            return redirect("/transport-$transport->code");
        }
    }

// View Order Transport =========================================================================================>
    public function order_transport(Request $request,$id){
        $now = Carbon::now();
        $order = Orders::all();
        $orderno = count($order) + 1;
        $price = TransportPrice::findOrFail($id);
        $transport = Transports::where('id',$price->transports_id)->first();
        $usdrates = UsdRates::where('name','USD')->first();
        $business = BusinessProfile::where('id','=',1)->first();
        $taxes = Tax::where('id',1)->first();
        $agents = Auth::user()->where('status',"Active")->get();
        $promotions = Promotion::where('status',"Active")->get();
        $bookingcode_status = null;
        $bookingcode = null;
        if (isset($promotions)){
            $pr = count($promotions);
            $promotion_price = 0;
            for ($i=0; $i < $pr; $i++) { 
                $promotion_price = $promotion_price + $promotions[$i]->discounts;
            }
        }else{
            $promotion_price = 0;
        }
        $price_non_tax = (ceil($price->contract_rate / $usdrates->rate))+$price->markup;
        $tax = ceil(($taxes->tax/100) * $price_non_tax);
        $normal_price = $price_non_tax + $tax;

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
        return view('form.order-transport',[
            'now'=>$now,
            'orderno'=>$orderno,
            'price'=>$price,
            'transport'=>$transport,
            'usdrates'=>$usdrates,
            'business'=>$business,
            'taxes'=>$taxes,
            'agents'=>$agents,
            'promotions'=>$promotions,
            'promotion_price'=>$promotion_price,
            'bookingcode_status'=>$bookingcode_status,
            'bookingcode'=>$bookingcode,
            'normal_price'=>$normal_price,
            'final_price'=>$final_price,
        ]);
    }

// View Galery Edit =============================================================================================================>
    public function view_edit_galery_transport($id)
    {
        $transports=Transports::findOrFail($id);
        return view('form.transportgaleryedit')->with('transports',$transports);
    }

// Function Transport delete =============================================================================================================>
    public function destroy_transport($id)
    {
         $transports=Transports::findOrFail($id);

         if (File::exists("images/cover/".$transports->cover)) {
             File::delete("images/cover/".$transports->cover);
         }
         $images=TransportsImages::where("transports_id",$transports->id)->get();
         foreach($images as $image){
         if (File::exists("images/transports".$image->image)) {
            File::delete("images/transports".$image->image);
        }
         }
         $transports->delete();
         return back();


    }

// Function Transport image delete =============================================================================================================>
    public function delete_image_transport($id){
        $images=TransportsImages::findOrFail($id);
        if (File::exists("images/transports/".$images->image)) 
        {
           File::delete("images/transports/".$images->image);
        }

       TransportsImages::find($id)->delete();
       return back();
    }

// Function Cover Transport delete =============================================================================================================>
    public function delete_cover_transport($id){
        $cover=Transports::findOrFail($id)->cover;
        if (File::exists("images/cover/".$cover)) 
        {
            File::delete("images/cover/".$cover);
        }
        return back();
    }
    

}