<?php

namespace App\Http\Controllers;
use Image;
use Carbon\Carbon;
use App\Models\Tax;
use App\Models\Tours;
use App\Models\LogData;
use App\Models\UserLog;
use App\Models\Partners;
use App\Models\UsdRates;
use App\Models\ActionLog;
use App\Models\Attention;
use App\Models\TourPrices;
use App\Models\ToursImages;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoretoursRequest;
use App\Http\Requests\UpdatetoursRequest;

class ToursAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','can:isAdmin']);
    }
    public function index()
    {
        $taxes = Tax::where('id',1)->first();
        $tours = Tours::all();
        $activetours=Tours::where('status','!=','Archived')
        ->where('status','!=','Removed')->get();
        $archivetours=Tours::where('status', '=','Archived')->get();
        $cactivetours=Tours::where('status', '=','Active')->get();
        $drafttours=Tours::where('status', '=','Draft')->get();
        $usdrates = UsdRates::where('name','USD')->first();
        $partners = Partners::all();
        return view('admin.toursadmin', compact('activetours'),[
            'taxes'=>$taxes,
            "cactivetours"=>$cactivetours,
            "usdrates" => $usdrates,
            "activetours" => $activetours,
            "archivetours" => $archivetours,
            "drafttours" => $drafttours,
            "partners" => $partners,
        ]);
    }

// View Admin Detail Tour =========================================================================================>
    public function view_detail_tour($id)
    {
        $taxes = Tax::where('id',1)->first();
        $user = Auth::user()->all();
        $tours = Tours::findOrFail($id);
        $prices = TourPrices::where('tours_id',$tours->id)->get();
        $usdrates = UsdRates::where('name','USD')->first();
        $attentions = Attention::where('page','admin-tour-detail')->get();
        $taxes = Tax::where('id',1)->first();
        $action_log = ActionLog::where('service',"Tour Package")
        ->where('service_id',$id)->get();
        $partner = Partners::where('id', $tours->partners_id)->first();
        return view('admin.toursadmindetail',[
            'taxes'=>$taxes,
            'usdrates'=>$usdrates,
            'tours'=>$tours, 
            'attentions'=>$attentions,
            'action_log'=>$action_log,
            'user'=>$user,
            'taxes'=>$taxes,
            'partner'=>$partner,
            'prices'=>$prices,
        ]);
    }
// View Tour Edit =============================================================================================================>
    public function view_edit_tour($id)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $attentions = Attention::where('page','admin-tour-edit')->get();
            $tours=Tours::findOrFail($id);
            $usdrates = UsdRates::where('name','USD')->first();
            $partner = Partners::where('id',$tours->partners_id)->first();
            $partners = Partners::all();
            return view('form.touredit',[
                'usdrates'=>$usdrates,
                'attentions'=>$attentions,
                "partner"=>$partner,
                "partners"=>$partners,
            ])->with('tours',$tours);
        }else{
            return redirect("/tours-admin")->with('error','Akses ditolak');
        }
    }
// View Add Tours =========================================================================================>
    public function view_add_tour()
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $attentions = Attention::where('page','admin-tour-add')->get();
            $tours = Tours::all();
            $partners = Partners::all();
            return view('form.touradd',[
                'attentions'=>$attentions,
                'partners'=>$partners,
            ])->with('tours',$tours);
        }else{
            return redirect("/tours-admin")->with('error','Akses ditolak');
        }
    }

// Function Add Tours =========================================================================================>
    public function func_add_tour(Request $request)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'destinations' => 'required',
                'location' => 'required',
                'type' => 'required',
                'duration' => 'required',
                'description' => 'required',
                'itinerary'=> 'required',
                'include' => 'required',
            ]);
            if($request->hasFile('cover')){
                $file=$request->file('cover');
                $coverName=time().'_'.$file->getClientOriginalName();

                // $destinationPath = public_path('storage/tours/tours-cover/thumbnail');
                // $img = Image::make($file->path());
                // $img->resize(300, 200, function ($constraint) {
                //     $constraint->aspectRatio();
                // })->save($destinationPath.'/'.$coverName);


                $file->move("storage/tours/tours-cover",$coverName);
                $status="Draft";
                $code=Str::random(26);
                $tour =new Tours([
                    "name"=>$request->name,
                    "partners_id"=>$request->partners_id,
                    "code"=>$code,
                    "destinations"=>$request->destinations,
                    "location"=>$request->location,
                    "type" =>$request->type, 
                    "duration"=>$request->duration,
                    "description"=>$request->description,
                    "include"=>$request->include,
                    "itinerary"=>$request->itinerary,
                    "additional_info"=>$request->additional_info,
                    "cancellation_policy"=>$request->cancellation_policy,
                    "status"=>$status,
                    "author_id"=>$request->author,
                    "cover" =>$coverName,
                ]);
                $tour->save();
            }
            // USER LOG
            $action = "Add Tour";
            $service = "Tour";
            $subservice = "Tour Package";
            $page = "add-tour";
            $note = "Add Tour Package: ".$tour->id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$tour->id,
                "page"=>$page,
                "user_id"=>$request->author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            return redirect("/detail-tour-$tour->id")->with('success','New Tour Package has been successfully created!');
        }else{
            return redirect("/tours-admin")->with('error','Akses ditolak');
        }
    }

