<?php
use Carbon\Carbon;
use App\Models\User;
use App\Models\OrderWedding;
use Illuminate\Http\Request;
use App\Models\WeddingPlanner;
use App\Models\WeddingInvitations;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentConfirmation;
use App\Http\Middleware\ApproveUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ToursController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\HotelsController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\FlightsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ExtraBedController;
use App\Http\Controllers\ImagesupController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\UsdRatesController;
use App\Http\Controllers\WeddingsController;
use App\Http\Controllers\AttentionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\HotelPromoController;
use App\Http\Controllers\ManualBookController;
use App\Http\Controllers\ToursAdminController;
use App\Http\Controllers\TransportsController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BookingCodeController;
use App\Http\Controllers\EmailBlastsController;
use App\Http\Controllers\HotelsAdminController;
use App\Http\Controllers\OrdersAdminController;
use App\Http\Controllers\RactivitiesController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\WeddingMenuController;
use App\Http\Controllers\InvoiceAdminController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\OrderWeddingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContractAgentController;
use App\Http\Controllers\WeddingDinnerController;
use App\Http\Controllers\WeddingVenuesController;
use App\Http\Controllers\WeddingPlannerController;
use App\Http\Controllers\ActivitiesAdminController;
use App\Http\Controllers\OrderHotelPromoController;
use App\Http\Controllers\TransportsAdminController;
use App\Http\Controllers\TermAndConditionController;
use App\Http\Controllers\DownloadDataHotelController;
use App\Http\Controllers\WeddingInvitationsController;
use App\Http\Controllers\WeddingLunchVenuesController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\PaymentConfirmationController;
use App\Http\Controllers\WeddingDinnerVenuesController;
use App\Http\Controllers\WeddingReceptionVenuesController;

Route::get('lang/{locale}',[LocalizationController::class,'changeLanguage'])->middleware('auth');
Route::get('/', function () {return view('welcome');});
Route::get('home', function () {return redirect('/profile');});

