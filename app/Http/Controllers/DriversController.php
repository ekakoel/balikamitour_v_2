<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Drivers;
use App\Models\Attention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DriversController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $drivers = Drivers::all();
        $attentions = Attention::where('page','drivers-admin')->get();
        return view('drivers.drivers-admin',[
            'now'=>$now,
            'drivers'=>$drivers,
            'attentions'=>$attentions,
        ]);
    }

    public function create(Request $request)
    {
        $name = $request->name;
        $phone = $request->phone;
        $email =$request->email;
        $license =$request->license;
        $address =$request->address;
        $country =$request->country;
        $driver =new Drivers([
            "name"=>$name,
            "phone"=>$phone,
            "email"=>$email,
            "license"=>$license,
            "address"=>$address,
            "country"=>$country,
            
        ]);
        $driver->save();
        return redirect()->back()->with('success','New Drivers has been created');
    }

    public function edit(Request $request,$id)
    {
        $driver=Drivers::findOrFail($id);
        $driver->update([
            "name" =>$request->name, 
            "phone"=>$request->phone,
            "email"=>$request->email,
            "license"=>$request->license,
            "address"=>$request->address,
            "country"=>$request->country,
        ]);
        return redirect("/drivers-admin")->with('success','Drivers has been updated');
    }
    
    public function destroy(Request $request,$id)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor') or Gate::allows('posRsv')) {
            $driver=Drivers::findOrFail($id);
            $driver->delete();
            return back()->with('success','Drivers has been deleted');
        }else{
            return redirect("/drivers-admin")->with('error','Akses ditolak');
        }
    }
}
