<?php

namespace App\Http\Controllers;
use Image;
use DateTime;
use Carbon\Carbon;
use App\Models\Tax;
use App\Models\Hotels;
use App\Models\Markup;
use App\Models\LogData;
use App\Models\UserLog;
use App\Models\Contract;
use App\Models\ExtraBed;
use App\Models\UsdRates;
use App\Models\ActionLog;
use App\Models\Attention;
use App\Models\HotelRoom;
use App\Models\HotelPrice;
use App\Models\HotelPromo;
use Illuminate\Support\Str;
use App\Models\HotelPackage;
use App\Models\HotelsImages;
use App\Models\OptionalRate;
use Illuminate\Http\Request;
use App\Models\WeddingVenues;
use App\Models\RoomFacilities;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StorehotelsRequest;
use App\Http\Requests\UpdatehotelsRequest;

class HotelsAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified','type:admin']);
    }
// View Admin Index =========================================================================================>
    public function index(){
        $now = Carbon::now();
        $hotels=Hotels::where('status','!=','Archived')
        ->where('status','!=','Removed')->get();
        $archivehotels=Hotels::where('status', '=','Archived')->get();
        $drafthotels=Hotels::where('status', '=','Draft')->get();
        $cactivehotels=Hotels::where('status', '=','Active')->get();
        $activerooms=HotelRoom::where('status', '=','Active')->get();
        $normal_prices = HotelPrice::where('end_date','>=',$now)->orderBy('end_date','desc')->get();
        $promos = HotelPromo::where('book_periode_end','>=',$now)->orderBy('book_periode_end','desc')->get();
        $packages = HotelPackage::where('stay_period_end','>=',$now)->orderBy('stay_period_end','desc')->get();
        foreach ($hotels as $hotel) {
            if (count($normal_prices->where('hotels_id',$hotel->id)) < 1 and count($promos->where('hotels_id',$hotel->id)) < 1 and count($packages->where('hotels_id',$hotel->id)) < 1) {
                $htl=Hotels::findOrFail($hotel->id);
                $rooms = HotelRoom::where('hotels_id',$hotel->id)->get();
                if (isset($rooms)) {
                    foreach ($rooms as $room) {
                        if ($room->status == "Active") {
                            $room->update([
                                "status"=>"Draft",
                            ]);
                        }
                    }
                }
                if ($htl->status == "Active") {
                    $htl->update([
                        "status"=>"Draft",
                    ]);
                }
            }
        }
        return view('admin.hotelsadmin', compact('hotels'),[
            "cactivehotels" => $cactivehotels,
            "archivehotels" => $archivehotels,
            "drafthotels" => $drafthotels,
            "activerooms"=>$activerooms,
            "normal_prices"=>$normal_prices,
            "now"=>$now,
            "promos"=>$promos,
            "packages"=>$packages,
        ]);
    }
    
// View Detail Hotel =========================================================================================>
    public function view_detail_hotel($id){
        $taxes = Tax::where('id',1)->first();
        $now = Carbon::now();
        $hotel = Hotels::find($id);
        $usdrates = UsdRates::where('name','USD')->first();
        $optional_rate = OptionalRate::where('service','Hotel')
            ->where('service_id',$id)->get();
        $markups = Markup::where('service','Hotel')
            ->where('service_id',$id)->first();
            if ($markups != "") {
                $markup = $markups;
            } else {
                $markup = "";
            }
        $promos = HotelPromo::where('hotels_id',$id)
        ->where('book_periode_end','>',$now)
        ->get();
        $contracts = Contract::where('period_end','>',$now)->where('hotels_id',$id)->get();
        $rooms = HotelRoom::where('hotels_id','=',$id)->orderBy('created_at', 'desc')->get();
        $extra_bed = ExtraBed::where('hotels_id',$id)->get();
        $prices = HotelPrice::where('hotels_id','=',$id)
            ->where('end_date','>',$now)
            ->orderBy('start_date', 'asc')->get();
        $package = HotelPackage::where('hotels_id','=',$id)->orderBy('created_at','desc')->get();
        $moonnow = date('m', strtotime($now));
        $user = Auth::user()->all();
        $author = Auth::user()->where('id',$hotel->author_id)->first();
        $attentions = Attention::where('page','admin-hotel-detail')->get();
        $action_log = ActionLog::where('service',"Hotel")->get();
        $weddingVenues = WeddingVenues::where('hotels_id',$id)->get();
        $priceokt = HotelPrice::where('hotels_id','=',$id)
            ->where('rooms_id','=', 1)
            ->orderBy('start_date', 'DESC')->get();
            return view('admin.hotelsadmindetail',[
                'taxes'=>$taxes,
                'optional_rate'=>$optional_rate,
                'extra_bed'=>$extra_bed,
                'usdrates'=>$usdrates,
                'markup'=>$markup,
                'user'=>$user,
                'action_log'=>$action_log,
                'attentions'=>$attentions,
                'package' => $package,
                'priceokt'=>$priceokt,
                'moonnow'=>$moonnow,
                'hotel'=>$hotel,
                'rooms'=>$rooms,
                'prices'=>$prices,
                'now'=>$now,
                'contracts'=>$contracts,
                'author'=>$author,
                'promos'=>$promos,
                'weddingVenues'=>$weddingVenues,
            ]);
        }


