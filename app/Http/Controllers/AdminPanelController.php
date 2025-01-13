<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tours;
use App\Models\Hotels;
use App\Models\Orders;
use App\Models\UserLog;
use App\Models\Services;
use App\Models\UsdRates;
use App\Models\Weddings;
use App\Models\Activities;
use App\Models\Attention;
use App\Models\AdminPanel;
use App\Models\Transports;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAdminPanelRequest;
use App\Http\Requests\UpdateAdminPanelRequest;

class AdminPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified','type:admin']);
    }
    public function index()
    {
        $attentions = Attention::where('page','admin-tour-edit')->get();
        $usdrates = UsdRates::where('name','USD')->first();
        $cnyrates = UsdRates::where('name','CNY')->first();
        $twdrates = UsdRates::where('name','TWD')->first();
        $now = Carbon::now();
        $adminpanel = AdminPanel::all();
        $attentions = Attention::where('page','admin-panel')->get();
        $services = Services::all();
        $activetours = Tours::where('status','Active')->get();
        $validorders = Orders::where('status','Active')
            ->get();
        $activeorders = Orders::where('status','Confirmed')
            ->where('checkin', '>=', $now)
            ->get();
        $pendingorders = Orders::where('status','Pending')
            ->where('checkin', '>=', $now)
            ->get();
        $invalidorders = Orders::where('status','Invalid')
            ->where('checkin', '>=', $now)
            ->get();
        $rejectedorders = Orders::where('status','Rejected')
            ->where('checkin', '>=', $now)
            ->get();
        $total_price_active_order = Orders::where('status','Confirmed')
            ->where('checkin', '>=', $now)
            ->sum('final_price');
        $total_price_invalid_order = Orders::where('status','Invalid')
            ->where('checkin', '>=', $now)
            ->sum('final_price');
        $total_price_pending_order = Orders::where('status','Pending')
            ->where('checkin', '>=', $now)
            ->sum('final_price');
        $total_price_rejected_order = Orders::where('status','Rejected')
            ->where('checkin', '>=', $now)
            ->sum('final_price');
        $total_price_valid_order = Orders::where('status','Approved')
            ->sum('final_price');
        $mindate = Orders::where('status','Active')
            ->min('checkin');
        $maxdate = Orders::where('status','Active')
            ->max('checkin');
        $hotels_active = Hotels::where('status','Active')->get();
        $tours_active = Tours::where('status','Active')->get();
        $activities_active = Activities::where('status','Active')->get();
        $transports_active = Transports::where('status','Active')->get();
        $weddings_active = Weddings::where('status','Active')->get();
        return view('admin.adminpanel',compact('adminpanel'),[
            'attentions'=>$attentions,
            'usdrates'=>$usdrates,
            'cnyrates'=>$cnyrates,
            'twdrates'=>$twdrates,
            'mindate'=>$mindate,
            'maxdate'=>$maxdate,
            'services'=>$services,
            'attentions'=>$attentions,
            'activetours'=>$activetours,
            'validorders'=>$validorders,
            'activeorders'=>$activeorders,
            'pendingorders'=>$pendingorders,
            'invalidorders'=>$invalidorders,
            'rejectedorders'=>$rejectedorders,
            'total_price_active_order'=>$total_price_active_order,
            'total_price_pending_order'=>$total_price_pending_order,
            'total_price_invalid_order'=>$total_price_invalid_order,
            'total_price_rejected_order'=>$total_price_rejected_order,
            'total_price_valid_order'=>$total_price_valid_order,
            'hotels_active'=>$hotels_active,
            'tours_active'=>$tours_active,
            'activities_active'=>$activities_active,
            'transports_active'=>$transports_active,
            'weddings_active'=>$weddings_active,
        ]);
    }

// FUNCTION ADD SERVICE =============================================================================================================>
    public function func_add_service(Request $request)
        {
            $status = "Draft";
            $service =new Services([
                "name"=>$request->name,
                "nicname"=>$request->nicname,
                "icon"=>$request->icon,
                "status"=>$status,
            ]);
            $service->save();
            
            // USER LOG
            $service_id = 1;
            $action = "Add";
            $service = "Service";
            $subservice = $request->name;
            $page = "admin-panel";
            $note = "Add Service";
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$service_id,
                "page"=>$page,
                "user_id"=>$request->author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            return redirect("/admin-panel")->with('success','Service has been added!');
        }

// FUNCTION EDIT SERVICE =============================================================================================================>
    public function func_edit_service(Request $request,$id)
    {
        $service=Services::findOrFail($id);
        $service->update([
            "name"=>$request->name,
            "nicname"=>$request->nicname,
            "icon"=>$request->icon,
            "status"=>$request->status,
        ]);

        // USER LOG
        $action = "Edit Service";
        $service = "Service";
        $subservice = "Disable Service";
        $page = "admin-panel";
        $note = "Update Service: ".$id;
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
        return redirect("/admin-panel")->with('success','Service has been Updated!');
    }

// FUNCTION DISABLE SERVICE =============================================================================================================>
    public function func_disable_service(Request $request,$id)
    {
        $service=Services::findOrFail($id);
        $service->update([
            "status"=>$request->status,
        ]);

        // USER LOG
        $action = "Update Service";
        $service = "Service";
        $subservice = "Disable Service";
        $page = "admin-panel";
        $note = "Update Service: ".$id;
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
        return redirect("/admin-panel")->with('success','Service has been disable!');
    }

// FUNCTION ENNABLE SERVICE =============================================================================================================>
    public function func_enable_service(Request $request,$id)
    {
        $service=Services::findOrFail($id);
        $service->update([
            "status"=>$request->status,
        ]);

        // USER LOG
        $action = "Update Service";
        $service = "Service";
        $subservice = "Enable Service";
        $page = "admin-panel";
        $note = "Update Service: ".$id;
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
        return redirect("/admin-panel")->with('success','Service has been activated!');
    }

// FUNCTION REMOVE SERVICE =============================================================================================================>
    public function func_remove_service(Request $request,$id)
    {
        $service=Services::findOrFail($id);
        $service->delete();
       
        // USER LOG
        $action = "Remove Service";
        $service = "Service";
        $subservice = $request->service;
        $page = "admin-panel";
        $note = "Remove service: ".$id;
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
        return redirect("/admin-panel")->with('success','Service has been Removed!');
    }
    
}
