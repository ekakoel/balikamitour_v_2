@section('title','Order Hotel')
@section('content')
    @extends('layouts.head')
        <div class="mobile-menu-overlay"></div>
        <div class="main-container">
            <div class="pd-ltr-20">
                <div class="min-height-200px">
                    <div class="page-header">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="title">
                                    <i class="icon-copy fa fa-building-o"></i>
                                    {{ $orderNumber }}
                                </div>
                                <nav aria-label="breadcrumb" role="navigation">
                                    @include('partials.breadcrumbs', [
                                        'breadcrumbs' => [
                                            ['url' => route('dashboard.index'), 'label' => __('messages.Dashboard')],
                                            ['url' => route('hotels.index'), 'label' => __('messages.Hotels')],
                                            ['url' => route('hotels.detail',$hotel->code), 'label' => $hotel->name],
                                            ['label' => __('messages.Room')." ".$room->rooms],
                                            ['label' => dateFormat($checkin)." - ".dateFormat($checkout) ],
                                        ]
                                    ])
                                </nav>
                            </div>
                        </div>
                    </div>
                    @include('partials.alerts')
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="card-box">
                                <div class="card-box-title">
                                    <div class="subtitle"><i class="icon-copy fa fa-tag" aria-hidden="true"></i>@lang('messages.Create Order')</div>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-6">
                                        <div class="order-bil text-left">
                                            <img src="{{ $logoDark }}" alt="{{ $altLogo }}" loading="lazy">
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-6 flex-end">
                                        <div class="label-title">@lang('messages.Order')</div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <div class="label-date float-right" style="width: 100%">
                                            {{ ($now) }}
                                        </div>
                                    </div>
                                </div>
                                <form id="create-order" action="{{ route('order.create') }}" method="POST">
                                    @csrf
                                    <div class="business-name">{{ $business->name }}</div>
                                    <div class="bussines-sub">{{ $business->caption }}</div>
                                    <hr class="form-hr">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table tb-list nowrap">
                                                <tbody>
                                                    <tr>
                                                        <td class="htd-1">
                                                            @lang('messages.Order No')
                                                        </td>
                                                        <td class="htd-2">
                                                            {{ $orderNumber }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="htd-1">
                                                            @lang('messages.Order Date')
                                                        </td>
                                                        <td class="htd-2">
                                                            {{ $now }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="htd-1">
                                                            @lang('messages.Service')
                                                        </td>
                                                        <td class="htd-2">
                                                            {{ $service }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="htd-1">
                                                            @lang('messages.Region')
                                                        </td>
                                                        <td class="htd-2">
                                                            {{ $hotel->region }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        @canany(['posDev','posAuthor','posRsv'])
                                            @include('partials.admin-create-order', compact('agents'))
                                        @endcanany
                                    </div>
                                    <div class="page-subtitle">@lang('messages.Order Details')</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table tb-list nowrap">
                                                <tbody>
                                                    @switch($service)
                                                        @case("Hotel Promo")
                                                            <tr>
                                                                <td class="htd-1">
                                                                    @lang('messages.Hotel Promotions')
                                                                </td>
                                                                <td class="htd-2">
                                                                    @foreach ($hotelPromos as $hotel_promo)
                                                                        {{ $hotel_promo->name }}
                                                                    @endforeach
                                                                </td>
                                                            </tr>
                                                            @break
                                                        @case("Hotel Package")
                                                            <tr>
                                                                <td class="htd-1">
                                                                    @lang('messages.Hotel Package')
                                                                </td>
                                                                <td class="htd-2">
                                                                    {{ $package->name }}
                                                                </td>
                                                            </tr>
                                                            @break
                                                    @endswitch
                                                    <tr>
                                                        <td class="htd-1">
                                                            @lang('messages.Hotel Name')
                                                        </td>
                                                        <td class="htd-2">
                                                            {{ $hotel->name }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="htd-1">
                                                            @lang('messages.Room')
                                                        </td>
                                                        <td class="htd-2">
                                                            {{ $room->rooms }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table tb-list">
                                                <tbody>
                                                    <tr>
                                                        <td class="htd-1">
                                                            @lang('messages.Duration')
                                                        </td>
                                                        <td class="htd-2">
                                                            {{ $duration." ".__("messages.nights")}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="htd-1">
                                                            @lang('messages.Check In')
                                                        </td>
                                                        <td class="htd-2">
                                                            {{ $checkin }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="htd-1">
                                                            @lang('messages.Check Out')
                                                        </td>
                                                        <td class="htd-2">
                                                            {{ $checkout }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="page-note">
                                                @switch($service)
                                                    @case("Hotel")
                                                        @if ($room->include)
                                                            <b>@lang('messages.Include') :</b> <br>
                                                            {!! $room->include !!}
                                                            <hr class="form-hr">
                                                        @endif
                                                        @if (isset($room->additional_info))
                                                            <b>@lang('messages.Additional Information') :</b> <br>
                                                            {!! $room->additional_info !!}
                                                        @endif
                                                        @break
                                                    @case("Hotel Promo")
                                                        @foreach ($hotelPromos as $h_promo_b)
                                                            @if ($h_promo_b->additional_info)
                                                                <b>@lang('messages.Additional Information') :</b> <br>
                                                                {!! $h_promo_b->additional_info !!}
                                                            @endif
                                                            @if ($h_promo_b->include)
                                                                <b>@lang('messages.Include') :</b> <br>
                                                                {!! $h_promo_b->include !!}
                                                            @endif
                                                            @if ($h_promo_b->benefits)
                                                                <b>@lang('messages.Benefits') :</b> <br>
                                                                {!! $h_promo_b->benefits !!}
                                                            @endif
                                                        @endforeach
                                                        @break
                                                    @case("Hotel Package")
                                                        @if ($package->include)
                                                            <b>@lang('messages.Include') :</b> <br>
                                                            {!! $package->include !!}
                                                        @endif
                                                        @if ($package->benefits)
                                                            <b>@lang('messages.Benefit') :</b> <br>
                                                            {!! $package->benefits !!}
                                                        @endif
                                                        @if ($package->additional_info)
                                                            <b>@lang('messages.Additional Information') :</b> <br>
                                                            {!! $package->additional_info !!}
                                                        @endif
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                    @if ($hotel->cancellation_policy)
                                        <div class="page-subtitle">@lang('messages.Cancellation Policy')</div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="cancelation-policy">
                                                    {!! $hotel->cancellation_policy !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="page-subtitle">@lang('messages.Guest and Room Details')</div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ol id="dynamic_field">
                                                <li class="m-b-8">
                                                    <div class="room-container ">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="subtitle">@lang('messages.Room') 1</div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="number_of_guests[]">@lang('messages.Number of Guest')</label>
                                                                    <input id="number_of_guests[]" type="number" min="1" max="{{ $room->capacity }}" name="number_of_guests[]" class="form-control m-0 @error('number_of_guests[]') is-invalid @enderror" placeholder="@lang('messages.Number of Guest')" value="{{ old('number_of_guests[]') }}" required>
                                                                    @error('number_of_guests[]')
                                                                        <div class="alert alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <div class="form-group">
                                                                    <label for="guest_detail[]">@lang('messages.Guest Name')  <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.Children guests must include the age on the back of their name. ex: Children Name(age)')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></label>
                                                                    <input type="text" name="guest_detail[]" class="form-control m-0 @error('guest_detail[]') is-invalid @enderror" placeholder="@lang('messages.Separate names with commas')" value="{{ old('guest_detail[]') }}" required>
                                                                    @error('guest_detail[]')
                                                                        <div class="alert alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="special_day[]">@lang('messages.Special Day') <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.If during your stay there are guests who have special days such as birthdays, aniversaries, and others')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></label>
                                                                    <input type="text" name="special_day[]" class="form-control m-0 @error('special_day[]') is-invalid @enderror" placeholder="@lang('messages.ex Birthday')" value="{{ old('special_day[]') }}">
                                                                    @error('special_day[]')
                                                                        <div class="alert alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="special_date[]">@lang('messages.Insert Date for Special Day')</label>
                                                                    <input type="date" name="special_date[]" class="form-control m-0 @error('special_date[]') is-invalid @enderror" placeholder="ex: yyyy-mm-dd" value="{{ old('special_date[]') }}">
                                                                    @error('special_date[]')
                                                                        <div class="alert alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4" style="place-self: padding-bottom: 6px;">
                                                                <div class="form-group">
                                                                    <label for="extra_bed_id[]">@lang('messages.Extra Bed')<span> * </span><i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.Select an extra bed if the room is occupied by more than 2 guests')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></label><br>
                                                                    <select name="extra_bed_id[]" id="extra_bed_id[]" type="text" class="form-control @error('extra_bed_id[]') is-invalid @enderror" required>
                                                                        <option selected value="" data-ebPrice="0">@lang('messages.Select extra bed')</option>
                                                                        <option value="" data-ebPrice="0">@lang('messages.None')</option>
                                                                        @foreach ($extrabed as $eb)
                                                                            <option value="{{ $eb->id }}" data-ebprice="{{ $eb->calculatePrice($usdrates, $tax) }}">@lang('messages.'.$eb->name) @lang('messages.'.$eb->type)</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('extra_bed[]')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="checkbox-left">
                                                <input name="request_quotation" type="checkbox" style="display: block !important;" value="Yes"> 
                                                <p>
                                                    @lang('messages.Ask for quote rates for rooms more than 8 units')
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button id="add" type="button" class="btn btn-primary"><i class="icon-copy fa fa-plus-circle" aria-hidden="true"></i> @lang('messages.Add More Room')</button>
                                        </div>
                                    </div>
                                    <div class="page-subtitle">@lang('messages.Flight and Transport Detail')</div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="arrival_flight">@lang('messages.Arrival Flight')</label>
                                                <input type="text" name="arrival_flight" class="form-control @error('arrival_flight') is-invalid @enderror" placeholder="@lang('messages.Arrival Flight')" value="{{ old('arrival_flight') }}">
                                                @error('arrival_flight')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="arrival_time">@lang('messages.Arrival Date and Time')</label>
                                                <input readonly type="text" name="arrival_time" class="form-control datetimepicker @error('arrival_time') is-invalid @enderror" placeholder="@lang('messages.Select date and time')" value="{{ old('arrival_time') }}">
                                                @error('arrival_time')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="airport_shuttle_in">@lang('messages.Airport Shuttle') <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.Request')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></label>
                                                <select name="airport_shuttle_in" id="airport_shuttle_in" type="text" class="form-control @error('airport_shuttle_in') is-invalid @enderror">
                                                    <option selected value="" data-transportin="0">@lang('messages.Select Transport')</option>
                                                    @if ($transports)
                                                        @foreach ($transports as $transport_in)
                                                            <option value="{{ $transport_in->id }}" data-transportin="1">{{ $transport_in->brand." ".$transport_in->name." - (".$transport_in->capacity.")" }}</option>
                                                        @endforeach
                                                    @else
                                                        <option value="Request">@lang('messages.Request')</option>
                                                    @endif
                                                </select>
                                                @error('airport_shuttle_in')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="departure_flight">@lang('messages.Departure Flight')</label>
                                                <input type="text" name="departure_flight" class="form-control @error('departure_flight') is-invalid @enderror" placeholder="@lang('messages.Departure Flight')" value="{{ old('departure_flight') }}">
                                                @error('departure_flight')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="departure_time"> @lang('messages.Departure Date and Time')</label>
                                                <input readonly type="text" name="departure_time" class="form-control datetimepicker @error('departure_time') is-invalid @enderror" placeholder="@lang('messages.Select date and time')" value="{{ old('departure_time') }}">
                                                @error('departure_time')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="airport_shuttle_out">@lang('messages.Airport Shuttle') <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.Request')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></label>
                                                <select name="airport_shuttle_out" id="airport_shuttle_out" type="text" class="form-control @error('airport_shuttle_out') is-invalid @enderror">
                                                    <option selected value="" data-transportout="0">@lang('messages.Select Transport')</option>
                                                    @if ($transports)
                                                        @foreach ($transports as $transport_out)
                                                            <option value="{{ $transport_out->id }}" data-transportout="1">{{ $transport_out->brand." ".$transport_out->name." - (".$transport_out->capacity.")" }}</option>
                                                        @endforeach
                                                    @else
                                                        <option value="Request">@lang('messages.Request')</option>
                                                    @endif
                                                </select>
                                                @error('airport_shuttle_out')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="page-subtitle">@lang('messages.Remark')</div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
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
                                        <div class="col-md-12">
                                            <div id="optional_service" class="page-subtitle">@lang('messages.Price')</div>
                                        </div>
                                        <div class="col-md-12 m-b-8">
                                            <div class="box-price-kicked">
                                                <div class="row">
                                                    <div class="col-6 col-md-6">
                                                        @if ($service == "Hotel")
                                                            @if ($kick_back > 0 or $promotion_price > 0)
                                                                <div class="promo-text">@lang('messages.Normal Price')</div>
                                                                <div id="extraBedText" class="promo-text">@lang('messages.Extra Bed')</div>
                                                                <hr class="form-hr">
                                                                @if ($kick_back > 0)
                                                                    <div class="promo-text">@lang('messages.Kick Back')</div>
                                                                @endif
                                                                @if ($promotion_price > 0)
                                                                    <div class="promo-text">@lang('messages.Promotion')</div>
                                                                @endif
                                                                <hr class="form-hr">
                                                                <div class="total-price">@lang('messages.Total Price')</div>
                                                            @else
                                                                <div class="total-price">@lang('messages.Total Price')</div>
                                                            @endif
                                                        @else
                                                            <div class="total-price">@lang('messages.Total Price')</div>
                                                        @endif
                                                    </div>
                                                    <div class="col-6 col-md-6 text-right">
                                                        @if ($service == "Hotel")
                                                            @if ($kick_back > 0 or $promotion_price > 0)
                                                                <div class="text-price line-trought"><span id='total_normal_price'>{{  number_format(($normal_price), 0, ",", ".")  }}</span></div>
                                                                <div id="extraBedPrice" class="text-price"><span id='extraBedPriceTotal'></span></div>
                                                                <hr class="form-hr">
                                                                @if ($kick_back > 0)
                                                                    <div class="kick-back"><span id="total_kick_back">{{ number_format($kick_back, 0, ",", ".") }}</span></div>
                                                                @endif
                                                                @if ($promotion_price > 0)
                                                                    <div class="kick-back"><span id="promotionPrice">{{ number_format($promotion_price, 0, ",", ".") }}</span></div>
                                                                @endif
                                                                <hr class="form-hr">
                                                                <div class="total-price">
                                                                    <span id="finalprice">{{ number_format($f_price, 0, ",", ".") }}</span>
                                                                </div>
                                                            @else
                                                                <div class="total-price"><span id="finalprice">{{ number_format($final_price , 0, ",", ".") }}</span></div>
                                                            @endif
                                                        @else
                                                            <div class="total-price"><span id="finalprice">{{ number_format($final_price , 0, ",", ".") }}</span></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="notif-modal text-left">
                                        @lang('messages.Please make sure all the data is correct before you make an order')
                                    </div>
                                    @if ($service == "Hotel")
                                        <input id="services" type="hidden" name="service" value="Hotel">
                                        <input type="hidden" name="include" value="{{ $room->include }}">
                                        <input type="hidden" name="additional_info" value="{{ $room->additional_info }}">
                                    @elseif ($service == "Hotel Promo")
                                        <input type="hidden" name="promo_id" value="{{ $promo_id }}">
                                        <input id="services" type="hidden" name="service" value="Hotel Promo">
                                        <input type="hidden" name="hotelPromos" value="{{ $hotelPromos }}">
                                        {{-- <input type="hidden" name="booking_code" value="{{ $p_booking_code }}"> --}}
                                        
                                        {{-- <input type="hidden" name="book_period_start" value="{{ $p_book_periode_start }}">
                                        <input type="hidden" name="book_period_end" value="{{ $p_book_periode_end }}">
                                        <input type="hidden" name="include" value="{{ $p_include }}">
                                        <input type="hidden" name="period_start" value="{{ $p_periode_start }}">
                                        <input type="hidden" name="period_end" value="{{ $p_periode_end }}">
                                        
                                        <input type="hidden" name="benefits" value="{{ $p_benefits }}">
                                        <input type="hidden" name="additional_info" value="{{ $p_ai }}"> --}}

                                    @elseif ($service == "Hotel Package")
                                        <input type="hidden" name="period_start" value="{{ date('Y-m-d',strtotime($package->stay_period_start)) }}">
                                        <input type="hidden" name="period_end" value="{{ date('Y-m-d',strtotime($package->stay_period_end)) }}">
                                        <input type="hidden" name="booking_code" value="{{ $package->booking_code }}">
                                        <input id="services" type="hidden" name="service" value="Hotel Package">
                                        <input type="hidden" name="package_name" value="{{ $package->name }}">
                                        <input type="hidden" name="benefits" value="{{ $package->benefits }}">
                                        <input type="hidden" name="include" value="{{  $package->include  }}">
                                        <input type="hidden" name="additional_info" value="{{ $package->additional_info }}">
                                    @endif
                                    <input type="hidden" name="orderno" value="{{ $orderNumber }}">
                                    <input type="hidden" name="page" value="hotel-detail">
                                    <input type="hidden" name="action" value="Create Order">
                                    <input type="hidden" name="sales_agent" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="servicename" value="{{ $hotel->name }}">
                                    <input type="hidden" name="service_id" value="{{ $hotel->id }}">
                                    <input type="hidden" name="subservice" value="{{ $room->rooms }}">
                                    <input type="hidden" name="subservice_id" value="{{ $room->id }}">
                                    <input type="hidden" name="duration" id="duration" value="{{ $duration }}">
                                    <input type="hidden" name="capacity" value="{{ $room->capacity }}">
                                    <input type="hidden" name="checkin" value="{{ $checkin }}">
                                    <input type="hidden" name="checkout" value="{{ $checkout }}">
                                    <input type="hidden" name="cancellation_policy" value="{{ $hotel->cancellation_policy }}">
                                    <input type="hidden" name="location" value="{{ $hotel->region }}">
                                    @if (isset($promotions))
                                        <input type="hidden" name="promotion_price" id='var_promotion_price' value="{{ $promotion_price }}">
                                    @endif
                                    @if (Auth::user()->type != "Admin")
                                        <input type="hidden" name="sales_agent" value="{{ Auth::user()->id }}">
                                    @endif
                                    <input type="hidden" name="normal_price" value="{{ $normal_price }}">
                                    <input type="hidden" name="total_normal_price" id="total_normal_price" value="{{ $normal_price }}">
                                    <input type="hidden" name="kick_back" id="kick_back" value="{{ $kick_back }}">
                                    <input type="hidden" name="kick_back_per_pax" value="{{ $kick_back_per_pax }}">
                                    <input type="hidden" name="var_kick_back" id='var_kick_back' value="{{ $kick_back }}">
                                    <input type="hidden" name="var_normal_price" id='var_normal_price' value="{{ $normal_price }}">
                                    <input type="hidden" name="var_price_pax" id='var_price_pax' value="{{ $price_pax }}">
                                    <div class="card-box-footer">
                                        <button type="submit" form="create-order" id="normal-reserve" class="btn btn-primary"><i class="icon-copy fa fa-shopping-basket" aria-hidden="true"></i> @lang('messages.Order')</button>
                                        <button type="button" onclick="goBack()" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Cancel')</button>
                                    </div>
                                </Form>
                            </div>
                        </div>
                    </div>
                @include('layouts.footer')
            </div>
        </div>
        <script>
            function goBack() {
              window.history.back();
            }
        </script>

        <script type="text/javascript">
            $(document).ready(function () {
                var maxField = 8;
                var duration = parseInt($('#duration').val()) || 0;
                var roomPrice = parseInt($('#var_price_pax').val()) || 0;
                var kickback = parseInt($('#var_kick_back').val()) || 0;
                var normalPrice = parseInt($('#var_normal_price').val()) || 0;
                var bcodeDiscounts = parseInt($('#var_bcode_discounts').val()) || 0;
                var promotionPrice = parseInt($('#var_promotion_price').val()) || 0;
                var orderService = $('#services').val() || "Other";
                var roomCount = 1;
                var roomId = 1;

                function toggleExtraBedSelection() {
                    $('input[name="number_of_guests[]"]').each(function () {
                        var numberOfGuests = parseInt($(this).val()) || 0;  
                        var extraBedSelect = $(this).closest('div').next('div').find('select[name="extra_bed_id[]"]');

                        if (numberOfGuests > 2) {
                            extraBedSelect.prop('disabled', false);
                        } else {
                            extraBedSelect.prop('disabled', true);
                            extraBedSelect.val("");
                        }
                    });
                }

                function calculatePrices() {
                    var totalNormalPrice = normalPrice * roomCount;
                    var finalPrice = totalNormalPrice;
                    var totalExtraBedPrice = 0;
                    $("select[name='extra_bed_id[]']").each(function () {
                        var selectedOption = $(this).find(":selected");
                        var ebPrice = parseInt(selectedOption.data('ebprice')) || 0;
                        var extraBedPrice = ebPrice * duration;
                        totalExtraBedPrice += extraBedPrice;
                    });

                    $("select[name='airport_shuttle_in']").each(function () {
                        var transportIn = $(this).find(":selected");
                        var airportShuttleIn = parseInt(transportIn.data('transportin')) || 0;
                    });
                    $("select[name='airport_shuttle_out']").each(function () {
                        var transportOut = $(this).find(":selected");
                        var airportShuttleOut = parseInt(transportOut.data('transportout')) || 0;
                    });

                    finalPrice += totalExtraBedPrice;
                    if (orderService === "Hotel") {
                        if (kickback > 0) {
                            var totalKickback = kickback * roomCount;
                            $('#kick_back').val(totalKickback);
                            $("#total_kick_back").text(totalKickback);
                            finalPrice -= totalKickback;
                        }
                        if (bcodeDiscounts > 0) {
                            finalPrice -= bcodeDiscounts;
                        }
                        if (promotionPrice > 0) {
                            finalPrice -= promotionPrice;
                            $("#promotionPrice").text(promotionPrice);
                        }
                    } else {
                        if (bcodeDiscounts > 0) {
                            finalPrice -= bcodeDiscounts;
                        }
                    }
                    $('#normal_price').val(totalNormalPrice);
                    $('#finalprice').val(finalPrice);
                    $("#total_normal_price").text(totalNormalPrice);
                    $("#finalprice").text(finalPrice);
                    if (totalExtraBedPrice > 0) {
                        document.getElementById('extraBedPrice').hidden = false;
                        document.getElementById('extraBedText').hidden = false;
                        $("#extraBedPriceTotal").text(totalExtraBedPrice);
                    }else{
                        document.getElementById('extraBedPrice').hidden = true;
                        document.getElementById('extraBedText').hidden = true;
                        $("#extraBedPriceTotal").text(totalExtraBedPrice);
                    }
                }
                $('input[name="number_of_guests[]"]').on('input', function () {
                    toggleExtraBedSelection();
                });

                $(document).on('change', 'select[name="extra_bed_id[]"]', function () {
                    calculatePrices();
                });
                
                $('#add').on('click', function () {
                    if (roomCount < maxField) {
                        roomCount++;
                        roomId++;
                        var newRoom = `
                            <li id="li${roomId}" class="m-b-8">
                                <div class="room-container">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="subtitle">@lang('messages.Room') ${roomId}</div>
                                            <button class="btn btn-remove" name="remove" id="${roomId}" type="button">
                                                <i class="icon-copy fa fa-close" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="number_of_guests[]">@lang('messages.Number of Guest')</label>
                                                <input id="number_of_guests[]" type="number" min="1" max="{{ $room->capacity }}" name="number_of_guests[]" class="form-control m-0 @error('number_of_guests[]') is-invalid @enderror" placeholder="@lang('messages.Number of Guest')" value="{{ old('number_of_guests[]') }}" required>
                                                @error('number_of_guests[]')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <label for="guest_detail[]">@lang('messages.Guest Name')  <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.Children guests must include the age on the back of their name. ex: Children Name(age)')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></label>
                                                <input type="text" name="guest_detail[]" class="form-control m-0 @error('guest_detail[]') is-invalid @enderror" placeholder="@lang('messages.Separate names with commas')" value="{{ old('guest_detail[]') }}" required>
                                                @error('guest_detail[]')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="special_day[]">@lang('messages.Special Day') <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.If during your stay there are guests who have special days such as birthdays, aniversaries, and others')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></label>
                                                <input type="text" name="special_day[]" class="form-control m-0 @error('special_day[]') is-invalid @enderror" placeholder="@lang('messages.ex Birthday')" value="{{ old('special_day[]') }}">
                                                @error('special_day[]')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="special_date[]">@lang('messages.Insert Date for Special Day')</label>
                                                <input type="date" name="special_date[]" class="form-control m-0 @error('special_date[]') is-invalid @enderror" placeholder="ex: yyyy-mm-dd" value="{{ old('special_date[]') }}">
                                                @error('special_date[]')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4" style="place-self: padding-bottom: 6px;">
                                            <div class="form-group">
                                                <label for="extra_bed_id[]">@lang('messages.Extra Bed')<span> * </span><i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.Select an extra bed if the room is occupied by more than 2 guests')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></label><br>
                                                <select name="extra_bed_id[]" id="extra_bed_id[]" type="text" class="form-control @error('extra_bed_id[]') is-invalid @enderror" required>
                                                    <option selected value="" data-ebPrice="0">@lang('messages.Select extra bed')</option>
                                                    <option value="" data-ebPrice="0">@lang('messages.None')</option>
                                                    @foreach ($extrabed as $eb)
                                                        <option value="{{ $eb->id }}" data-ebprice="{{ $eb->calculatePrice($usdrates, $tax) }}">@lang('messages.'.$eb->name) @lang('messages.'.$eb->type)</option>
                                                    @endforeach
                                                </select>
                                                @error('extra_bed[]')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>`;

                        $('#dynamic_field').append(newRoom);
                        calculatePrices();
                    }
                });
                $(document).on('click', '.btn-remove', function () {
                    var buttonId = $(this).attr("id");
                    $('#li' + buttonId).remove();
                    roomCount--;
                    calculatePrices();
                });
                calculatePrices();
            });

        </script>
@endsection