// View Hotel Edit =============================================================================================================>
    public function view_edit_hotel($id)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $hotels=Hotels::findOrFail($id);
            $attentions = Attention::where('page','admin-hotel-edit')->get();
            $usdrates=UsdRates::where('name','USD')->first();
            return view('form.hoteledit',[
                'usdrates'=>$usdrates,
                'attentions'=>$attentions,
                ])->with('hotels',$hotels);
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

// View Add Hotel Price =============================================================================================================>
    public function view_add_hotel_price($id)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $hotels=Hotels::findOrFail($id);
            $attentions = Attention::where('page','add-hotel-price')->get();
            $usdrates=UsdRates::where('name','USD')->first();
            $rooms = HotelRoom::where('hotels_id','=',$id)->orderBy('created_at', 'desc')->get();
            $markups = Markup::where('service','Hotel')
            ->where('service_id',$id)->first();
            if ($markups != "") {
                $markup = $markups;
            } else {
                $markup = "";
            }
            return view('form.hotel-add-normal-price',[
                'usdrates'=>$usdrates,
                'attentions'=>$attentions,
                'markups'=>$markups,
                'rooms'=>$rooms,
                ])->with('hotels',$hotels);
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }


// View Room Edit =============================================================================================================>
    public function view_edit_room($id)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $room=HotelRoom::findOrFail($id);
            $hotel=Hotels::where('id','=', $room->hotels_id)->first();
            $attentions = Attention::where('page','admin-room-edit')->get();
            return view('form.roomedit',[
                'hotel'=>$hotel,
                'attentions'=>$attentions,
            ])->with('room',$room);
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
// View Room Edit =============================================================================================================>
    public function view_edit_wedding_venue($id)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $wedding_venue=WeddingVenues::findOrFail($id);
            $hotel=Hotels::where('id','=', $wedding_venue->hotels_id)->first();
            $attentions = Attention::where('page','edit-wedding-venue')->get();
            return view('form.wedding-venue-edit',[
                'hotel'=>$hotel,
                'attentions'=>$attentions,
            ])->with('wedding_venue',$wedding_venue);
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

