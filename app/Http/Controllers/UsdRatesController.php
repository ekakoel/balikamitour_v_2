<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tax;
use App\Models\Tours;
use App\Models\UserLog;
use App\Models\UsdRates;
use App\Models\BankAccount;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StoreUsdRatesRequest;
use AmrShawky\LaravelCurrency\Facade\Currency;

class UsdRatesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    public function index()
    {
        $usdrates = UsdRates::where('name','USD')->first();
        $cnyrates = UsdRates::where('name','CNY')->first();
        $twdrates = UsdRates::where('name','TWD')->first();
        $now = Carbon::now();
        $tax = Tax::where('id',1)->first();
        $from = 'USD';
        $to = 'IDR';
        $amount= 1;
        $bank_acc = BankAccount::all();
        // $converted=Currency::convert()
        //     ->from($from) //currncy you are converting
        //     ->to($to)     // usdrates you are converting to
        //     ->amount($amount) // amount in USD you converting to IDR
        //     ->round(2)
        //     ->date($now)
        //     ->source('crypto')
        //     ->get();
        
        // https://exchangerate.host/dashboard
        // $endpoint = 'convert';
        // $access_key = 'dfaadf4d4196f5937b8894b301fb36f5';
        // $usd = 'USD';
        // $cny = 'CNY';
        // $twd = 'TWD';
        // $to = 'IDR';
        // $amount = 1;
        // $us = curl_init('http://api.exchangerate.host/'.$endpoint.'?access_key='.$access_key.'&from='.$usd.'&to='.$to.'&amount='.$amount.'');
        // $cn = curl_init('http://api.exchangerate.host/'.$endpoint.'?access_key='.$access_key.'&from='.$cny.'&to='.$to.'&amount='.$amount.'');
        // $tw = curl_init('http://api.exchangerate.host/'.$endpoint.'?access_key='.$access_key.'&from='.$twd.'&to='.$to.'&amount='.$amount.'');
        // curl_setopt($us, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($cn, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($tw, CURLOPT_RETURNTRANSFER, true);
        // $json_usd = curl_exec($us);
        // $json_cny = curl_exec($cn);
        // $json_twd = curl_exec($tw);
        // curl_close($us);
        // curl_close($cn);
        // curl_close($tw);
        // $conversionUsd = json_decode($json_usd, true);
        // $conversionCny = json_decode($json_cny, true);
        // $conversionTwd = json_decode($json_twd, true);
        // $usd_rate = $conversionUsd["result"];
        // $cny_rate = $conversionCny["result"];
        // $twd_rate = $conversionTwd["result"];
        $apiKey = config('app.exchange_rate_api_key');
        $baseUrl = "https://v6.exchangerate-api.com/v6/{$apiKey}/latest/USD";

        $response = Http::get($baseUrl);
        $rates = $response->json();

        if ($response->successful()) {
            $idrRate = $rates['conversion_rates']['IDR'];
            $usdRate = $rates['conversion_rates']['USD'];
            $twdRate = $rates['conversion_rates']['TWD'];
            $cnyRate = $rates['conversion_rates']['CNY'];

            $usdIdr = $usdRate*$idrRate;
            $twdIdr = $idrRate/$twdRate;
            $cnyIdr = $idrRate/$cnyRate;
            return view('admin.currency',[
                "usdrates" => $usdrates, 
                "cnyrates" => $cnyrates, 
                "twdrates" => $twdrates, 
                "now"=>$now,
                'tax'=>$tax,
                'bank_acc'=>$bank_acc,
                'usd_rate' => $usdIdr,
                'twd_rate' => $twdIdr,
                'cny_rate' => $cnyIdr,
                // 'usd_rate'=>$usd_rate,
                // 'cny_rate'=>$cny_rate,
                // 'twd_rate'=>$twd_rate,
            ]);
        }else{
            return view('admin.currency')->withErrors('Unable to retrieve exchange rates.');
        }
    }

    public function func_update_usdrates(Request $request,$id)
    {
        if (Gate::any(['posDev', 'posAuthor'])) {
            $rate_usd=UsdRates::findOrFail($id);
            $sell = $request->sell;
            $buy = $sell - $request->difference;
            $rate_usd->update([
                "rate"=>$sell,
                "sell"=>$sell,
                "buy"=>$buy,
            ]);
            // USER LOG
            $action = "Update UsdRates";
            $service = "UsdRates";
            $subservice = "Usd";
            $page = "usdrates";
            $note = "Update UsdRates: ".$id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$id,
                "page"=>$page,
                "user_id"=>$request->author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            return redirect("/currency")->with('success','The UsdRates has been successfully updated!');
        }else{
            return redirect("/currency")->with('error','Tidak dapat merubah data!');
        }
    }

    public function func_update_cnyrates(Request $request,$id)
    {
        if (Gate::any(['posDev', 'posAuthor'])) {
            $rate_cny=UsdRates::findOrFail($id);
            $sell = $request->sell;
            $buy = $sell - $request->difference;
            $rate_cny->update([
                "rate"=>$sell,
                "sell"=>$sell,
                "buy"=>$buy,
            ]);
            // USER LOG
            $action = "Update CNY Rate";
            $service = "CNY Rate";
            $subservice = "CNY";
            $page = "Chinese Yuan";
            $note = "Update CNY: ".$id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$id,
                "page"=>$page,
                "user_id"=>$request->author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            return redirect("/currency")->with('success','The Yuan Rate has been successfully updated!');
        }else{
            return redirect("/currency")->with('error','Anda tidak dapat merubah data!');
        }
    }

    public function func_update_twdrates(Request $request,$id)
    {
        if (Gate::any(['posDev', 'posAuthor'])) {
            $rate_twd=UsdRates::findOrFail($id);
            $sell = $request->sell;
            $buy = $sell - $request->difference;
            $rate_twd->update([
                "rate"=>$sell,
                "sell"=>$sell,
                "buy"=>$buy,
            ]);
            // USER LOG
            $action = "Update TWD Rate";
            $service = "TWD Rate";
            $subservice = "TWD";
            $page = "Taiwan Dolars";
            $note = "Update TWD: ".$id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$id,
                "page"=>$page,
                "user_id"=>$request->author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            return redirect("/currency")->with('success','The Taiwan Dolar has been successfully updated!');
        }else{
            return redirect("/currency")->with('error','Anda tidak dapat merubah data!');
        }
    }

    public function func_update_tax(Request $request,$id)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $tax=Tax::findOrFail($id);
            $tax->update([
                "tax"=>$request->tax,
            ]);
            // USER LOG
            $action = "Update Tax";
            $service = "Tax";
            $subservice = "Tax";
            $page = "usdrates";
            $note = "Update Tax: ".$id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$id,
                "page"=>$page,
                "user_id"=>$request->author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            
            return redirect("/currency")->with('success','The UsdRates has been successfully updated!');
        }else{
            return redirect("/currency")->with('error','Tidak dapat merubah data!');
        }
    }

    public function showRates()
    {
        $apiKey = config('exchange_rate_api_key');
        $baseUrl = "https://v6.exchangerate-api.com/v6/{$apiKey}/latest/USD";

        $response = Http::get($baseUrl);
        $rates = $response->json();

        if ($response->successful()) {
            $usdRate = $rates['conversion_rates']['USD'];
            $twdRate = $rates['conversion_rates']['TWD'];
            $cnyRate = $rates['conversion_rates']['CNY'];

            return view('currency-rates', [
                'usdRate' => $usdRate,
                'twdRate' => $twdRate,
                'cnyRate' => $cnyRate,
            ]);
        } else {
            return view('currency-rates')->withErrors('Unable to retrieve exchange rates.');
        }
    }
}