Route::get('change-password', [ForgotPasswordController::class, 'forgetPassword'])->name('change.password.get');
Route::get('forget-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('forget.password.get');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('f-forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('f-forget.password.post');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
Route::get('profile', [ProfileController::class,'profile'])->name('profile');
Route::get('profile-{email}',[ProfileController::class,'users']);
Route::put('/fupdate-profile/{id}',[UsersController::class,'func_update_profile']);
Route::put('/fupdate-profileimg/{id}',[UsersController::class,'func_update_profileimg']);
Route::put('/fupdate-password',[UsersController::class,'updatePassword'])->name('update-password');
Route::get('/terms-and-conditions', [TermAndConditionController::class, 'terms_and_conditions']);
Route::get('/privacy-policy', [TermAndConditionController::class, 'privacy_policy']);

Route::middleware('log.route.usage')->group(function () {
    Auth::routes(['verify' => true]);
    Route::middleware(['profile.complete'])->group(function () {
        Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard.index')->middleware('auth');
        Route::middleware(['approve'])->group(function () {
        // ADMIN PANEL =================================================================================================================> (Admin)
            Route::get('/admin-panel',[AdminPanelController::class,'index'])->name('admin-panel')->middleware(['auth','checkPosition:developer']);
            Route::post('/fadd-service',[AdminPanelController::class,'func_add_service'])->name('f-add-service')->middleware(['auth','checkPosition:developer,reservation,author']);
            Route::put('/fdisable-service/{id}',[AdminPanelController::class,'func_disable_service'])->name('f-disable-service')->middleware(['auth','checkPosition:developer,reservation,author']);
            Route::put('/fedit-service/{id}',[AdminPanelController::class,'func_edit_service'])->name('f-edit-service')->middleware(['auth','checkPosition:developer,reservation,author']);
            Route::put('/fenable-service/{id}',[AdminPanelController::class,'func_enable_service'])->name('f-enable-service')->middleware(['auth','checkPosition:developer,reservation,author']);
            Route::delete('/fremove-service/{id}',[AdminPanelController::class,'func_remove_service'])->name('f-remove-service')->middleware(['auth','checkPosition:developer,reservation,author']);

        // Tour User =================================================================================================================> (Agent)
            // Route::get('/tours',[ToursController::class,'index'])->middleware('auth');
            // Route::get('/tour-{code}-{bcode}',[ToursController::class,'view_tour_detail_bookingcode'])->middleware('auth')->name('tour-bookingcode');
            // Route::get('/tour-{code}',[ToursController::class,'view_tour_detail'])->middleware('auth')->name('tour-detail');
            // Route::post('/search-tours',[ToursController::class,'search_tour'])->middleware('auth');
            // Route::post('/tour-detail',[ToursController::class,'tour_check_code'])->middleware('auth');
            // Route::post('/tour-add-bookingcode',[ToursController::class,'search_tour'])->middleware('auth');

        // Tour Admin =================================================================================================================> (Admin)
            // Route::get('/tours-admin',[ToursAdminController::class,'index'])->middleware(['auth','adminType']);
            // Route::get('/detail-tour-{id}',[ToursAdminController::class,'view_detail_tour'])->middleware(['auth','adminType']);
            // Route::get('/edit-tour-{id}',[ToursAdminController::class,'view_edit_tour'])->middleware(['auth','adminType']);
            // Route::delete('/remove-tour/{id}',[ToursAdminController::class,'remove_tour'])->middleware(['auth','adminType']);
            // Route::get('/add-tour',[ToursAdminController::class,'view_add_tour'])->middleware(['auth','adminType']);
            // Route::post('/fadd-tour',[ToursAdminController::class,'func_add_tour'])->name('fadd-tour')->middleware(['auth','adminType']);
            // Route::post('/fadd-tour-price-{id}',[ToursAdminController::class,'func_add_tour_price'])->name('fadd-tour-price')->middleware(['auth','adminType']);
            // Route::put('/fedit-tour-price-{id}',[ToursAdminController::class,'func_update_tour_price'])->middleware(['auth','adminType']);
            // Route::delete('/fdelete-tour-price-{id}',[ToursAdminController::class,'func_delete_tour_price'])->middleware(['auth','adminType']);
            // Route::put('/fupdate-tour/{id}',[ToursAdminController::class,'func_update_tour'])->middleware(['auth','adminType']);

        // CURRENCY =================================================================================================================> (Admin)
            Route::get('/currency',[UsdRatesController::class,'index'])->name('currency')->middleware(['auth','adminType']);
            Route::put('/update-usdrates/{id}',[UsdRatesController::class,'func_update_usdrates'])->name('f-update-usd-rates')->middleware(['auth','adminType']);
            Route::put('/update-cnyrates/{id}',[UsdRatesController::class,'func_update_cnyrates'])->name('f-update-cny-rates')->middleware(['auth','adminType']);
            Route::put('/update-twdrates/{id}',[UsdRatesController::class,'func_update_twdrates'])->name('f-update-twd-rates')->middleware(['auth','adminType']);
            Route::put('/update-tax/{id}',[UsdRatesController::class,'func_update_tax'])->name('f-update-tax')->middleware(['auth','adminType','developerPos']);


        // Bookingcode =================================================================================================================> (Agent)
            Route::post('/bookingcode-check',[BookingCodeController::class,'storeBookingCode'])->name('bookingcode.check')->middleware('auth');
            // Route::post('/bookingcode/store', [BookingCodeController::class, 'storeBookingCode'])->name('bookingcode.store');
            Route::post('/remove-bookingcode', function () {
                Session::forget('bookingcode');
                return redirect()->back()->with('success', 'Booking code removed successfully.');
            })->name('bookingcode.remove');



        


        // Hotel User =================================================================================================================> (Agent)
            Route::get('/hotels',[HotelsController::class,'index'])->name('hotels.index')->middleware('auth');
            Route::post('/search-hotels',[HotelsController::class,'search_hotel'])->name('hotels.search')->middleware('auth');
            Route::get('/hotel-{code}',[HotelsController::class,'hoteldetail'])->name('hotels.detail')->middleware('auth');
            Route::post('/hotel-price-{code}',[HotelsController::class,'hotel_price'])->name('hotels.prices')->middleware('auth');

            Route::get('/hotel-{code}-{bcode}',[HotelsController::class,'hoteldetail_bookingcode'])->middleware('auth')->name('hotels.bookingcode');
            Route::post('/fcheck-code',[HotelsController::class,'fcheck_code'])->name('fhotel-check-code')->middleware('auth');
            Route::post('/hotel-price-{code}-{bcode}',[HotelsController::class,'hotel_price_bookingcode'])->middleware('auth');
            Route::post('/fadd-optional-rate-order',[HotelsController::class,'func_add_optional_rate_order'])->middleware('auth');

        // Hotel Promo =================================================================================================================> (Agent)
            Route::get('/hotel-promo/{id}/{checkin}/{checkout}',[HotelPromoController::class,'index'])->middleware('auth');
            Route::post('/hotel-promo',[HotelPromoController::class,'hotelpromo'])->middleware('auth');

        // Hotel Admin =================================================================================================================> (Admin)
            Route::get('/hotels-admin',[HotelsAdminController::class,'index'])->name('admin.hotels.index')->middleware(['auth','adminType']);
            Route::get('/detail-hotel-{id}',[HotelsAdminController::class,'view_detail_hotel'])->name('admin.hotel.detail')->middleware(['auth','adminType']);
            Route::get('/edit-hotel-{id}',[HotelsAdminController::class,'view_edit_hotel'])->name('admin.hotel.update')->middleware(['auth','adminType']);
            Route::get('/add-hotel-price-{id}',[HotelsAdminController::class,'view_add_hotel_price'])->name('admin.hotel.add.price')->middleware(['auth','adminType']);
            Route::get('/add-hotel',[HotelsAdminController::class,'view_add_hotel'])->name('admin.hotel.add')->middleware(['auth','adminType']);
            Route::post('/fadd-hotel',[HotelsAdminController::class,'func_add_hotel'])->name('func.admin.hotel.add')->middleware(['auth','adminType']);
            Route::post('/fadd-hotel-contract',[HotelsAdminController::class,'func_add_contract'])->name('func.admin.hotel.add.contract')->middleware(['auth','adminType']);
            Route::post('/fadd-optionalrate',[HotelsAdminController::class,'func_add_optionalrate'])->name('func.admin.hotel.add.optionalrate')->middleware(['auth','adminType']);
            Route::put('/fupdate-hotel/{id}',[HotelsAdminController::class,'func_edit_hotel'])->name('func.admin.hotel.update')->middleware(['auth','checkPosition:developer,reservation,author']);
            Route::put('/fupdate-hotel-contract/{id}',[HotelsAdminController::class,'func_edit_hotel_contract'])->name('func.admin.hotel.update.contract')->middleware(['auth','adminType']);
            Route::put('/fupdate-optionalrate/{id}',[HotelsAdminController::class,'func_edit_optionalrate'])->name('func.admin.hotel.update.optionalrate')->middleware(['auth','adminType']);
            Route::delete('/remove-hotel/{id}',[HotelsAdminController::class,'remove_hotel'])->name('func.admin.hotel.remove')->middleware(['auth','adminType']);
            Route::delete('/fdelete-contract/{id}',[HotelsAdminController::class,'delete_contract'])->name('func.admin.hotel.remove.contract')->middleware(['auth','adminType']);
            Route::delete('/fdelete-hotel-cover/{id}',[HotelsAdminController::class,'delete_cover_hotel'])->name('func.admin.hotel.remove.cover')->middleware(['auth','adminType']);
            Route::delete('/fdelete-optionalrate/{id}',[HotelsAdminController::class,'delete_optionalrate'])->name('func.admin.hotel.remove.optionalrate')->middleware(['auth','adminType']);

        // Extra Bed ==================================================================================================================> (Agent)
            Route::post('/fadd-e-b',[ExtraBedController::class,'func_add_extra_bed'])->middleware('auth');
            Route::put('/fedit-e-b/{id}',[ExtraBedController::class,'fedit_extra_bed'])->middleware('auth');
            Route::delete('/fdelete-e-b/{id}',[ExtraBedController::class,'fdelete_extra_bed'])->middleware('auth');

        // Room Hotel =================================================================================================================> (Admin)
            Route::get('/add-room-{id}',[HotelsAdminController::class,'view_add_room'])->middleware(['auth','adminType']);
            Route::get('/edit-room-{id}',[HotelsAdminController::class,'view_edit_room'])->middleware(['auth','adminType']);
            Route::post('/fadd-room',[HotelsAdminController::class,'func_add_room'])->middleware(['auth','adminType']);
            Route::put('/fedit-room-{id}',[HotelsAdminController::class,'func_edit_room'])->middleware(['auth','adminType']);
            Route::delete('/delete-room/{id}',[HotelsAdminController::class,'destroy_room'])->middleware(['auth','adminType']);

        // Hotel Price =================================================================================================================> (Admin)
            Route::post('/fadd-price',[HotelsAdminController::class,'func_add_price'])->middleware(['auth','adminType']);
            Route::put('/fedit-price-{id}',[HotelsAdminController::class,'func_edit_price'])->middleware(['auth','adminType']);
            Route::delete('/delete-price/{id}',[HotelsAdminController::class,'destroy_price'])->middleware(['auth','adminType']);

        // Hotel Promo =================================================================================================================> (Admin)
            Route::post('/fadd-promo',[HotelsAdminController::class,'func_add_promo'])->middleware(['auth','adminType']);
            Route::put('/fedit-promo-{id}',[HotelsAdminController::class,'func_edit_promo'])->middleware(['auth','adminType']);
            Route::delete('/delete-promo/{id}',[HotelsAdminController::Class, 'destroy_promo'])->middleware(['auth','adminType']);

        // Hotel Package =================================================================================================================> (Admin)
            Route::post('/fadd-package',[HotelsAdminController::class,'func_add_package'])->middleware(['auth','adminType']);
            Route::put('/fedit-package-{id}',[HotelsAdminController::class,'func_edit_package'])->middleware(['auth','adminType']);
            Route::delete('/delete-package/{id}',[HotelsAdminController::Class, 'destroy_package'])->middleware(['auth','adminType']);

        // Activity User =================================================================================================================> (Agent)
            Route::get('/activities',[ActivitiesController::class,'index'])->middleware('auth');
            Route::get('/activity-{code}-{bcode}',[ActivitiesController::class,'activitydetail_bookingcode'])->middleware('auth');
            Route::get('/activity-{code}',[ActivitiesController::class,'activitydetail'])->middleware('auth');
            Route::post('/activity-detail',[ActivitiesController::class,'activity_check_code'])->middleware('auth');
            Route::post('/search-activities',[ActivitiesController::class,'search_activities'])->middleware('auth');

        // Activity Admin =================================================================================================================> (Admin)
            Route::get('/activities-admin',[ActivitiesAdminController::class,'index'])->middleware(['auth','adminType']);
            Route::get('/detail-activity-{id}',[ActivitiesAdminController::class,'view_detail_activity'])->middleware(['auth','adminType']);
            Route::get('/add-activity',[ActivitiesAdminController::class,'view_add_activity'])->middleware(['auth','adminType']);
            Route::get('/edit-activity-{id}',[ActivitiesAdminController::class,'view_edit_activity'])->middleware(['auth','adminType']);
            Route::get('/edit-galery-activity-{id}',[ActivitiesController::class,'view_edit_galery_activity'])->middleware(['auth','adminType']);
            Route::post('/fadd-activity',[ActivitiesAdminController::class,'func_add_activity'])->middleware(['auth','adminType']);
            Route::put('/fupdate-activity/{id}',[ActivitiesAdminController::class,'func_update_activity'])->middleware(['auth','adminType']);
            Route::delete('/remove-activity/{id}',[ActivitiesController::class,'destroy_activity'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-activity-cover/{id}',[ActivitiesController::class,'delete_cover_activityl'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-activity-img/{id}',[ActivitiesController::class,'delete_image_activity'])->middleware(['auth','adminType']);

        // Transport User =================================================================================================================> (Agent)
            Route::get('/transports',[transportsController::class,'index'])->middleware('auth');
            Route::get('/transport-{code}-{bcode}',[transportsController::class,'transport_detail_bookingcode'])->middleware('auth');
            Route::get('/transport-{code}',[transportsController::class,'transport_detail'])->name('transport.detail')->middleware('auth');
            Route::post('/transport-detail',[transportsController::class,'transport_check_code'])->middleware('auth');
            Route::post('/search-transports',[transportsController::class,'search_transports'])->middleware('auth');
            Route::post('/order-transport-{id}',[transportsController::class,'order_transport'])->name('order-transport')->middleware('auth');

        // Transport Admin =================================================================================================================> (Admin)
            Route::get('/transports-admin',[transportsAdminController::class,'index'])->middleware(['auth','adminType']);
            Route::get('/add-transport',[transportsAdminController::class,'view_add_transport'])->middleware(['auth','adminType']);
            Route::get('/detail-transport-{id}',[transportsAdminController::class,'view_detail_transport'])->middleware(['auth','adminType']);
            Route::get('/edit-transport-{id}',[transportsAdminController::class,'view_edit_transport'])->middleware(['auth','adminType']);
            Route::get('/edit-galery-transport-{id}',[transportsController::class,'view_edit_galery_transport'])->middleware(['auth','adminType']);
            Route::get('/users',[UsersController::class,'index'])->middleware(['auth','adminType']);
            Route::get('/user-detail-{id}',[UsersController::class,'userdetail'])->middleware(['auth','adminType']);
            Route::post('/fadd-transport',[transportsAdminController::class,'func_add_transport'])->middleware(['auth','adminType']);
            Route::post('/fadd-transport-price',[transportsAdminController::class,'func_add_transport_price'])->middleware(['auth','adminType']);
            Route::put('/fupdate-transport/{id}',[transportsAdminController::class,'func_update_transport'])->middleware(['auth','adminType']);
            Route::put('/fupdate-transport-price/{id}',[transportsAdminController::class,'func_update_transport_price'])->middleware(['auth','adminType']);
            Route::put('/delete-transport/{id}',[transportsAdminController::class,'remove_transport'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-transport-price/{id}',[transportsAdminController::class,'remove_transport_price'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-transport-cover/{id}',[transportsController::class,'delete_cover_transport'])->middleware(['auth','adminType']);

        // PARTNER =================================================================================================================> (Admin)
            Route::get('/partners',[PartnersController::class,'index'])->middleware(['auth','adminType']);
            Route::get('/detail-partner-{id}',[PartnersController::class,'view_partner_detail'])->middleware(['auth','adminType']);
            Route::get('/partner-add-activity-{id}',[PartnersController::class,'view_partner_add_activity'])->middleware(['auth','adminType']);
            Route::get('/partner-add-tour-{id}',[PartnersController::class,'view_partner_add_tour'])->middleware(['auth','adminType']);
            Route::post('/fadd-partner',[PartnersController::class,'func_add_partner'])->middleware(['auth','adminType']);
            Route::post('/fpartner-add-activity',[PartnersController::class,'func_partner_add_activity'])->middleware(['auth','adminType']);
            Route::post('/fpartner-add-tour',[PartnersController::class,'func_partner_add_tour'])->middleware(['auth','adminType']);
            Route::put('/fupdate-partner/{id}',[PartnersController::class,'func_update_partner'])->middleware(['auth','adminType']);
            Route::put('/fremove-partner/{id}',[PartnersController::class,'func_remove_partner'])->middleware(['auth','adminType']);

        // WEDDINGS (USER) =================================================================================================================> (Agent)
            Route::get('/donwload-file', [WeddingsController::class,'download_pdf'])->middleware('auth');
            Route::get('/weddings',[WeddingsController::class,'user_index'])->middleware('auth');
            Route::get('/wedding-hotel-{code}',[WeddingsController::class,'view_wedding_hotel_detail'])->name('wedding.detail')->middleware('auth');
            Route::post('/wedding-search',[WeddingsController::class,'wedding_search'])->middleware('auth');
            Route::put('/fadd-package-to-wedding-planner-{id}',[WeddingsController::class,'func_add_package_to_wedding_planner'])->middleware('auth');
            Route::put('/fupdate-cancellation-policy/{id}',[WeddingsController::class,'func_update_cancellation_policy'])->middleware('auth');
            
        //ORDER WEDDING (USER)
            Route::get('/order-wedding',[OrderWeddingController::class,'view_order_wedding'])->middleware('auth');
            Route::put('/fsubmit-order-wedding/{id}',[OrderWeddingController::class,'func_submit_order_wedding'])->middleware('auth');
            Route::get('/detail-order-wedding-{orderno}',[OrderWeddingController::class,'detail_order_wedding'])->middleware('auth');
            Route::get('/edit-order-wedding-{orderno}',[OrderWeddingController::class,'edit_order_wedding'])->middleware('auth');
            Route::post('/forder-wedding-venue-{id}',[OrderWeddingController::class,'func_create_order_wedding_venue'])->middleware('auth');
            Route::put('/fupdate-wedding-ceremonial-venue/{id}',[OrderWeddingController::class,'func_update_wedding_ceremonial_venue'])->middleware('auth');

            Route::post('/fupdate-wedding-order-ceremony-venue/{id}',[OrderWeddingController::class,'func_update_wedding_order_ceremony_venue'])->middleware('auth');
            Route::put('/fdelete-ceremony-venue/{id}',[OrderWeddingController::class,'func_delete_ceremony_venue'])->middleware('auth');
            Route::put('/fupdate-wedding-order-bride/{id}',[OrderWeddingController::class,'func_update_wedding_order_brides'])->middleware('auth');
            Route::put('/fupdate-wedding-order-wedding/{id}',[OrderWeddingController::class,'func_update_wedding_order_wedding'])->middleware('auth');
            
            Route::put('/fadd-wedding-order-additional-service/{id}',[OrderWeddingController::class,'func_add_wedding_order_addser_decoration'])->middleware('auth');
            
            Route::post('/fadd-order-wedding-transport/{id}',[OrderWeddingController::class,'func_add_wedding_order_transport'])->middleware('auth');
            Route::put('/fuser-update-order-wedding-transport/{id}',[OrderWeddingController::class,'func_user_update_order_wedding_transport'])->middleware('auth');
            Route::delete('/fremove-order-wedding-transport/{id}',[OrderWeddingController::class,'func_remove_transport_from_order_wedding'])->middleware('auth');



            Route::delete('/fremove-order-wedding-additional-charge/{id}',[OrderWeddingController::class,'func_remove_request_service_from_order_wedding'])->middleware('auth');

            Route::post('/upload-pdf-{id}', [OrderWeddingController::class, 'store_invoice_pdf'])->name('upload.pdf.store');


            Route::put('/fadd-decoration-to-ceremony-venue/{id}',[OrderWeddingController::class,'func_add_decoration_to_ceremony_venue'])->middleware('auth');
            Route::put('/fupdate-decoration-ceremony-venue/{id}',[OrderWeddingController::class,'func_update_decoration_ceremony_venue'])->middleware('auth');
            Route::put('/fdelete-decoration-ceremony-venue/{id}',[OrderWeddingController::class,'func_delete_decoration_ceremony_venue'])->middleware('auth');
            
            Route::put('/fupdate-decoration-reception-venue/{id}',[OrderWeddingController::class,'func_update_decoration_reception_venue'])->middleware('auth');
            Route::put('/fdelete-decoration-reception-venue/{id}',[OrderWeddingController::class,'func_delete_decoration_reception_venue'])->middleware('auth');

            Route::put('/fadd-order-wedding-remark/{id}',[OrderWeddingController::class,'func_add_order_wedding_remark'])->middleware('auth');
            Route::put('/fdelete-order-wedding-remark/{id}',[OrderWeddingController::class,'func_delete_order_wedding_remark'])->middleware('auth');

            Route::put('/fadd-order-wedding-accommodation/{id}',[OrderWeddingController::class,'func_add_order_wedding_accommodation'])->middleware('auth');
            Route::put('/fupdate-order-wedding-accommodation/{id}',[OrderWeddingController::class,'func_update_order_wedding_accommodation'])->middleware('auth');
            Route::delete('/fdelete-order-wedding-accommodation/{id}',[OrderWeddingController::class,'func_delete_order_wedding_accommodation'])->middleware('auth');

            Route::put('/fupdate-reception-venue/{id}',[OrderWeddingController::class,'func_update_reception_venue'])->middleware('auth');
            Route::put('/fdelete-reception-venue/{id}',[OrderWeddingController::class,'func_delete_reception_venue'])->middleware('auth');

            Route::put('/fadd-additional-service-to-order-wedding/{id}',[OrderWeddingController::class,'func_additional_service_to_order_wedding'])->middleware('auth');
            Route::put('/fadd-order-wedding-brides-flight/{id}',[OrderWeddingController::class,'func_order_wedding_bride_flight'])->middleware('auth');
            Route::put('/fadd-order-wedding-additional-service/{id}',[OrderWeddingController::class,'func_add_order_wedding_additional_service'])->middleware('auth');

            Route::post('/fadd-order-reception-venue/{id}',[OrderWeddingController::class,'func_add_order_reception_venue'])->middleware('auth');
            Route::post('/fadd-order-wedding-package/{id}',[OrderWeddingController::class,'func_add_order_wedding_package'])->middleware('auth');
            Route::post('/fadd-order-wedding-flight/{id}',[OrderWeddingController::class,'func_add_order_wedding_flight'])->middleware('auth');
            Route::post('/fupdate-order-wedding-flight/{id}',[OrderWeddingController::class,'func_update_order_wedding_flight'])->middleware('auth');
            Route::delete('/fdelete-order-wedding-flight/{id}',[OrderWeddingController::class,'func_delete_order_wedding_flight'])->middleware('auth');

            Route::put('/fadd-invitation-to-order-wedding-{id}',[OrderWeddingController::class,'func_add_invitation_to_order_wedding'])->middleware(['auth']);
            Route::post('/fupdate-invitation-order-wedding/{id}',[OrderWeddingController::class,'func_update_invitation_to_order_wedding'])->middleware(['auth']);
            Route::delete('/func-delete-invitation-order-wedding/{id}',[OrderWeddingController::class,'func_delete_invitation_to_order_wedding'])->middleware('auth');
            
            
            Route::delete('/delete-wedding-order/{id}',[OrderWeddingController::class,'func_delete_order_wedding'])->middleware('auth');

        //WEDDING PLANNER (USER)
            Route::get('/edit-wedding-planner-{id}',[WeddingPlannerController::class,'view_edit_wedding_planner'])->middleware('auth');
            Route::get('/wedding-planner',[WeddingPlannerController::class,'index'])->middleware('auth');
            Route::post('/fadd-wedding-planner',[WeddingPlannerController::class,'func_add_wedding_planner'])->middleware('auth');
            Route::put('/fadd-wedding-planner-brides-flight/{id}',[WeddingPlannerController::class,'func_add_wedding_planner_brides_flight'])->middleware('auth');
            Route::put('/fupdate-wedding-planner-invitations/{id}',[WeddingPlannerController::class,'func_update_wedding_planner_invitations'])->middleware('auth');
            
            Route::put('/fadd-wedding-planner-invitations-flight/{id}',[FlightsController::class,'func_add_wedding_planner_invitations_flight'])->middleware('auth');
            Route::put('/fupdate-wedding-planner-invitations-flight/{id}',[FlightsController::class,'func_update_wedding_planner_invitations_flight'])->middleware('auth');
            Route::delete('/fdelete-wedding-planner-invitations-flight/{id}',[FlightsController::class,'func_delete_wedding_planner_invitations_flight'])->middleware('auth');

            Route::put('/fadd-ceremonial-venue-to-wedding-planner/{id}',[WeddingPlannerController::class,'func_add_ceremony_venue_to_wedding_planner'])->middleware('auth');
            Route::put('/fadd-reception-venue-to-wedding-planner/{id}',[WeddingPlannerController::class,'func_add_reception_venue_to_wedding_planner'])->middleware('auth');
            
            Route::post('/fadd-wedding-planner-transport/{id}',[WeddingPlannerController::class,'func_add_transport_to_wedding_planner'])->middleware('auth');
            Route::delete('/fremove-wedding-planner-transport/{id}',[WeddingPlannerController::class,'func_remove_transport_from_wedding_planner'])->middleware('auth');
            Route::put('/fupdate-wedding-planner-transport/{id}',[WeddingPlannerController::class,'func_update_transport_from_wedding_planner'])->middleware('auth');

            Route::put('/fadd-wedding-planner-invitations/{id}',[WeddingPlannerController::class,'func_add_wedding_planner_invitations'])->middleware('auth');
            Route::put('/fupdate-wedding-planner-bride/{id}',[WeddingPlannerController::class,'func_update_wedding_planner_bride'])->middleware('auth');
            Route::put('/fupdate-wedding-planner-brides-flight/{id}',[WeddingPlannerController::class,'func_update_wedding_planner_bride_flight'])->middleware('auth');
            Route::put('/fupdate-wedding-planner-wedding/{id}',[WeddingPlannerController::class,'func_update_wedding_planner_wedding'])->middleware('auth');
            Route::put('/fupdate-wedding-planner-ceremonial-venue/{id}',[WeddingPlannerController::class,'func_update_wedding_planner_ceremonial_venue'])->middleware('auth');
            Route::put('/fupdate-wedding-planner-reception-venue/{id}',[WeddingPlannerController::class,'func_update_wedding_planner_reception_venue'])->middleware('auth');
            Route::put('/fdelete-wedding-planner-ceremonial-venue/{id}',[WeddingPlannerController::class,'func_delete_wedding_planner_ceremonial_venue'])->middleware('auth');
            Route::put('/fdelete-wedding-planner-reception-venue/{id}',[WeddingPlannerController::class,'func_delete_wedding_planner_reception_venue'])->middleware('auth');
            Route::delete('/fdelete-wedding-planner/{id}',[WeddingPlannerController::class,'func_destroy_wedding_planner'])->middleware('auth');
            Route::delete('/fdelete-wedding-planner-invitation/{id}',[WeddingPlannerController::class,'func_destroy_wedding_planner_invitation'])->middleware('auth');
            Route::put('/fsubmit-wedding-planner/{id}',[WeddingPlannerController::class,'func_submit_wedding_planner'])->middleware('auth');
            
        // WEDDING ACCOMMODATION =====================================================================================================================> (USER)
            Route::get('wedding-accommodation-update-{id}',[WeddingPlannerController::class,'view_update_wedding_accommodation'])->middleware('auth');
            Route::post('/fadd-wedding-accommodation/{id}',[WeddingPlannerController::class,'func_add_wedding_accommodations'])->middleware('auth');
            Route::post('/fupdate-wedding-accommodation/{id}',[WeddingPlannerController::class,'func_update_wedding_accommodation'])->middleware('auth');
            Route::post('/fadd-wedding-planner-accommodation/{id}',[WeddingPlannerController::class,'func_add_wedding_planner_accommodation'])->middleware('auth');

            Route::put('/fupdate-wedding-planner-bride-accommodation/{id}',[WeddingPlannerController::class,'func_update_wedding_planner_bride_accommodation'])->middleware('auth');
            Route::put('/fupdate-wedding-planner-invitations-accommodation/{id}',[WeddingPlannerController::class,'func_update_wedding_planner_invitations_accommodation'])->middleware('auth');
            Route::delete('/fdelete-wedding-accommodation/{id}',[WeddingPlannerController::class,'func_destroy_wedding_accommodation'])->middleware('auth');
            Route::delete('/fdelete-wedding-planner-bride-accommodation/{id}',[WeddingPlannerController::class,'func_destroy_wedding_planner_bride_accommodation'])->middleware('auth');


        // WEDDING INVITATIONS =====================================================================================================================> (USER)
            Route::get('wedding-invitations-update-{id}',[WeddingInvitationsController::class,'view_update_wedding_invitations'])->middleware('auth');
            Route::post('/fadd-wedding-invitations/{id}',[WeddingInvitationsController::class,'func_add_wedding_invitations'])->middleware('auth');
            Route::post('/fupdate-wedding-invitations/{id}',[WeddingInvitationsController::class,'func_update_wedding_invitations'])->middleware('auth');
            Route::delete('/fdelete-wedding-invitations/{id}',[WeddingInvitationsController::class,'func_destroy_wedding_invitations'])->middleware('auth');

        // WEDDINGS (ADMIN) =================================================================================================================> (Admin)
            Route::get('/weddings-admin',[WeddingsController::class,'index'])->middleware(['auth','adminType']);
            Route::get('/weddings-hotel-admin-{id}',[WeddingsController::class,'view_wedding_hotel_admin_detail'])->middleware(['auth','adminType']);
            Route::get('/add-wedding-package-{id}',[WeddingsController::class,'view_add_wedding_package'])->middleware(['auth','adminType']);
            Route::get('/edit-wedding-package-{id}',[WeddingsController::class,'view_edit_wedding_package'])->middleware(['auth','adminType']);
            Route::get('/getCeremonyDecorations', [WeddingsController::class, 'getCeremonyDecorations'])->middleware(['auth','adminType']);
            Route::get('/getReceptionDecorations', [WeddingsController::class, 'getReceptionDecorations'])->middleware(['auth','adminType']);
            
            Route::put('/func-update-order-wedding-flight-admin/{id}',[OrderWeddingController::class,'func_update_order_wedding_flight_admin'])->middleware('auth');
            Route::post('/fadd-wedding-contract',[WeddingsController::class,'func_add_wedding_contract'])->middleware(['auth','adminType']);
            Route::put('/fedit-wedding-package/{id}',[WeddingsController::class,'func_edit_wedding_package'])->middleware(['auth','adminType']);
            Route::put('/fupdate-wedding-contract/{id}',[WeddingsController::class,'func_edit_wedding_contract'])->middleware(['auth','adminType']);
            Route::put('/fupdate-wedding-info/{id}',[WeddingsController::class,'func_edit_wedding_info'])->middleware(['auth','adminType']);
            Route::put('/fupdate-entrance-fee/{id}',[WeddingsController::class,'func_edit_entrance_fee'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-wedding-contract/{id}',[WeddingsController::class,'delete_wedding_contract'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-wedding-package/{id}',[WeddingsController::class,'destroy_wedding'])->middleware(['auth','adminType']);
            
            Route::put('/factivate-ceremony-venue/{id}',[WeddingVenuesController::class,'func_activate_ceremony_venue'])->middleware(['auth','adminType']);
            Route::put('/fdeactivate-ceremony-venue/{id}',[WeddingVenuesController::class,'func_deactivate_ceremony_venue'])->middleware(['auth','adminType']);

        // WEDDING RECEPTION VENUE ADMIN =================================================================================================================>
            Route::post('/fcreate-new-reception-venue/{id}',[WeddingReceptionVenuesController::class,'func_add_reception_venue'])->middleware(['auth','adminType']);
            Route::get('/update-reception-venue-{id}',[WeddingReceptionVenuesController::class,'view_edit_reception_venue'])->middleware(['auth','adminType']);
            Route::put('/fupdate-reception-venue-{id}',[WeddingReceptionVenuesController::class,'func_edit_reception_venue'])->middleware(['auth','adminType']);
            Route::put('/factivate-reception-venue-{id}',[WeddingReceptionVenuesController::class,'func_activate_reception_venue'])->middleware(['auth','adminType']);
            Route::put('/fdeactivate-reception-venue-{id}',[WeddingReceptionVenuesController::class,'func_deactivate_reception_venue'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-wedding-reception-venue/{id}',[WeddingReceptionVenuesController::class,'destroy_wedding_reception_venue'])->middleware(['auth','adminType']);

        // WEDDING LUNCH VENUE ADMIN =================================================================================================================>
            Route::post('/fcreate-new-lunch-venue/{id}',[WeddingLunchVenuesController::class,'func_add_lunch_venue'])->middleware(['auth','adminType']);
            Route::get('/update-lunch-venue-{id}',[WeddingLunchVenuesController::class,'view_edit_lunch_venue'])->middleware(['auth','adminType']);
            Route::put('/fupdate-lunch-venue-{id}',[WeddingLunchVenuesController::class,'func_edit_lunch_venue'])->middleware(['auth','adminType']);
            Route::put('/factivate-lunch-venue-{id}',[WeddingLunchVenuesController::class,'func_activate_lunch_venue'])->middleware(['auth','adminType']);
            Route::put('/fdeactivate-lunch-venue-{id}',[WeddingLunchVenuesController::class,'func_deactivate_lunch_venue'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-wedding-lunch-venue/{id}',[WeddingLunchVenuesController::class,'destroy_wedding_lunch_venue'])->middleware(['auth','adminType']);
            
        // WEDDING DINNER VENUE ADMIN ==================================================================================================================>
            Route::get('/add-dinner-venue-{id}',[WeddingDinnerVenuesController::class,'view_add_dinner_venue'])->middleware(['auth','adminType']);
            Route::post('/fcreate-new-dinner-venue/{id}',[WeddingDinnerVenuesController::class,'func_add_dinner_venue'])->middleware(['auth','adminType']);
            Route::get('/update-dinner-venue-{id}',[WeddingDinnerVenuesController::class,'view_edit_dinner_venue'])->middleware(['auth','adminType']);
            Route::put('/fupdate-dinner-venue-{id}',[WeddingDinnerVenuesController::class,'func_edit_dinner_venue'])->middleware(['auth','adminType']);
            Route::put('/factivate-dinner-venue-{id}',[WeddingDinnerVenuesController::class,'func_activate_dinner_venue'])->middleware(['auth','adminType']);
            Route::put('/fdeactivate-dinner-venue-{id}',[WeddingDinnerVenuesController::class,'func_deactivate_dinner_venue'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-dinner-venue/{id}',[WeddingDinnerVenuesController::class,'destroy_dinner_venue'])->middleware(['auth','adminType']);

        // Wedding Dinner Package =================================================================================================================> (Admin)
            // Route::get('/vadd-dinner-package-{id}',[WeddingDinnerController::class,'view_add_dinner_package'])->middleware(['auth','adminType']);
            // Route::post('/fcreate-dinner-package/{id}',[WeddingDinnerController::class,'func_add_dinner_package'])->middleware(['auth','adminType']);
            // Route::get('/update-dinner-package-{id}',[WeddingDinnerController::class,'view_update_dinner_package'])->middleware(['auth','adminType']);
            // Route::put('/fupdate-dinner-package-{id}',[WeddingDinnerController::class,'func_update_dinner_package'])->middleware(['auth','adminType']);

        // Wedding Food And Beverage =================================================================================================================> (Admin)
            Route::get('/vadd-food-and-beverage/{id}',[WeddingMenuController::class,'view_add_food_and_beverage'])->middleware(['auth','adminType']);
            Route::post('/fadd-food-and-beverage/{id}',[WeddingMenuController::class,'func_add_food_and_beverage'])->middleware(['auth','adminType']);


        // CEREMONY VENUE =================================================================================================================> (Admin)
            Route::get('/add-ceremony-venue-{id}',[WeddingsController::class,'view_add_wedding_venue'])->middleware(['auth','adminType']);
            Route::post('/fadd-wedding-venue',[WeddingsController::class,'func_add_wedding_venue'])->middleware(['auth','adminType']);
            Route::get('/edit-wedding-venue-{id}',[WeddingsController::class,'view_edit_wedding_venue'])->middleware(['auth','adminType']);
            Route::put('/fedit-wedding-venue-{id}',[WeddingsController::class,'func_edit_wedding_venue'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-wedding-venue/{id}',[WeddingsController::class,'destroy_wedding_venue'])->middleware(['auth','adminType']);
        // DECORATION CEREMONY VENUE =================================================================================================================> (Admin)
            Route::get('/add-decoration-ceremony-venue-{id}',[WeddingsController::class,'view_add_decoration_ceremony_venue'])->middleware(['auth','adminType']);
            Route::get('/edit-decoration-ceremony-venue-{id}',[WeddingsController::class,'view_edit_decoration_ceremony_venue'])->middleware(['auth','adminType']);
            Route::post('/fadd-decoration-ceremony-venue-{id}',[WeddingsController::class,'func_add_decoration_ceremony_venue'])->middleware(['auth','adminType']);
            Route::put('/fedit-decoration-ceremony-venue-{id}',[WeddingsController::class,'func_edit_decoration_ceremony_venue'])->middleware(['auth','adminType']);
            Route::put('/fsave-to-draft-decoration-ceremony-venue-{id}',[WeddingsController::class,'func_save_to_draft_decoration_ceremony_venue'])->middleware(['auth','adminType']);
            Route::put('/fsave-to-active-decoration-ceremony-venue-{id}',[WeddingsController::class,'func_save_to_active_decoration_ceremony_venue'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-decoration-ceremony-venue-{id}',[WeddingsController::class,'destroy_decoration_ceremony_venue'])->middleware(['auth','adminType']);
            

            Route::get('/weddings-admin-{id}',[WeddingsController::class,'view_wedding_admin_detail'])->middleware(['auth','adminType']);
            Route::get('/weddings-edit-{id}',[WeddingsController::class,'view_edit_wedding'])->middleware(['auth','adminType']);
            Route::get('/weddings-add',[WeddingsController::class,'view_add_wedding'])->middleware(['auth','adminType']);
            Route::post('/fadd-wedding-package-{id}',[WeddingsController::class,'func_add_wedding_package'])->middleware(['auth','adminType']);
            Route::put('/fupdate-weddings/{id}',[WeddingsController::class,'func_update_wedding'])->middleware(['auth','adminType']);
            Route::put('/fadd-wedding-fixed-service/{id}',[WeddingsController::class,'func_add_wedding_fixed_service'])->middleware(['auth','adminType']);
            Route::put('/fadd-wedding-venue/{id}',[WeddingsController::class,'func_add_wedding_venue'])->middleware(['auth','adminType']);
            Route::put('/fadd-wedding-decoration/{id}',[WeddingsController::class,'func_add_wedding_decoration'])->middleware(['auth','adminType']);
            Route::put('/fadd-wedding-dinner-venue/{id}',[WeddingsController::class,'func_add_wedding_dinner_venue'])->middleware(['auth','adminType']);
            Route::put('/fadd-wedding-makeup/{id}',[WeddingsController::class,'func_add_wedding_makeup'])->middleware(['auth','adminType']);
            Route::put('/fadd-wedding-entertainment/{id}',[WeddingsController::class,'func_add_wedding_entertainment'])->middleware(['auth','adminType']);
            Route::put('/fadd-wedding-documentation/{id}',[WeddingsController::class,'func_add_wedding_documentation'])->middleware(['auth','adminType']);
            Route::put('/fadd-wedding-other/{id}',[WeddingsController::class,'func_add_wedding_other'])->middleware(['auth','adminType']);
            Route::put('/fadd-wedding-rooms/{id}',[WeddingsController::class,'func_add_wedding_room'])->middleware(['auth','adminType']);
            Route::put('/fadd-wedding-transports/{id}',[WeddingsController::class,'func_add_wedding_transport'])->middleware(['auth','adminType']);
            Route::put('/fadd-wedding-price/{id}',[WeddingsController::class,'func_add_wedding_price'])->middleware(['auth','adminType']);
            Route::put('/frefresh-wedding-price/{id}',[WeddingsController::class,'func_refresh_wedding_price'])->middleware(['auth','adminType']);
            Route::put('/factivate-wedding-package/{id}',[WeddingsController::class,'func_activate_wedding_package'])->middleware(['auth','adminType']);
            Route::put('/fdraft-wedding-package/{id}',[WeddingsController::class,'func_draft_wedding_package'])->middleware(['auth','adminType']);
            Route::put('/fdrafted-wedding-package/{id}',[WeddingsController::class,'func_drafted_wedding_package'])->middleware(['auth','adminType']);
            Route::put('/fremove-wedding-package/{id}',[WeddingsController::class,'func_removed_wedding_package'])->middleware(['auth','adminType']);
            Route::delete('/fweddings-remove/{id}',[WeddingsController::class,'destroy_wedding'])->middleware(['auth','adminType']);

        //  WEDDING VENDOR  =================================================================================================================> (Admin)
            Route::get('/vendors-admin',[VendorController::class,'index'])->middleware(['auth','checkPosition:developer,weddingDvl,weddingRsv,weddingSls,weddingAuthor']);
            Route::get('/detail-vendor-{id}',[VendorController::class,'view_vendor_detail'])->middleware(['auth','checkPosition:developer,weddingDvl,weddingRsv,weddingSls,weddingAuthor']);
            Route::post('/fadd-vendor',[VendorController::class,'func_add_vendor'])->middleware(['auth','checkPosition:developer,weddingDvl,weddingAuthor']);
            Route::post('/fadd-vendor-package',[VendorController::class,'func_add_package'])->middleware(['auth','checkPosition:developer,weddingDvl,weddingAuthor']);
            Route::put('/fupdate-vendor/{id}',[VendorController::class,'func_update_vendor'])->middleware(['auth','checkPosition:developer,weddingDvl,weddingAuthor']);
            Route::put('/fupdate-vendor-package/{id}',[VendorController::class,'func_update_vendor_package'])->middleware(['auth','checkPosition:developer,weddingDvl,weddingAuthor']);
            Route::put('/fremove-vendor-package/{id}',[VendorController::class,'func_remove_package'])->middleware(['auth','checkPosition:developer,weddingDvl,weddingAuthor']);
            Route::put('/fremove-vendor/{id}',[VendorController::class,'func_remove_vendor'])->middleware(['auth','checkPosition:developer,weddingDvl,weddingAuthor']);

        // Order User =================================================================================================================> (User)
            Route::post('/order-room-promo/{id}',[OrderController::class,'order_room_promo'])->name('hotels.order.room.promo')->middleware('auth');
            Route::post('/order-room-normal/{id}',[OrderController::class,'order_room_normal'])->name('hotels.order.room.normal')->middleware('auth');
            Route::post('/order-hotel-promo-{id}', [OrderHotelPromoController::class, 'create'])->name('order-hotel-promo.create')->middleware(['auth']);

            Route::post('/fadd-order',[OrderController::class,'func_add_order'])->name('order.create')->middleware(['auth']);

            Route::get('/edit-order-{id}',[OrderController::class,'user_edit_order'])->middleware(['auth'])->name('edit.order');

            

            Route::post('/order-hotel-promo/store', [OrderHotelPromoController::class, 'store_order_hotel_promo'])->name('order-hotel-promo.store')->middleware(['auth']);
            Route::get('/order-hotel-promo-edit-{id}',[OrderHotelPromoController::class,'order_hotel_promo_edit'])->name('order-hotel-promo-edit.view')->middleware(['auth']);
            
            Route::get('/order-hotel-promo-{id}',[OrderHotelPromoController::class,'edit_order_room_promo'])->name('edit.order.room.promo')->middleware(['auth']);
            Route::put('/fupdate-order-hotel-promo-room-{id}',[OrderHotelPromoController::class,'func_update_order_promo_room'])->name('fupdate.order.hotel.promo.room')->middleware(['auth']);
            
            Route::post('/fadd-order-hotel-promo',[OrderController::class,'func_add_order_hotel_promo'])->name('func.create.order.hotel.promo')->middleware(['auth']);


            Route::get('/editorder-room-{id}',[OrderController::class,'editorder_room'])->middleware(['auth'])->name('edit.order.room');
            
            
            Route::get('/orders',[OrderController::class,'index'])->name('orders.index')->middleware(['auth']);
            Route::get('/order-{id}',[OrderController::class,'detail_order'])->middleware(['auth']);
            Route::get('/detail-order-{id}',[OrderController::class,'user_detail_order'])->middleware(['auth'])->name('detail.user.order');
            Route::get('/editorder-optionalservice-{id}',[OrderController::class,'editorder_optionalservice'])->middleware(['auth']);
            Route::put('/fupdate-optional-service-order-{id}',[OrderController::class,'func_update_optional_service_order'])->name('fupdate.optional.service.order')->middleware(['auth']);
            Route::post('/fadd-optional-service-order',[OrderController::class,'fadd_optional_service_order'])->name('fadd.optional.service.order')->middleware(['auth']);
            Route::put('/fupdate-order-room/{id}',[OrderController::class,'func_update_order_room'])->name('fupdate.room.order')->middleware(['auth']);
            
            Route::put('/fupdate-order/{id}',[OrderController::class,'func_update_order'])->name('fupdate.order')->middleware(['auth']);


            Route::post('/fadd-optional-rate',[OrderController::class,'func_add_optional_rate'])->middleware(['auth']);
            Route::put('/fupdate-optional-rate-order/{id}',[OrderController::class,'func_update_optional_rate_order'])->middleware(['auth']);
            Route::put('/cancel-order/{id}',[OrderController::class,'func_cancel_order'])->middleware(['auth']);
            Route::put('/freupload-order/{id}',[OrderController::class,'func_reupload_order'])->middleware(['auth']);
            Route::put('/fremove-order/{id}',[OrderController::class,'func_remove_order'])->middleware(['auth']);
            Route::put('/fapprove-order-{id}',[OrderController::class,'func_approve_order'])->middleware(['auth']);
            Route::delete('/delete-order/{id}',[OrderController::class,'destroy_order'])->middleware(['auth']);
            Route::delete('/delete-opser/{id}',[OrderController::class,'destroy_opser_order'])->middleware(['auth']);

        //ORDER WEDDING USER
            
            // Route::post('/fcreate_order_wedding',[OrderWedding::class,'func_create_order_wedding'])->middleware('auth');

        // Order Admin  (ord) =================================================================================================================> (Admin)
            Route::get('/orders-admin',[OrdersAdminController::class,'index'])->middleware(['auth','adminType']);
            Route::get('/orders-admin-{id}',[OrdersAdminController::class,'view_order_admin_detail'])->name('view.order.admin.detail')->middleware(['auth','adminType']);
            Route::get('/edit-additional-services-{id}',[OrdersAdminController::class,'edit_additional_services'])->middleware(['auth','adminType']);
            Route::get('/admin-edit-order-itinerary-{id}',[OrdersAdminController::class,'admin_edit_order_itinerary'])->middleware(['auth','adminType']);
            Route::get('/edit-airport-shuttle-{id}',[OrdersAdminController::class,'edit_airport_shuttle'])->middleware(['auth','adminType']);
            Route::get('/optional-rate-add-{id}',[OrdersAdminController::class,'optional_rate_add'])->middleware(['auth','adminType']);
            Route::get('/admin-edit-order-room-{id}',[OrdersAdminController::class,'admin_edit_order_room'])->middleware(['auth','adminType']);
            Route::post('/fadd-airport-shuttle',[OrdersAdminController::class,'func_add_airport_shuttle'])->middleware(['auth','adminType']);
            Route::post('/fadd-order-note-{id}',[OrdersAdminController::class,'func_add_order_note'])->middleware(['auth','adminType']);
            Route::post('/fadd-order-wedding-note-{id}',[OrdersAdminController::class,'func_add_order_wedding_note'])->middleware(['auth','adminType']);
            Route::post('/fadmin-add-optional-service-order',[OrdersAdminController::class,'fadd_optional_service_order'])->middleware(['auth','adminType']);
            Route::put('/fedit-airport-shuttles-{id}',[OrdersAdminController::class,'func_edit_airport_shuttle'])->middleware(['auth','adminType']);
            Route::put('/fedit-additional-services-{id}',[OrdersAdminController::class,'func_edit_additional_services'])->middleware(['auth','adminType']);
            Route::put('/fupdate-pickup-dropoff-{id}',[OrdersAdminController::class,'func_update_pickup_dropoff'])->middleware(['auth','adminType']);
            Route::put('/fupdate-flight-{id}',[OrdersAdminController::class,'func_update_flight'])->middleware(['auth','adminType']);
            Route::put('/fupdate-confirmation-number-{id}',[OrdersAdminController::class,'func_update_confirmation_number'])->middleware(['auth','adminType']);
            Route::put('/fsend-confirmation-{id}',[OrdersAdminController::class,'func_send_confirmation'])->middleware(['auth','adminType']);
            Route::put('/fresend-confirmation-order-{id}',[OrdersAdminController::class,'resend_confirmation_order'])->middleware(['auth','adminType']);
            Route::put('/fgenerate-invoice-{id}',[OrdersAdminController::class,'fgenerate_invoice'])->middleware(['auth','adminType']);
            Route::put('/fsend-approval-email-{id}',[OrdersAdminController::class,'fsend_approval_email'])->middleware(['auth','adminType']);
            Route::put('/fadd-guide-order-{id}',[OrdersAdminController::class,'func_add_guide_order'])->middleware(['auth','adminType']);
            Route::put('/fedit-guide-order-{id}',[OrdersAdminController::class,'func_edit_guide_order'])->middleware(['auth','adminType']);
            Route::put('/fdelete-guide-order-{id}',[OrdersAdminController::class,'func_delete_guide_order'])->middleware(['auth','adminType']);
            Route::put('/fadd-driver-order-{id}',[OrdersAdminController::class,'func_add_driver_order'])->middleware(['auth','adminType']);
            Route::put('/fedit-driver-order-{id}',[OrdersAdminController::class,'func_edit_driver_order'])->middleware(['auth','adminType']);
            Route::put('/fdelete-driver-order-{id}',[OrdersAdminController::class,'func_delete_driver_order'])->middleware(['auth','adminType']);
            Route::put('/fedit-confirmation-order-{id}',[OrdersAdminController::class,'func_edit_confirmation_order'])->middleware(['auth','adminType']);
            Route::put('/fadd-confirmation-order-{id}',[OrdersAdminController::class,'func_add_confirmation_order'])->middleware(['auth','adminType']);
            Route::put('/factivate-order/{id}',[OrdersAdminController::class,'func_activate_order'])->middleware(['auth','adminType']);

            
            
            Route::put('/fadmin-update-order/{id}',[OrdersAdminController::class,'fadmin_update_order'])->middleware(['auth','adminType']);
            Route::put('/farchive-order/{id}',[OrdersAdminController::class,'func_archive_order'])->middleware(['auth','adminType']);
            Route::put('/fupdate-order-invalid/{id}',[OrdersAdminController::class,'func_update_order_invalid'])->middleware(['auth','adminType']);
            Route::put('/fupdate-order-rejected/{id}',[OrdersAdminController::class,'func_update_order_rejected'])->middleware(['auth','adminType']);
            Route::put('/fupdate-order-discounts/{id}',[OrdersAdminController::class,'func_update_order_discounts'])->middleware(['auth','adminType']);

            Route::put('/fupdate-order-itinerary/{id}',[OrdersAdminController::class,'func_update_order_itinerary'])->middleware(['auth','adminType']);
            Route::put('/fadd-order-wedding-itinerary-{id}',[OrdersAdminController::class,'func_add_order_wedding_itinerary'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-wedding-itinerary/{id}',[OrdersAdminController::class,'func_delete_order_wedding_itinerary'])->middleware(['auth','adminType']);

            Route::get('/update-wedding-service-{id}',[OrdersAdminController::class, 'view_update_wedding_service'])->middleware(['auth','adminType']);
            Route::put('/fupdate-order-wedding-venue/{id}',[OrdersAdminController::class,'func_update_order_wedding_venue'])->middleware(['auth','adminType']);
            Route::put('/fupdate-order-wedding-room/{id}',[OrdersAdminController::class,'func_update_order_wedding_room'])->middleware(['auth','adminType']);
            Route::put('/fupdate-order-wedding-makeup/{id}',[OrdersAdminController::class,'func_update_order_wedding_makeup'])->middleware(['auth','adminType']);
            Route::put('/fupdate-order-wedding-decoration/{id}',[OrdersAdminController::class,'func_update_order_wedding_decoration'])->middleware(['auth','adminType']);
            Route::put('/fupdate-order-wedding-dinner_venue/{id}',[OrdersAdminController::class,'func_update_order_wedding_dinner_venue'])->middleware(['auth','adminType']);
            Route::put('/fupdate-order-wedding-entertainment/{id}',[OrdersAdminController::class,'func_update_order_wedding_entertainment'])->middleware(['auth','adminType']);
            Route::put('/fupdate-order-wedding-documentation/{id}',[OrdersAdminController::class,'func_update_order_wedding_documentation'])->middleware(['auth','adminType']);
            Route::put('/fupdate-order-wedding-transport/{id}',[OrdersAdminController::class,'func_update_order_wedding_transport'])->middleware(['auth','adminType']);
            Route::put('/fupdate-order-wedding-other/{id}',[OrdersAdminController::class,'func_update_order_wedding_other'])->middleware(['auth','adminType']);
            Route::put('/func-final-order/{id}',[OrdersAdminController::class,'func_final_wedding_order'])->middleware(['auth','adminType']);


            Route::put('/fremove-order-discounts/{id}',[OrdersAdminController::class,'func_remove_order_discounts'])->middleware(['auth','adminType']);
            Route::put('/fadmin-update-optional-service-order-{id}',[OrdersAdminController::class,'func_update_optional_service_order'])->middleware(['auth','adminType']);
            Route::put('/fadmin-update-order-room/{id}',[OrdersAdminController::class,'func_update_order_room'])->middleware(['auth','adminType']);
            Route::put('/fadmin-update-bridal/{id}',[OrdersAdminController::class,'func_update_bridal'])->middleware(['auth','adminType']);
            Route::delete('/fremove-airport-shuttle/{id}',[OrdersAdminController::class,'func_remove_airport_shuttle'])->middleware(['auth','adminType']);
            Route::delete('/admin-delete-opser/{id}',[OrdersAdminController::class,'destroy_opser_order'])->middleware(['auth','adminType']);
            
        // ORDER WEDDING ADMIN (rsv) =================================================================================================================> (Admin)
            Route::get('/contract-{id}',[OrdersAdminController::class,'view_contract_wedding_eng'])->middleware(['auth','adminType']);
            Route::put('/confirm-order-wedding/{id}',[OrdersAdminController::class,'func_confirm_order_wedding'])->middleware(['auth','adminType']);
            Route::put('/func-add-order-wedding-flight-admin/{id}',[OrdersAdminController::class,'func_add_order_wedding_flight'])->middleware(['auth','adminType']);
            Route::delete('/func-delete-order-wedding-flight-admin/{id}',[OrdersAdminController::class,'func_delete_order_wedding_flight_admin'])->middleware(['auth','adminType']);
            Route::put('/fadd-invitation-order-wedding/{id}',[OrdersAdminController::class,'func_add_order_wedding_invitation'])->middleware(['auth','adminType']);
            Route::put('/fedit-invitation-order-wedding/{id}',[OrdersAdminController::class,'func_edit_order_wedding_invitation'])->middleware(['auth','adminType']);
            Route::delete('/func-delete-order-wedding-invitation-admin/{id}',[OrdersAdminController::class,'func_delete_order_wedding_invitation'])->middleware(['auth','adminType']);
            
            Route::put('/admin-fadd-additional-charge/{id}',[OrdersAdminController::class,'func_admin_add_request_service'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-additional-charge/{id}',[OrdersAdminController::class,'func_admin_update_request_service'])->middleware(['auth','adminType']);
            Route::put('/admin-fdelete-additional-charge/{id}',[OrdersAdminController::class,'func_admin_delete_request_service'])->middleware(['auth','adminType']);

            Route::put('/fadmin-add-order-wedding-accommodation/{id}',[OrdersAdminController::class,'func_admin_add_order_wedding_accommodation'])->middleware(['auth','adminType']);
            Route::get('/admin-validate-order-wedding-accommodation-{id}',[OrdersAdminController::class,'view_validate_order_wedding_accommodation'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-accommodation-wedding-order/{id}',[OrdersAdminController::class,'func_update_wedding_order_accommodation'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-accommodation-brides/{id}',[OrdersAdminController::class,'admin_func_update_accommodation_brides'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-accommodation-invitation-price/{id}',[OrdersAdminController::class,'admin_func_update_accommodation_invitation'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-price-accommodation/{id}',[OrdersAdminController::class,'admin_func_update_price_accommodation'])->middleware(['auth','adminType']);
            Route::delete('/admin-func-delete-order-wedding-accommodation/{id}',[OrdersAdminController::class,'func_delete_order_wedding_accommodation_invitation'])->middleware(['auth','adminType']);
            
            Route::get('/validate-orders-wedding-{id}',[OrdersAdminController::class,'view_validate_order_wedding'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-wedding-order-bride/{id}',[OrdersAdminController::class,'func_validate_bride_order_wedding'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-wedding-order-wedding/{id}',[OrdersAdminController::class,'func_validate_wedding_and_reservation'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-wedding-order-ceremony-venue/{id}',[OrdersAdminController::class,'func_validate_wedding_order_ceremony_venue'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-order-wedding-remark/{id}',[OrdersAdminController::class,'func_validate_wedding_order_remark'])->middleware(['auth','adminType']);
            
            Route::put('/admin-fdelete-wedding-order-ceremony-venue/{id}',[OrdersAdminController::class,'func_admin_delete_ceremony_venue'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-wedding-order-decoration-ceremony-venue/{id}',[OrdersAdminController::class,'func_admin_update_decoration_ceremony_venue'])->middleware(['auth','adminType']);
            Route::put('/admin-fdelete-wedding-order-decoration-ceremony-venue/{id}',[OrdersAdminController::class,'func_admin_delete_decoration_ceremony_venue'])->middleware(['auth','adminType']);

            Route::put('/admin-fupdate-wedding-order-reception-venue/{id}',[OrdersAdminController::class,'admin_func_update_reception_venue'])->middleware(['auth','adminType']);
            Route::put('/admin-fdelete-wedding-order-reception-venue/{id}',[OrdersAdminController::class,'admin_func_delete_reception_venue'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-wedding-order-decoration-reception-venue/{id}',[OrdersAdminController::class,'admin_func_update_decoration_reception_venue'])->middleware(['auth','adminType']);
            Route::put('/admin-fdelete-wedding-order-decoration-reception-venue/{id}',[OrdersAdminController::class,'admin_func_delete_decoration_reception_venue'])->middleware(['auth','adminType']);
            
            Route::put('/admin-fadd-additional-service-to-order-wedding/{id}',[OrdersAdminController::class,'admin_func_add_additional_service_to_wedding_order'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-confirmation-number-{id}',[OrdersAdminController::class,'admin_func_update_confirmation_numbber'])->middleware(['auth','adminType']);
            Route::put('/fvalidate-order-wedding/{id}',[OrdersAdminController::class,'admin_func_validate_order_wedding'])->middleware(['auth','adminType']);
            
            Route::put('/admin-fadd-transport-invitation/{id}',[OrdersAdminController::class,'admin_func_add_transport_invitation_wedding'])->middleware(['auth','adminType']);
            Route::put('/admin-fupdate-transport-invitation/{id}',[OrdersAdminController::class,'admin_func_update_transport_invitation'])->middleware(['auth','adminType']);
            
            Route::post('/fadmin-update-lunch-venue-order-wedding/{id}',[OrdersAdminController::class,'admin_func_update_lunch_venue'])->middleware(['auth','adminType']);
            Route::post('/fadmin-delete-lunch-venue/{id}',[OrdersAdminController::class,'admin_func_delete_lunch_venue'])->middleware(['auth','adminType']);
            
        // Reservation (rsv) =================================================================================================================> (Admin)
            Route::get('/reservation',[ReservationController::class, 'index'])->middleware(['auth','adminType']);
            Route::get('/order-rsv-{id}',[ReservationController::class, 'view_order_rsv'])->middleware(['auth','adminType']);
            Route::get('/reservation-{id}',[ReservationController::class, 'view_detail_reservation'])->middleware(['auth','adminType']);
            Route::get('/rsv-hotel-{id}',[ReservationController::class, 'view_reservation_hotel'])->middleware(['auth','adminType']);
            Route::get('/add-rsv-order-{id}',[ReservationController::class, 'view_add_rsv_order'])->middleware(['auth','adminType']);
            Route::get('/add-rsv-transport-{id}',[ReservationController::class, 'view_add_rsv_transport'])->middleware(['auth','adminType']);
            Route::get('/add-rsv-activity-tour-{id}',[ReservationController::class, 'view_add_rsv_activity_tour'])->middleware(['auth','adminType']);
            Route::get('/add-itinerary-{id}',[ReservationController::class, 'view_add_itinerary'])->middleware(['auth','adminType']);
            
            Route::put('/fremove-rsv-order/{id}',[ReservationController::class,'func_remove_rsv_order'])->middleware(['auth','adminType']);
            Route::put('/fadd-reservation',[ReservationController::class,'func_add_rsv_order'])->middleware(['auth','adminType']);
            Route::put('/fupdate-accommodation/{id}',[ReservationController::class,'func_update_accommodation'])->middleware(['auth','adminType']);
            Route::put('/fupdate-restaurant/{id}',[ReservationController::class,'func_update_restaurant'])->middleware(['auth','adminType']);
            Route::put('/fupdate-activity-tour/{id}',[ReservationController::class,'func_update_activity_tour'])->middleware(['auth','adminType']);
            Route::put('/fupdate-include/{id}',[ReservationController::class,'func_update_include'])->middleware(['auth','adminType']);
            Route::put('/fupdate-exclude/{id}',[ReservationController::class,'func_update_exclude'])->middleware(['auth','adminType']);
            Route::put('/fupdate-remark/{id}',[ReservationController::class,'func_update_remark'])->middleware(['auth','adminType']);
            Route::put('/fupdate-invoice-bank/{id}',[ReservationController::class,'func_update_invoice_bank'])->middleware(['auth','adminType']);
            Route::put('/activate-reservation/{id}',[ReservationController::class,'func_activate_reservation'])->middleware(['auth','adminType']);
            Route::put('/deactivate-reservation/{id}',[ReservationController::class,'func_deactivate_reservation'])->middleware(['auth','adminType']);
            Route::put('/fadd-guest',[ReservationController::class,'func_add_guest'])->middleware(['auth','adminType']);
            Route::put('/fadd-restaurant',[ReservationController::class,'func_add_restaurant'])->middleware(['auth','adminType']);
            Route::put('/fadd-include',[ReservationController::class,'func_add_include'])->middleware(['auth','adminType']);
            Route::put('/fadd-exclude',[ReservationController::class,'func_add_exclude'])->middleware(['auth','adminType']);
            Route::put('/fadd-remark',[ReservationController::class,'func_add_remark'])->middleware(['auth','adminType']);
            Route::put('/fadd-invoice',[ReservationController::class,'func_add_invoice'])->middleware(['auth','adminType']);
            Route::put('/fadd-itinerary',[ReservationController::class,'func_add_itinerary'])->middleware(['auth','adminType']);
            Route::put('/fupdate-cin-cut/{id}',[ReservationController::class,'fupdate_cin_cut'])->middleware(['auth','adminType']);
            Route::put('/fupdate-reservation/{id}',[ReservationController::class,'func_update_reservation'])->middleware(['auth','adminType']);
            Route::put('/fupdate-reservation-pickup-name/{id}',[ReservationController::class,'func_update_reservation_pickup_name'])->middleware(['auth','adminType']);
            Route::put('/fupdate-guest/{id}',[ReservationController::class,'func_update_guest'])->middleware(['auth','adminType']);
            Route::put('/fupdate-itinerary/{id}',[ReservationController::class,'func_update_itinerary'])->middleware(['auth','adminType']);
            Route::delete('/delete-guest/{id}',[ReservationController::class,'destroy_guest'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-restaurant/{id}',[ReservationController::class,'destroy_restaurant'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-include/{id}',[ReservationController::class,'destroy_include'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-exclude/{id}',[ReservationController::class,'destroy_exclude'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-remark/{id}',[ReservationController::class,'destroy_remark'])->middleware(['auth','adminType']);
            Route::delete('/fdelete-rsv/{id}',[ReservationController::class,'destroy_rsv'])->middleware(['auth','adminType']);
            Route::delete('/delete-itinerary/{id}',[ReservationController::class,'destroy_itinerary'])->middleware(['auth','adminType']);

        //Bank account =================================================================================================================> (Admin)
            Route::put('/fadd-bank-account',[BankAccountController::class,'func_add_bank_account'])->middleware(['auth','adminType']);
            Route::put('/fupdate-bank-account/{id}',[BankAccountController::class,'func_update_bank_account'])->middleware(['auth','adminType']);
            Route::delete('/delete-bank-account/{id}',[BankAccountController::class,'destroy_bank_account'])->middleware(['auth','adminType']);

        //additional service =================================================================================================================> (Admin)
            Route::post('/add-additional-service',[ReservationController::class,'func_add_additional_service'])->middleware(['auth','adminType']);
            Route::put('/update-additional-service/{id}',[ReservationController::class,'func_update_additional_service'])->middleware(['auth','adminType']);
            Route::delete('/delete-additional-service/{id}',[ReservationController::class,'destroy_additional_service'])->middleware(['auth','adminType']);

        // Invoice (inv) =================================================================================================================> (Admin)
            Route::get('/invoice',[InvoiceAdminController::class,'index'])->middleware(['auth','adminType']);
            Route::get('/invoice-{id}',[InvoiceAdminController::class,'view_detail_invoice'])->middleware(['auth','adminType']);
            Route::put('/fupdate-additional-inv/{id}',[InvoiceAdminController::class,'func_update_additional_inv'])->middleware(['auth','adminType']);
            Route::delete('/delete-additional-inv/{id}',[InvoiceAdminController::class,'destroy_additional_inv'])->middleware(['auth','adminType']);

        // PAYMENT CONFIRMATION
            Route::post('/fpayment-confirmation-{id}',[PaymentConfirmationController::class,'payment_confirmation'])->name('payment-confirmation')->middleware(['auth']);
            Route::post('/fwedding-payment-confirmation-{id}',[PaymentConfirmationController::class,'wedding_payment_confirmation'])->name('wedding-payment-confirmation')->middleware(['auth']);
            Route::put('/fupdate-payment-confirmation/{id}',[PaymentConfirmationController::class,'update_payment_confirmation'])->name('update-payment-confirmation')->middleware(['auth']);

        // ADMIN CONFIRMATION PAYMENT
            Route::post('/fconfirm-receipt-{id}',[OrdersAdminController::class,'fadmin_confirm_receipt'])->name("fadmin.confirm.receipt")->middleware(['auth','adminType']);
            Route::post('/fconfirmation-payment-{id}',[OrdersAdminController::class,'fconfirmation_payment'])->name("func.confirm.payment")->middleware(['auth','adminType']);
            Route::post('/fadmin-add-payment-confirmation-{id}',[OrdersAdminController::class,'admin_add_payment_confirmation'])->name('func.admin.add.receipt')->middleware(['auth','adminType']);
            Route::post('/forder-wedding-confirmation-payment-{id}',[OrdersAdminController::class,'forder_wedding_confirmation_payment'])->middleware(['auth','adminType']);
            Route::post('/order-wedding-add-payment-confirmation-{id}',[OrdersAdminController::class,'admin_add_payment_confirmation_to_order_wedding'])->middleware(['auth','adminType']);

        // calendar
            Route::get('calendar-event', [CalendarController::class, 'index'])->middleware(['auth','adminType']);
            Route::post('calendar-crud-ajax', [CalendarController::class, 'calendarEvents'])->middleware(['auth','adminType']);

        // Email
            Route::get('/email-reservation', [MailController::class, 'index'])->middleware(['auth','adminType']);
            Route::get('/send-email-approval', [MailController::class, 'sendEmailApproval'])->name('send.email-approval')->middleware(['auth','adminType','adminType','developerPos']);
            Route::get('/promo-email-blast', [EmailBlastsController::class, 'index'])->middleware(['auth','checkPosition:developer,author']);
            Route::get('/send-promo-to-agent-{id}', [EmailBlastsController::class, 'send_email_promo'])->middleware(['auth','checkPosition:developer,author']);
            Route::get('/send-promo-to-specific-agent-{id}', [EmailBlastsController::class, 'send_specific_email_promo'])->middleware(['auth','checkPosition:developer,author']);
            Route::post('/fsend-promo-email-to-agent-{id}', [EmailBlastsController::class, 'func_send_email_promo'])->name('send.promo.email')->middleware(['auth','checkPosition:developer,author']);
            Route::post('/fsend-promo-specific-email-to-agent-{id}', [EmailBlastsController::class, 'func_send_specific_email_promo'])->name('send.promo.specific.email')->middleware(['auth','checkPosition:developer,author']);
            Route::get('/send-promo-to-agent-{id}', [EmailBlastsController::class, 'send_email_promo'])->middleware(['auth','checkPosition:developer,author']);

            Route::get('/unsubscribe', function (Request $request) {
                $user = User::where('email', $request->email)->first();
            
                if ($user) {
                    return view('unsubscribe', ['user' => $user]);
                }
            
                return redirect('/')->with('error', 'User not found.');
            })->name('unsubscribe');
            Route::post('/process-unsubscribe', function (Request $request) {
                $user = User::where('email', $request->email)->first();
            
                if ($user) {
                    $user->is_subscribed = false;
                    $user->unsubscribe_reason = $request->reason; // Pastikan field ini ada di tabel users
                    $user->save();
            
                    return redirect('/')->with('success', 'You have been unsubscribed successfully. Thank you for your feedback!');
                }
            
                return redirect('/')->with('error', 'User not found.');
            })->name('process_unsubscribe');
            Route::get('/subscribe', function (Request $request) {
                $user = User::where('email', $request->email)->first();
            
                if ($user) {
                    return view('subscribe', ['user' => $user]);
                }
            
                return redirect('/')->with('error', 'User not found.');
            })->name('subscribe');
            
            Route::post('/process-subscribe', function (Request $request) {
                $user = User::where('email', $request->email)->first();
            
                if ($user) {
                    $user->is_subscribed = true;
                    $user->save();
            
                    return redirect('/')->with('success', 'You have successfully subscribed again!');
                }
            
                return redirect('/')->with('error', 'User not found.');
            })->name('process_subscribe');
        // User Manager
            Route::get('/user-manager', [UsersController::class, 'user_manager'])->name('user-manager')->middleware(['auth','checkPosition:developer']);
            Route::post('/create-user',[UsersController::class,'func_create_user'])->name('create-user')->middleware(['auth','checkPosition:developer']);
            Route::put('/fedit-user-{id}',[UsersController::class,'func_edit_user'])->name('edit-user')->middleware(['auth','checkPosition:developer']);
            Route::put('/fapprove-user-{id}',[UsersController::class,'func_approve_user'])->name('approve-user')->middleware(['auth','checkPosition:developer']);
            Route::put('/fverified-user-{id}',[UsersController::class,'func_verified_user'])->name('verified-user')->middleware(['auth','checkPosition:developer']);

        // Download data
            Route::get('/download', [DownloadDataHotelController::class, 'index'])->middleware('auth');
            Route::get('/download-data-hotel', [DownloadDataHotelController::class, 'down_data_hotel'])->middleware('auth');
            Route::get('/download-data-hotel-package', [DownloadDataHotelController::class, 'down_data_hotel_package'])->middleware('auth');
            Route::get('/download-data-hotel-promo', [DownloadDataHotelController::class, 'down_data_hotel_promo'])->middleware('auth');
            Route::get('/download-data-tour', [DownloadDataHotelController::class, 'down_data_tour'])->middleware('auth');
            Route::get('/data-hotel', [DownloadDataHotelController::class, 'view_download_hotel'])->middleware('auth');
            Route::get('generate-pdf', [DownloadDataHotelController::class, 'generatePDF']);
            Route::get('/download-data-hotel-test', [DownloadDataHotelController::class, 'down_data_hotel_test'])->middleware('auth');
            Route::post('/func-action-log-download-hotel', [DownloadDataHotelController::class, 'action_log_download_hotel'])->middleware('auth');

        // Contract Agent =================================================================================================================>
            // Route::get('/contract',[ContractAgentController::class,'index'])->middleware(['auth','adminType']);

        // BOOKING CODE =================================================================================================================> (Admin)
            Route::get('/booking-code',[BookingCodeController::class,'index'])->name('booking-code')->middleware(['auth','adminType']);
            Route::put('/fadd-booking-code',[BookingCodeController::class,'create'])->name('fadd-booking-code')->middleware(['auth','checkPosition:developer,author']);
            Route::put('/fupdate-bookingcode-{id}',[BookingCodeController::class,'func_update_bookingcode'])->name('f-update-booking-code')->middleware(['auth','checkPosition:developer,author']);
            Route::put('/fremove-bookingcode/{id}',[BookingCodeController::class,'func_remove_bookingcode'])->name('f-remove-booking-code')->middleware(['auth','checkPosition:developer,author']);

        // PROMOTIONS =================================================================================================================> (Admin)
            Route::get('/promotion',[PromotionController::class,'index'])->name('promotion')->middleware(['auth','checkPosition:developer,reservation,author,weddingRsv,weddingSls,weddingAuthor,weddingDvl']);
            Route::post('/fadd-promotion',[PromotionController::class,'create'])->name('fadd-promotion')->middleware(['auth','checkPosition:developer,author']);
            Route::post('/fupdate-promotion/{id}',[PromotionController::class,'update'])->name('fupdate-promotion')->middleware(['auth','checkPosition:developer,author']);
            Route::post('/fremove-promotion/{id}',[PromotionController::class,'destroy'])->name('fremove-promotion')->middleware(['auth','checkPosition:developer,author']);

        // GUIDE =================================================================================================================>(Admin)
            Route::get('/guides-admin',[GuideController::class,'index'])->middleware(['auth','adminType']);
            Route::post('/fcreate-guide',[GuideController::class,'create'])->name('create-guide')->middleware(['auth','adminType']);
            Route::post('/fedit-guide-{id}',[GuideController::class,'edit'])->name('edit-guide')->middleware(['auth','adminType']);
            Route::delete('/fdestroy-guide/{id}',[GuideController::class,'destroy'])->middleware(['auth','adminType']);

        // DRIVER =================================================================================================================>(Admin)
            Route::get('/drivers-admin',[DriversController::class,'index'])->middleware(['auth','adminType']);
            Route::post('/fcreate-driver',[DriversController::class,'create'])->name('create-driver')->middleware(['auth','adminType']);
            Route::post('/fedit-driver-{id}',[DriversController::class,'edit'])->name('edit-driver')->middleware(['auth','adminType']);
            Route::delete('/fdestroy-driver/{id}',[DriversController::class,'destroy'])->middleware(['auth','adminType']);
            Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
            Route::post('/chat/send-message', [ChatController::class, 'sendMessage'])->name('chat.send');

        // CONTRAC =================================================================================================================>(Admin)
            Route::get('/confirmation-order-{id}', [OrdersAdminController::class, 'confirmation_order'])->middleware(['auth','adminType']);
            Route::get('/print-contract-order-{id}', [OrdersAdminController::class, 'print_contract_order'])->middleware(['auth','adminType']);
            Route::get('/print-contract-wedding-{id}', [OrdersAdminController::class, 'print_contract_wedding'])->middleware(['auth','adminType']);
            Route::get('/zh-print-contract-wedding-{id}', [OrdersAdminController::class, 'zh_print_contract_wedding'])->middleware(['auth']);
            Route::get('/en-print-contract-wedding-{id}', [OrdersAdminController::class, 'en_print_contract_wedding'])->middleware(['auth']);

            // Mail
            Route::get('/new-order-wedding-mail', [MailController::class, 'view_email_order_wedding']);
            Route::get('/email-approval', [MailController::class, 'view_email_approval']);
            Route::get('/email-booking', [MailController::class, 'view_email_booking']);
            Route::get('/confirmation-mail', [MailController::class, 'view_confirm_email']);
            Route::get('/confirmation-payment', [MailController::class, 'view_email_payment_confirmation']);
            Route::get('/manual-book', [ManualBookController::class, 'index']);

        // FOOTER
        // Term And Condition =================================================================================================================>
            Route::get('/term-and-condition',[TermAndConditionController::class,'index'])->middleware(['auth','checkPosition:developer']);
            // Route::get('/privacy-policy',[TermAndConditionController::class,'v_privacy_policy'])->middleware('auth');
            Route::put('/fupdate-policy/{id}',[TermAndConditionController::class,'func_edit_policy'])->middleware(['auth','checkPosition:developer']);
            Route::put('/fadd-policy',[TermAndConditionController::class,'func_add_policy'])->middleware(['auth','checkPosition:developer']);
            Route::delete('/fdestroy-policy/{id}',[TermAndConditionController::class,'fdestroy_policy'])->middleware(['auth','checkPosition:developer']);
            
            
            Route::get('/contract-inv', [OrdersAdminController::class, 'confirmation_order']);
            
            // FORM WIZARD
            Route::get('form-wizard', function () {
                return view('wizard');
            });
            // ATTENTIONS
            Route::get('/attentions', [AttentionController::class, 'attentions'])->middleware(['auth','checkPosition:developer']);
            Route::put('/fupdate-attention-{id}',[AttentionController::class,'func_update_attention'])->middleware(['auth','checkPosition:developer']);
            Route::delete('/fremove-attention/{id}',[AttentionController::class,'func_delete_attention'])->middleware(['auth','checkPosition:developer']);
        });
    });
});
Route::get('/approval/pending', function () {
    return redirect('/profile');
})->name('approval.pending');