// View Add Hotels =========================================================================================>
    public function view_add_hotel(){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $hotels = Hotels::all();
            $attentions = Attention::where('page','admin-hotel-add')->get();
            return view('form.hoteladd',[
                'attentions'=>$attentions,
            ])->with('hotels',$hotels);
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

// View Edit Galery =============================================================================================================>
    public function view_edit_galery_hotel($id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $hotels=Hotels::findOrFail($id);
            return view('form.hotelgaleryedit')->with('hotels',$hotels);
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
// View Add Room =========================================================================================>
    public function view_add_room($id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $hotels=Hotels::findOrFail($id);
            $attentions=Attention::where('page','admin-hotel-room-add')->get();
            $usdrates=UsdRates::where('name','USD')->first();
            return view('form.roomadd',[
                'attentions'=>$attentions,
                'usdrates'=>$usdrates,
            ])->with('hotels',$hotels);
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
// View Add Wedding Venue =========================================================================================>
    public function view_add_wedding_venue($id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $hotels=Hotels::findOrFail($id);
            $attentions=Attention::where('page','admin-wedding-venue-add')->get();
            $usdrates=UsdRates::where('name','USD')->first();
            return view('form.wedding-venue-add',[
                'attentions'=>$attentions,
                'usdrates'=>$usdrates,
            ])->with('hotels',$hotels);
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

// View Add Promo =============================================================================================================>
    public function view_add_promo($id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $hotel=Hotels::findOrFail($id);
            $rooms= HotelRoom::where('hotels_id','=');
            return view('form.hotelpromoadd')->with('hotel',$hotel);
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
    
// Function Add Hotels =========================================================================================>
    public function func_add_hotel(Request $request){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'region' => 'required',
                'address' => 'required',
                'description' => 'required',
                'phone' => 'required',
                'web' => 'required',
                'cover' => 'required',
                'airport_duration' => 'required',
                'airport_distance' => 'required',
            ]);

            if($request->hasFile("cover")){
                $file=$request->file("cover");
                $coverName=time().'_'.$file->getClientOriginalName();
                $file->move("storage/hotels/hotels-cover/",$coverName);
                $status="Draft";
                $code=Str::random(26);
                $hotel =new Hotels([
                    "name"=>$request->name,
                    "code"=>$code,
                    "region"=>$request->region,
                    "address" =>$request->address, 
                    "contact_person"=>$request->contact_person,
                    "description"=>$request->description,
                    "phone"=>$request->phone,
                    "additional_info"=>$request->additional_info,
                    "facility"=>$request->facility,
                    "status"=>$status,
                    "web" => $request->web,
                    "map" => $request->map,
                    "include" => $request->include,
                    "author_id"=>$request->author,
                    "cancellation_policy"=>$request->cancellation_policy,
                    "cover" =>$coverName,
                    "min_stay" =>$request->min_stay,
                    "max_stay" =>$request->max_stay,
                    "airport_distance" =>$request->airport_distance,
                    "airport_duration" =>$request->airport_duration,
                ]);
                $hotel->save();
            }
            // USER LOG
            $action = "Add Hotel";
            $service = "Hotel";
            $subservice = "Hotel";
            $page = "add-hotel";
            $note = "Add new Hotel with Hotel id : ".$hotel->id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$hotel->id,
                "page"=>$page,
                "user_id"=>$request->author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            return redirect("/detail-hotel-$hotel->id")->with('success', 'Hotel added successfully');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
// Function Add Contract =========================================================================================>
public function func_add_contract(Request $request){
    if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
        if ($request->hasFile("file_name")) {
            $request->validate([
                'file_name' => 'required|file|mimes:pdf,doc,docx',
                'period_start' => 'required|date',
                'period_end' => 'required|date|after_or_equal:period_start',
                'hotels_id' => 'required|exists:hotels,id',
                'contract_name' => 'required|string',
            ]);
        
            try {
                $fileContract = $request->file("file_name");
                $contractName = time() . "_" . $fileContract->getClientOriginalName();
                $fileContract->move("storage/hotels/hotels-contract/", $contractName);
        
                $periodStart = date('Y-m-d', strtotime($request->period_start));
                $periodEnd = date('Y-m-d', strtotime($request->period_end));
                $hotelsId = $request->hotels_id;
        
                $contract = new Contract([
                    "name" => $request->contract_name,
                    "hotels_id" => $hotelsId,
                    "period_start" => $periodStart,
                    "period_end" => $periodEnd,
                    "file_name" => $contractName,
                ]);
                $contract->save();
                 // USER LOG
                $action = "Add Contract";
                $service = "Hotel";
                $subservice = "Contract";
                $page = "hotel_detail";
                $note = "Add new contract : ".$hotelsId;
                $user_log =new UserLog([
                    "action"=>$action,
                    "service"=>$service,
                    "subservice"=>$subservice,
                    "subservice_id"=>$hotelsId,
                    "page"=>$page,
                    "user_id"=>$request->author,
                    "user_ip"=>$request->getClientIp(),
                    "note" =>$note, 
                ]);
                $user_log->save();
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to save contract: ' . $e->getMessage()], 500);
            }
        }
       
        return redirect("/detail-hotel-$hotelsId")->with('success', 'Hotel contract added successfully');
    }else{
        return redirect("/hotels-admin")->with('error','Akses ditolak');
    }
}

// Function Add Room =========================================================================================>
    public function func_add_room(Request $request){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            if($request->hasFile("cover")){
                $file=$request->file("cover");
                $coverName=time().'_'.$file->getClientOriginalName();
                $file->move("storage/hotels/hotels-room/",$coverName);
                
                $status="Draft";
                $service="Room";
                $action="Add Room";

                $hotelroom =new HotelRoom([
                    "hotels_id"=>$request->hotels_id,
                    "cover"=>$coverName,
                    "rooms"=>$request->rooms,
                    "capacity" =>$request->capacity, 
                    "additional_info"=>$request->additional_info,
                    "include"=>$request->include,
                    "status"=>$status,
                ]);
                $hotelroom->save();
            }
            
            // USER LOG
            $action = "Add Rooms";
            $service = "Hotel";
            $subservice = "Room";
            $page = "add-room";
            $note = "Add new rooms at Hotel id : ".$request->hotels_id.", Room id : ".$hotelroom->id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$request->hotels_id,
                "page"=>$page,
                "user_id"=>$request->author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            return redirect("/detail-hotel-$request->hotels_id#rooms")->with('success', 'Rooms added successfully');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

// ADD PRICES ==================================================================================================================================================>
    public function func_add_price(Request $request){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $validated = $request->validate([
                'hotels_id' => 'required',
                'rooms_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'markup' => 'required',
                'contract_rate' => 'required',
                'author' => 'required',
            ]);
            $jr = count($request->rooms_id);
            for ($i=0; $i < $jr; $i++) { 
                $start_date = date('Y-m-d',strtotime($request->start_date[$i]));
                $end_date = date('Y-m-d',strtotime($request->end_date[$i]));
                $markup = $request->markup[$i];
                $kick_back = $request->kick_back[$i];
                $contract_rate = $request->contract_rate[$i];
                $rooms_id = $request->rooms_id[$i];
                $hotels_id = $request->hotels_id;
                $price =new HotelPrice([
                    "hotels_id"=>$hotels_id,
                    "rooms_id"=>$rooms_id,
                    "start_date"=>$start_date,
                    "end_date"=>$end_date,
                    "contract_rate" =>$contract_rate, 
                    "markup" =>$markup, 
                    "kick_back" =>$kick_back, 
                    "author" =>$request->author, 
                ]);
                $price->save();
                // @dd($price);
            }
            return redirect("/detail-hotel-$request->hotels_id#normal-price")->with('success', 'Price added successfully');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
// Function Add Optional Rate =========================================================================================>
    public function func_add_optionalrate(Request $request){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $service = "Hotel";
            $validated = $request->validate([
                'hotels_id' => 'required',
                'type' => 'required',
                'name' => 'required',
                'service_id' => 'required',
                'markup' => 'required',
                'contract_rate' => 'required',
                'description' => 'required',
            ]);
            $optional_rate =new OptionalRate([
                "type"=>$request->type,
                "hotels_id"=>$request->hotels_id,
                "name"=>$request->name,
                "service"=>$service,
                "service_id"=>$request->service_id,
                "markup" =>$request->markup, 
                "contract_rate" =>$request->contract_rate, 
                "description" =>$request->description, 
            ]);
            $optional_rate->save();

            // USER LOG
            $action = "Add Optional Rate";
            $service = "Hotel";
            $subservice = "Optional Rate";
            $page = "detail-hotel#optional-rate";
            $note = "Add optional rate to Hotel id : ".$request->service_id.", Optional rate id: ".$optional_rate->id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$optional_rate->id,
                "page"=>$page,
                "user_id"=>$request->author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            return redirect("/detail-hotel-$request->service_id#optional-rate")->with('success', 'Optional Rate added successfully');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
    
    // Function Add Promo =========================================================================================>
    public function func_add_promo(Request $request){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $validated = $request->validate([
                'hotels_id' => 'required',
                'rooms_id' => 'required',
                'name' => 'required',
                'book_periode_start' => 'required',
                'book_periode_end' => 'required',
                'periode_start' => 'required',
                'minimum_stay' => 'required',
                'contract_rate' => 'required',
                'markup'=> 'required',
                'author'=>'required',
            ]);
            $status = "Draft";
            $book_periode_start = date('Y-m-d', strtotime($request->book_periode_start));
            $book_periode_end = date('Y-m-d', strtotime($request->book_periode_end));
            $periode_start = date('Y-m-d', strtotime($request->periode_start));
            $periode_end = date('Y-m-d', strtotime($request->periode_end));
            $promo =new HotelPromo([
                "status"=>$status,
                "promotion_type"=>$request->promotion_type,
                "quotes"=>$request->quotes,
                "hotels_id"=>$request->hotels_id,
                "rooms_id"=>$request->rooms_id,
                "name"=>$request->name,
                "book_periode_start" =>$book_periode_start, 
                "book_periode_end"=>$book_periode_end,
                "periode_start"=>$periode_start,
                "periode_end"=>$periode_end,
                "minimum_stay"=>$request->minimum_stay,
                "contract_rate"=>$request->contract_rate,
                "markup"=>$request->markup,
                "booking_code"=>$request->booking_code,
                "benefits"=>$request->benefits,
                "include"=>$request->include,
                "additional_info"=>$request->additional_info,
                "author"=>$request->author,
            ]);
            $promo->save();

            // USER LOG
            $action = "Add Promo";
            $service = "Hotel";
            $subservice = "Promo";
            $page = "detail-hotel#promo";
            $note = "Add Promo to Hotel id : ".$request->hotels_id.", Room id : ".$request->rooms_id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$promo->id,
                "page"=>$page,
                "user_id"=>$request->author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            return redirect("/detail-hotel-$request->hotels_id#promo")->with('success', 'Promo added successfully');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
    
    // Function Add Package =========================================================================================>
    public function func_add_package(Request $request){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $status = "Draft";
            $stay_period_start = date('Y-m-d', strtotime($request->stay_period_start));
            $stay_period_end = date('Y-m-d', strtotime($request->stay_period_end));
            $package =new HotelPackage([
                "rooms_id"=>$request->rooms_id,
                "hotels_id"=>$request->hotels_id,
                "name"=>$request->name,
                "duration" =>$request->duration, 
                "stay_period_start" =>$stay_period_start, 
                "stay_period_end" =>$stay_period_end,
                "contract_rate"=>$request->contract_rate,
                "markup"=>$request->markup,
                "booking_code"=>$request->booking_code,
                "benefits"=>$request->benefits,
                "include"=>$request->include,
                "author"=>$request->author,
                "status"=>$status,
                "additional_info"=>$request->additional_info,
            ]);
            //@dd($package);
            $package->save();
            // USER LOG
            $action = "Add Package";
            $service = "Hotel";
            $subservice = "Package";
            $page = "detail-hotel#package";
            $note = "Add Package to Hotel id : ".$request->hotels_id.", Room id : ".$request->rooms_id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$package->id,
                "page"=>$page,
                "user_id"=>$request->author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
                // return redirect("/detail-hotel-$hotels->id");
                // return dd($hotelroom);
            return redirect("/detail-hotel-$request->hotels_id#package")->with('success', 'Package added successfully');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

    
    // Function Update Hotel =============================================================================================================>
    public function func_edit_hotel(Request $request, $id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $hotel=Hotels::findOrFail($id);
            $service="Hotel";
            $action="Update";
            if($request->hasFile("cover")){
                if (File::exists("storage/hotels/hotels-cover/".$hotel->cover)) {
                    File::delete("storage/hotels/hotels-cover/".$hotel->cover);
                }
                $file=$request->file("cover");
                $hotel->cover=time()."_".$file->getClientOriginalName();
                $file->move("storage/hotels/hotels-cover/",$hotel->cover);
                $request['cover']=$hotel->cover;
                
            }

            if($request->hasFile("contract")){
                if (File::exists("storage/hotels/hotels-contract/".$hotel->contract)) {
                    File::delete("storage/hotels/hotels-contract/".$hotel->contract);
                }
                $file=$request->file("contract");
                $hotel->contract=time()."_".$file->getClientOriginalName();
                $file->move("storage/hotels/hotels-contract/",$hotel->contract);
                $request['contract']=$hotel->contract;
            }

            $hotel->update([
                "name"=>$request->name,
                "region"=>$request->region,
                "address"=>$request->address,
                "contact_person"=>$request->contact_person,
                "phone"=>$request->phone,
                "description"=>$request->description,
                "facility"=>$request->facility,
                "additional_info"=>$request->additional_info,
                "status"=>$request->status,
                "web"=>$request->web,
                "contract"=>$hotel->contract,
                "cover" =>$hotel->cover,
                "cancellation_policy"=>$request->cancellation_policy,
                "author_id"=>$request->author,
                "min_stay" =>$request->min_stay,
                "max_stay" =>$request->max_stay,
                "airport_distance" =>$request->airport_distance,
                "airport_duration" =>$request->airport_duration,
            ]);

            // USER LOG
            $action = "Update";
            $service = "Hotel";
            $subservice = "Hotel";
            $page = "detail-hotel";
            $note = "Update hotel : ".$id;
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
            return redirect("/detail-hotel-$hotel->id")->with('success','The Hotel has been updated!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
    
    // Function Edit room =============================================================================================================>
    public function func_edit_room(Request $request, $id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $room=HotelRoom::findOrFail($id);
            $hotel_id=$room->hotels_id;
            $action="Update";
            if($request->hasFile("cover")){
                if (File::exists("storage/hotels/hotels-room/".$room->cover)) {
                    File::delete("storage/hotels/hotels-room/".$room->cover);
                }
                $file=$request->file("cover");
                $room->cover=time()."_".$file->getClientOriginalName();
                $file->move("storage/hotels/hotels-room/",$room->cover);
                $request['cover']=$room->cover;
            }

            $room->update([
                "hotels_id"=>$request->hotels_id,
                "cover"=>$room->cover,
                "rooms"=>$request->rooms,
                "capacity" =>$request->capacity, 
                "include"=>$request->include,
                "status"=>$request->status,
                "additional_info"=>$request->additional_info,
            ]);

            // USER LOG
            $action = "Update";
            $service = "Hotel";
            $subservice = "Room";
            $page = "edit-room";
            $note = "Update room on hotel : ".$request->hotels_id.", Room id : ".$id;
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
            // return dd($room);
            return redirect("/detail-hotel-$hotel_id#rooms")->with('success','The room has been updated!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
    
    
    // Function Edit Price =============================================================================================================>
    public function func_edit_price(Request $request,$id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $price=HotelPrice::findOrFail($id);
            $hotel_id=$request->hotels_id;
            $action="Update Normal Price";
            $service="Hotel";
            $start_date = date('Y-m-d', strtotime($request->start_date));
            $end_date = date('Y-m-d', strtotime($request->end_date));

            $price->update([
                "hotels_id"=>$request->hotels_id,
                "rooms_id"=>$request->rooms_id,
                "start_date"=>$start_date,
                "end_date"=>$end_date,
                "markup"=>$request->markup,
                "kick_back"=>$request->kick_back,
                "contract_rate"=>$request->contract_rate,
            ]);

            // USER LOG
            $action = "Update Normal Price";
            $service = "Hotel";
            $subservice = "Normal Price";
            $page = "detail-hotel#normal-price";
            $note = "Update normal price to Hotel id : ".$request->hotels_id.", Room id : ".$request->rooms_id.", Start date : ".$start_date.", End date : ".$end_date.", Markup : ".$request->markup.", Contract rate : ".$request->contract_rate;
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
            return redirect("/detail-hotel-$hotel_id#prices")->with('success','The Price has been updated!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
// Function Edit Optional Rate =============================================================================================================>
    public function func_edit_optionalrate(Request $request,$id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $optional_rate=OptionalRate::findOrFail($id);
            $service = "Hotel";
            $optional_rate->update([
                "type"=>$request->type,
                "name"=>$request->name,
                "service"=>$service,
                "service_id"=>$request->service_id,
                "description"=>$request->description,
                "markup"=>$request->markup,
                "contract_rate"=>$request->contract_rate,
            ]);

            // USER LOG
            $action = "Update Optional Rate";
            $subservice = "Optional Rate";
            $page = "detail-hotel#optional-rate";
            $note = "Update optional rate to Hotel id : ".$request->service_id;
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
            return redirect("/detail-hotel-$request->service_id#optional-rate")->with('success','The Optional Rate has been updated!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
    // Function Edit Contract =============================================================================================================>
    public function func_edit_hotel_contract(Request $request,$id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $contract=Contract::findOrFail($id);
            $period_start = date('Y-m-d', strtotime($request->period_start));
            $period_end = date('Y-m-d', strtotime($request->period_end));
            if($request->hasFile("file_name")){
                if (File::exists("storage/hotels/hotels-contract/".$contract->file_name)) {
                    File::delete("storage/hotels/hotels-contract/".$contract->file_name);
                }
                $file=$request->file("file_name");
                $contract->file_name = time()."_".$file->getClientOriginalName();
                $file->move("storage/hotels/hotels-contract/",$contract->file_name);
                $file_name = $contract->file_name;
                
            }
            $contract->update([
                "name"=>$request->contract_name,
                "file_name"=>$contract->file_name,
                "period_start"=>$period_start,
                "period_end"=>$period_end,
            ]);

            // USER LOG
            $action = "Update Hotel Contract";
            $service = "Hotel";
            $subservice = "Contract";
            $page = "detail-hotel";
            $note = "Update contract to Hotel id : ".$request->hotels_id;
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
            return redirect("/detail-hotel-$request->hotels_id")->with('success','Contract has been updated!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

// Function Edit Promo =============================================================================================================>
    public function func_edit_promo(Request $request,$id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $promo=HotelPromo::findOrFail($id);
            $hotel_id=$request->hotels_id;
            $book_periode_start = date('Y-m-d', strtotime($request->book_periode_start));
            $book_periode_end = date('Y-m-d', strtotime($request->book_periode_end));
            $periode_start = date('Y-m-d', strtotime($request->periode_start));
            $periode_end = date('Y-m-d', strtotime($request->periode_end));

            $promo->update([
                "hotels_id"=>$request->hotels_id,
                "promotion_type"=>$request->promotion_type,
                "quotes"=>$request->quotes,
                "rooms_id"=>$request->rooms_id,
                "name"=>$request->name,
                "book_periode_start" =>$book_periode_start, 
                "book_periode_end"=>$book_periode_end,
                "periode_start"=>$periode_start,
                "periode_end"=>$periode_end,
                "minimum_stay"=>$request->minimum_stay,
                "contract_rate"=>$request->contract_rate,
                "markup"=>$request->markup,
                "booking_code"=>$request->booking_code,
                "benefits"=>$request->benefits,
                "status"=>$request->status,
                "author"=>$request->author,
                "include"=>$request->include,
                "additional_info"=>$request->additional_info,
            ]);

            // USER LOG
            $action = "Update Promo";
            $service = "Hotel";
            $subservice = "Promo";
            $page = "detail-hotel#promos";
            $note = "Update Promo on Hotel id : ".$request->hotels_id.", Room id : ".$request->rooms_id.", Promo id : ".$id;
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
            // return dd($promo);
            return redirect("/detail-hotel-$hotel_id#promo")->with('success','The Promo has been updated!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

// Function Edit Package =============================================================================================================>
    public function func_edit_package(Request $request, $id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $package=HotelPackage::findOrFail($id);
            $hotel_id=$request->hotels_id;
            $stay_period_start = date('Y-m-d', strtotime($request->stay_period_start));
            $stay_period_end = date('Y-m-d', strtotime($request->stay_period_end));
            // $duration = $inpack->diffInDays($outpack);

            $package->update([
                "rooms_id"=>$request->rooms_id,
                "hotels_id"=>$request->hotels_id,
                "name"=>$request->name,
                "stay_period_start" =>$stay_period_start, 
                "stay_period_end" =>$stay_period_end,
                "contract_rate"=>$request->contract_rate,
                "markup"=>$request->markup,
                "booking_code"=>$request->booking_code,
                "benefits"=>$request->benefits,
                "include"=>$request->include,
                "additional_info"=>$request->additional_info,
                "author"=>$request->author,
                "status"=>$request->status,
            ]);
            // dd($package);
            // USER LOG
            $action = "Update Package";
            $service = "Hotel";
            $subservice = "Package";
            $page = "detail-hotel#package";
            $note = "Update Package on Hotel id : ".$request->hotels_id.", Room id : ".$request->rooms_id.", Package id : ".$id;
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
            // return dd($room);
            return redirect("/detail-hotel-$hotel_id#package")->with('success','The Package has been updated!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

// function Tour Remove =============================================================================================================>
    public function remove_hotel(Request $request,$id)
    {
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $hotel=Hotels::findOrFail($id);
            $status = "Removed";
            $hotel->update([
                "status"=>$status,
            ]);
            // USER LOG
            $action = "Remove Hotel";
            $service = "Hotel";
            $subservice = "Hotel";
            $page = "hotel-admin";
            $note = "Remove Hotel id : ".$id;
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
            return back()->with('success','The Hotel has been successfully deleted!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
// Function Delete Hotel =============================================================================================================>
    public function destroy_hotel($id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $hotels=Hotels::findOrFail($id);
            $service="Hotel";
            $action="Delete";
            $author= Auth::user()->id;

            if (File::exists("storage/hotels/hotels-cover/".$hotels->cover)) {
                File::delete("storage/hotels/hotels-cover/".$hotels->cover);
            }
            $images=HotelsImages::where("hotels_id",$hotels->id)->get();
            foreach($images as $image){
                if (File::exists("storage/hotels/hotels-galery/".$image->image)) {
                    File::delete("storage/hotels/hotels-galery/".$image->image);
                }
            }
            $hotels->delete();
            $log= new LogData ([
                'service' =>$service,
                'service_name'=>$hotels->name,
                'action'=>$action,
                'user'=>$author,
            ]);
            $log->save();
            return back();
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
    
// Function Delete Room =============================================================================================================>
    public function destroy_room(Request $request, $id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $room=HotelRoom::findOrFail($id);
            $hotel= Hotels::where('id','=',$room->hotels_id)->first();
            $service_name=$room->rooms;
            $service=$hotel->name;
            $action="Delete Room";
            $author= Auth::user()->id;

            // USER LOG
            $action = "Remove";
            $service = "Hotel";
            $subservice = "Room";
            $page = "detail-hotel#rooms";
            $note = "Remove room on hotel : ".$request->hotels_id.", Room id : ".$id;
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
            $room->delete();
            return redirect("/detail-hotel-$request->hotels_id#rooms")->with('success','The Room has been successfully deleted!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

// Function Delete Price =============================================================================================================>
    public function destroy_price(Request $request,$id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $price=HotelPrice::findOrFail($id);
            $hotel= Hotels::where('id','=',$price->hotels_id)->first();
            $room=HotelRoom::where('id','=',$price->rooms_id)->first();
            $author= Auth::user()->id;
            // USER LOG
            $action = "Remove";
            $service = "Hotel";
            $subservice = "Normal Price";
            $page = "detail-hotel#normal-price";
            $note = "Remove normal price Hotel id : ".$hotel->id.", Room id : ".$room->id.", Price id : ".$id;
            $user_log =new UserLog([
                "action"=>$action,
                "service"=>$service,
                "subservice"=>$subservice,
                "subservice_id"=>$price->id,
                "page"=>$page,
                "user_id"=>$author,
                "user_ip"=>$request->getClientIp(),
                "note" =>$note, 
            ]);
            $user_log->save();
            $price->delete();
            return back()->with('success','The Price has been successfully deleted!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
// Function Delete Optional Rate =============================================================================================================>
    public function delete_optionalrate(Request $request,$id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $optional_rate=OptionalRate::findOrFail($id);
            $author= Auth::user()->id;
            // USER LOG
            $action = "Remove";
            $service = "Hotel";
            $subservice = "Optional Rate";
            $page = "detail-hotel#optional-rate";
            $note = "Remove optional rate on Hotel id : ".$request->hotels_id.", Optional rate id : ".$id;
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
            $optional_rate->delete();
            return back()->with('success','The Optional Rate has been successfully deleted!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

    // Function Delete Promo =============================================================================================================>
    public function destroy_promo(Request $request,$id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $promo=HotelPromo::findOrFail($id);
            // USER LOG
            $action = "Remove";
            $service = "Hotel";
            $subservice = "Promo";
            $page = "detail-hotel#promo";
            $note = "Remove Promo on hotel : ".$request->hotels_id.", Promo id : ".$id;
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
            $promo->delete();
            return back()->with('success','The Promo has been successfully deleted!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

    // Function Delete Package =============================================================================================================>
    public function destroy_package(Request $request, $id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $package=HotelPackage::findOrFail($id);
            // USER LOG
            $action = "Remove";
            $service = "Hotel";
            $subservice = "Package";
            $page = "detail-hotel#package";
            $note = "Remove Package on hotel : ".$request->hotels_id.", Package id : ".$id;
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
            $package->delete();
            return back()->with('success','The Package has been successfully deleted!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

// Function Delete Hotel Galery  =============================================================================================================>
    public function delete_image_hotel($id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $images=HotelsImages::findOrFail($id);
            if (File::exists("storage/hotels/hotels-galery/".$images->image)) 
            {
            File::delete("storage/hotels/hotels-galery/".$images->image);
            }
            HotelsImages::find($id)->delete();
            return back();
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

// Function Delete Hotel Cover =============================================================================================================>
    public function delete_cover_hotel($id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $cover=Hotels::findOrFail($id)->cover;
            if (File::exists("storage/hotels/hotels-cover/".$cover)) 
            {
                File::delete("storage/hotels/hotels-cover/".$cover);
            }
            return back();
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }
// Function Delete Hotel Contract =============================================================================================================>
    public function delete_contract(Request $request, $id){
        if (Gate::allows('posDev') or Gate::allows('posAuthor')) {
            $contract=Contract::findOrFail($id);
            if (File::exists("storage/hotels/hotels-contract/".$request->file_name)) 
            {
                File::delete("storage/hotels/hotels-contract/".$request->file_name);
            }
            $action = "Remove";
            $service = "Hotel";
            $subservice = "Contract";
            $page = "detail-hotel";
            $note = "Remove Contract on hotel : ".$request->hotels_id;
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
            $contract->delete();
            return redirect("/detail-hotel-$request->hotels_id")->with('success','The Contract has been successfully deleted!');
        }else{
            return redirect("/hotels-admin")->with('error','Akses ditolak');
        }
    }

// Modal Hotel  =============================================================================================================>
    public function modal($id){
        $modal=Hotels::findOrFail($id);
        return view('form.hotelgaleryedit')->with('hotels',$hotels);
        }
}