// Function Add Tours =========================================================================================>
    public function func_add_tour_price(Request $request,$id)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $tour = Tours::where('id',$id)->first();
            $expired_date = date('Y-m-d',strtotime($request->expired_date));
            $status = "Draft";
            $price =new TourPrices([
                "tours_id"=>$id,
                "min_qty"=>$request->min_qty,
                "max_qty"=>$request->max_qty,
                "contract_rate"=>$request->contract_rate,
                "markup"=>$request->markup,
                "expired_date"=>$expired_date,
                "status"=>$status,
            ]);
            $price->save();
            // USER LOG
            $author = Auth::user()->id;
            $action = "Add Tour Price";
            $service = "Tour";
            $subservice = "Tour Package";
            $page = "detail-tour";
            $note = "Add Tour Price: ".$id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$id,
                "page"=>$page,
                "user_id"=>$author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            return redirect("/detail-tour-$id#prices")->with('success','New Tour Package Price has been successfully created!');
        }else{
            return redirect("/tours-admin")->with('error','Akses ditolak');
        }
    }


// function Update Tour PRICE =============================================================================================================>
    public function func_update_tour_price(Request $request,$id)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $tour_price=TourPrices::findOrFail($id);
            $tour_price->update([
                "min_qty"=>$request->min_qty,
                "max_qty"=>$request->max_qty,
                "contract_rate"=>$request->contract_rate,
                "markup"=>$request->markup,
                "expired_date"=>$request->expired_date,
                "status"=>$request->status,
            ]);

            // USER LOG
            $author = Auth::user()->id;
            $action = "Update Tour Price";
            $service = "Tour";
            $subservice = "Price";
            $page = "detail-tour";
            $note = "Update Tour Price: ".$id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$id,
                "page"=>$page,
                "user_id"=>$author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            return redirect("/detail-tour-$tour_price->tours_id#prices")->with('success','The Tour Price has been successfully updated!');
        }else{
            return redirect("/tours-admin")->with('error','Akses ditolak');
        }
    }
// FUNCTION DELETE TOUR PRICE
    public function func_delete_tour_price(Request $request,$id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $tour_price=TourPrices::findOrFail($id);
            $action="Delete Tour Price";
            $author= Auth::user()->id;
            $tour_price->delete();
            // USER LOG
            $action = "Remove";
            $service = "Tour Package";
            $subservice = "Price";
            $page = "detail-tour";
            $note = "Remove Tour Price on Tour : ".$tour_price->tours_id.", Price id : ".$id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$id,
                "page"=>$page,
                "user_id"=>$author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            return redirect("/detail-tour-$tour_price->tours_id#prices")->with('success','The Tour Price has been successfully deleted!');
        }else{
            return redirect("/tours-admin")->with('error','Akses ditolak');
        }
    }
// function Update Tour =============================================================================================================>
    public function func_update_tour(Request $request,$id)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $tour=Tours::findOrFail($id);
            $initial_data = $tour;
            if($request->hasFile("cover")){
                if (File::exists("storage/tours/tours-cover/".$tour->cover)) {
                    File::delete("storage/tours/tours-cover/".$tour->cover);
                }
                $file=$request->file("cover");
                $tour->cover=time()."_".$file->getClientOriginalName();
                $file->move("storage/tours/tours-cover",$tour->cover);
                $request['cover']=$tour->cover;
            }
            if ($request->status == "Active") {
                $partner=Partners::where('id',$tour->partners_id)->first();
                if (isset($partner)) {
                    $partner->update([
                        "status"=>"Active",
                    ]);
                }
            }
            $tour->update([
                "name"=>$request->name,
                "partners_id"=>$request->partners_id,
                "destinations"=>$request->destinations,
                "location"=>$request->location,
                "type" =>$request->type, 
                "duration"=>$request->duration,
                "description"=>$request->description,
                "itinerary"=>$request->itinerary,
                "include"=>$request->include,
                "additional_info"=>$request->additional_info,
                "cancellation_policy"=>$request->cancellation_policy,
                "status"=>$request->status,
                "author_id"=>$request->author,
                "cover" =>$tour->cover,
            ]);

            // USER LOG
            $action = "Update Tour";
            $service = "Tour";
            $subservice = "Tour Package";
            $page = "edit-tour";
            $note = "Update Tour Package: ".$id;
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
            return redirect("/detail-tour-$tour->id")->with('success','The Tour Package has been successfully updated!');
        }else{
            return redirect("/tours-admin")->with('error','Akses ditolak');
        }
    }

// function Tour Remove =============================================================================================================>
    public function remove_tour(Request $request,$id)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $tour=Tours::findOrFail($id);
            $status = "Removed";
            $author = Auth::user()->id;
            $tour->update([
                "status"=>$status,
            ]);
            // USER LOG
            $action = "Remove Tour";
            $service = "Tour";
            $subservice = "Tour Package";
            $page = "tours-admin";
            $note = "Remove Tour Package: ".$id;
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
            return back()->with('success','The Tour Package has been successfully deleted!');
        }else{
            return redirect("/tours-admin")->with('error','Akses ditolak');
        }
    }
}