<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>{{ config('app.name', 'Bali Kami Tour') }}</title>
	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="images/balikami/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="images/balikami/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="images/balikami/favicon-16x16.png">
	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/panel/styles/core.css">
	<link rel="stylesheet" type="text/css" href="/panel/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="/panel/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="/panel/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="/panel/styles/style.css">
	<link rel="stylesheet" type="text/css" href="/panel/fullcalendar/fullcalendar.css">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/panel/dropzone/dropzone.css">
	<!-- Slick Slider css -->
	<link rel="stylesheet" type="text/css" href="/panel/slick/slick.css">
	<!-- bootstrap-touchspin css -->
	<link rel="stylesheet" type="text/css" href="/panel/bootstrap-touchspin/jquery.bootstrap-touchspin.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js "></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script type="text/javascript">  
		if(performance.navigation.type == 2){
		   location.reload(true);
		}
	</script>
	</head>
	<body class="sidebar-light">
        @include('component.menu')
        {{-- @include('component.sysload') --}}
        @include('layouts.left-navbar')
        <div class="mobile-menu-overlay"></div>
        <div class="main-container">
            <div class="pd-ltr-20">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="title">
                                <i class="icon-copy fa fa-car"></i>
                                {{ 'ORD.' . date('Ymd', strtotime($now)) . '.TRN' . $orderno }}
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard">@lang('messages.Dashboard')</a></li>
                                    <li class="breadcrumb-item"><a href="transports">@lang('messages.Transports')</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:history.back()">{{ $transport->name }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ $price->type }}</a></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-8">
                        <div class="card-box">
                            <div class="card-box-title">
                                <div class="subtitle"><i class="icon-copy fa fa-tag" aria-hidden="true"></i>@lang('messages.Create Order')</div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-6">
                                    <div class="order-bil text-left">
                                        <img src="{{ config('app.logo_dark') }}" alt="{{ config('app.alt_logo') }}">
                                    </div>
                                </div>
                                <div class="col-6 col-md-6 flex-end">
                                    <div class="label-title">@lang('messages.Order')</div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <div class="label-date float-right" style="width: 100%">
                                        {{ dateFormat($now) }}
                                    </div>
                                </div>
                            </div>
                            <form id="create-order-transport-{{ $price->id }}" action="/fadd-order" method="POST">
                                @csrf
                                <div class="modal-body pd-5">
                                    <div class="business-name">{{ $business->name }}</div>
                                    <div class="bussines-sub">{{ $business->caption }}</div>
                                    <hr class="form-hr">
                                    <div class="row">
                                        <div class="col-md-6 m-b-8">
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="page-list"> @lang('messages.Order No') </div>
                                                    <div class="page-list"> @lang('messages.Order Date') </div>
                                                    <div class="page-list"> @lang('messages.Service') </div>
                                                </div>
                                                <div class="col-8">
                                                    <div class="page-list-value">
                                                        {{ 'ORD.' . date('Ymd', strtotime($now)) . '.TRN' . $orderno }}
                                                    </div>
                                                    <div class="page-list-value">
                                                        {{ dateFormat($now) }}
                                                    </div>
                                                    <div class="page-list-value">@lang('messages.Transport')</div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        {{-- Admin create order ================================================================= --}}
                                        @canany(['posDev','posAuthor','posRsv'])
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="user_id" class="col-sm-12 col-md-12 col-form-label">Select Agent <span>*</span></label>
                                                    <div class="col-sm-12">
                                                        <select name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id') }}" required>
                                                            <option selected value="">Select Agent</option>
                                                            @foreach ($agents as $agent)
                                                                <option value="{{ $agent->id }}">{{ $agent->username." (".$agent->code.") @".$agent->office }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('user_id')
                                                            <div class="alert-form">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endcanany
        
        
                                    </div>
                                    <div class="page-subtitle">@lang('messages.Order')</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="page-list">@lang('messages.Transport')</div>
                                                    <div class="page-list">@lang('messages.Type')</div>
                                                    <div class="page-list">@lang('messages.Capacity')</div>
                                                </div>
                                                <div class="col-8">
                                                    <div class="page-list-value">{{ $transport->name }}</div>
                                                    <div class="page-list-value">@lang('messages.'.$transport->type)</div>
                                                    <div class="page-list-value">{{ $transport->capacity . ' ' }}@lang('messages.Seat')</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="page-list">@lang('messages.Service')</div>
                                                    @if ($price->type == "Daily Rent")
                                                        <div class="page-list">@lang('messages.Location')</div>
                                                    @else
                                                        <div class="page-list">@lang('messages.Src') - @lang('messages.Dst')</div>
                                                    @endif
                                                    <div class="page-list">@lang('messages.Duration')</div>
                                                </div>
                                                <div class="col-8">
                                                    <div class="page-list-value">{{ $price->type }}</div>
                                                    @if ($price->type == "Daily Rent")
                                                        <div class="page-list-value">{{ $price->src }}</div>
                                                    @else
                                                        <div class="page-list-value">{{ $price->src." - ".$price->dst }}</div>
                                                    @endif
                                                    
                                                    <div class="page-list-value">{{ $price->duration . ' ' }}@lang('messages.Hours')</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="page-note">
                                                @if (isset($transport->include))
                                                    <b>@lang('messages.Include') :</b><br>
                                                    {!! $transport->include !!}
                                                @endif
                                                @if (isset($transport->additional_info) or isset($price->additional_info))
                                                    <hr class="form-hr">
                                                    <b>@lang('messages.Additional Information') :</b> <br>
                                                    {!! $transport->additional_info !!}<br>
                                                    {!! $price->additional_info !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="page-subtitle">@lang('messages.Guest')</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="number_of_guests">@lang('messages.Number of Guest') </label>
                                                    <input name="number_of_guests" min="1" max="{{ $transport->capacity }}" wire:model="number_of_guests" class="form-control @error('number_of_guests') is-invalid @enderror" placeholder="@lang('messages.Maximum') {{ $transport->capacity }} @lang('messages.Guests')" type="number" required>
                                                @error('number_of_guests')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="travel_date">@lang('messages.Pickup Date') </label>
                                                <input id="travel_date" name="travel_date" wire:model="travel_date" class="form-control datetimepicker @error('travel_date') is-invalid @enderror" placeholder="@lang('messages.Select date and time')" type="text" required>
                                                @error('travel_date')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        @if ($price->type == "Daily Rent")
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="duration" >@lang('messages.Duration') </label>
                                                        <input name="duration" min="1" wire:model="duration" class="form-control @error('duration') is-invalid @enderror" placeholder="@lang('messages.Insert duration by day')" type="number" required>
                                                    @error('duration')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @else
                                            <input type="hidden" name="duration" value="{{ $price->duration }}">
                                        @endif
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="guest_detail" >@lang('messages.Guest Detail') </label>
                                                <textarea name="guest_detail" placeholder="@lang('messages.Insert guest name')" class="ckeditor form-control border-radius-0" value="{{ old('guest_detail') }}" required></textarea>
                                                @error('guest_detail')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="note">@lang('messages.Note')</label>
                                                <textarea id="note" name="note" placeholder="@lang('messages.Optional')" class="ckeditor form-control border-radius-0" value="{{ old('note') }}"></textarea>
                                                @error('note')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 m-b-8">
                                            <div class="box-price-kicked">
                                                <div class="row">
                                                    <div class="col-6 col-md-6">
                                                        @if (isset($bookingcode->code) or $promotion_price > 0)
                                                            <div class="modal-text-price">@lang('messages.Normal Price') :</div>
                                                            <hr class="form-hr">
                                                            @if (isset($bookingcode->code))
                                                                <div class="modal-text-price">@lang('messages.Booking Code') :</div>
                                                            @endif
                                                            @if ($promotion_price > 0)
                                                                <div class="modal-text-price">@lang('messages.Promotion') :</div>
                                                            @endif
                                                            <hr class="form-hr">
                                                        @endif
                                                        <div class="price-name">@lang('messages.Total Price')</div>
                                                    </div>
                                                    <div class="col-6 col-md-6 text-right">
                                                        @if (isset($bookingcode->code) or $promotion_price > 0)
                                                            <div class="modal-num-price">$<span id="normal_price">{{ $normal_price }}</span></div>
                                                            <hr class="form-hr">
                                                            @if (isset($bookingcode->code))
                                                                <div class="kick-back">{{ "- $ ".number_format($bookingcode->discounts, 0, ",", ".") }}</div>
                                                            @endif
                                                            @if ($promotion_price > 0)
                                                                <div class="kick-back">{{ "- $ ".number_format($promotion_price, 0, ",", ".") }}</div>
                                                            @endif
                                                            <hr class="form-hr">
                                                        @endif
                                                        <div class="price-tag"><span id="final_price">{{ number_format($final_price, 0, ",", ".") }}</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="notif-modal text-left">
                                    @lang('messages.Please make sure all the data is correct before you place an order')
                                </div>
                                
                                <input type="hidden" name="page" value="tour-detail">
                                <input type="hidden" name="action" value="Add Order">
                                
                                <input type="hidden" name="orderno" value="{{ 'ORD.' . date('Ymd', strtotime($now)) . '.TRN' . $orderno }}">
                                <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                                <input type="hidden" name="servicename" value="{{ $transport->name }}">
                                <input type="hidden" name="service" value="Transport">
                                <input type="hidden" name="service_id" value="{{ $transport->id }}">
                                <input type="hidden" name="extra_time" value="{{ $price->extra_time }}">
                                <input type="hidden" name="subservice" value="{{ $transport->type }}">
                                <input type="hidden" name="capacity" value="{{ $transport->capacity }}">
                                <input type="hidden" name="transport_type" value="{{ $transport->type }}">
                                @if (isset($transport->additional_info) or isset($price->additional_info))
                                    <input type="hidden" name="additional_info" value="{{ $transport->additional_info."<br>".$price->additional_info }}">   
                                @endif
                                <input type="hidden" name="include" value="{{ $transport->include }}">
                                <input type="hidden" name="src" value="{{ $price->src }}">
                                <input type="hidden" name="dst" value="{{ $price->dst }}">
                                <input type="hidden" name="pickup_location" value="{{ $price->src }}">
                                <input type="hidden" name="dropoff_location" value="{{ $price->dst }}">
                                <input type="hidden" name="price_total" value="{{ $normal_price }}">
                                <input type="hidden" name="price_pax" value="{{ $normal_price }}">
                                <input type="hidden" name="normal_price" value="{{ $normal_price }}">
                                <input type="hidden" name="promo_disc" value="{{ $promotion_price }}">
                                @if (isset($bookingcode->code))
                                    <input type="hidden" name="final_price" value="{{ $final_price }}">
                                    <input type="hidden" name="bookingcode" value="{{ $bookingcode->code }}">
                                    <input type="hidden" name="bookingcode_disc" value="{{ $bookingcode->discounts }}">
                                    <input type="hidden" name="bookingcode_id" value="{{ $bookingcode->id }}">
                                @else
                                    <input type="hidden" name="final_price" value="{{ $final_price }}">
                                @endif
                                @if (Auth::user()->type != "Admin")
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                                    <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                                @endif
                                <input type="hidden" name="service_type" value="{{ $price->type }}">
                                <input type="hidden" name="price_transport" value="{{ $transport->price }}">
                                <input type="hidden" name="cancellation_policy" value="{{ $transport->cancellation_policy }}">
                                <div class="card-box-footer">
                                    <button type="submit" form="create-order-transport-{{ $price->id }}" class="btn btn-primary"><i class="fa fa-shopping-basket"></i> @lang('messages.Order')</button>
                                    <button type="button" onclick="goBack()" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Cancel')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="panel/jquery/jquery.min.js"></script>
        <script src="panel/jquery/jquery.validate.min.js"></script>
        <script src="panel/script/core.js"></script>
        <script src="panel/script/script.min.js"></script>
        <script src="panel/script/process.js"></script>
        <script src="panel/script/layout-settings.js"></script>
        <script src="panel/apexcharts/apexcharts.min.js"></script>
        <script src="panel/script/dashboard.js"></script>
        <script src="panel/dropzone/dropzone.js"></script>
        <script src="panel/ckeditor/ckeditor.js"></script>
        <script src="panel/bootstrap-touchspin/jquery.bootstrap-touchspin.js"></script>
        <script src="panel/fullcalendar/fullcalendar.min.js"></script>
        <script src="vendors/scripts/calendar-setting.js"></script>
        <script src="assets/dist/pdfreader/pspdfkit.js"></script>
        <script src="panel/slick/slick.min.js"></script>
        <script src="js/sweetalert/sweetalert2.all.min.js"></script>
        <script src="js/script.js"></script>
        <script src="panel/datatables/js/responsive.bootstrap4.min.js"></script>
        <script>
            $('form').submit(function (event) {
                if ($(this).hasClass('submitted')) {
                    event.preventDefault();
                }
                else {
                    $(this).find(':submit').html('<i class="fa fa-spinner fa-spin spn"></i> @lang('messages.Loading')');
                    $(this).addClass('submitted');
                }
            });
        </script>
        <script>
            function goBack() {
            window.history.back();
            }
        </script>
    </body>
</html>