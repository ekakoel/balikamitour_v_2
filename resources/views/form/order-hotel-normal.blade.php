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
                                            {{ dateFormat($now) }}
                                        </div>
                                    </div>
                                </div>
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
                                                        {{ dateTimeFormat($now) }}
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
                                                    <tr>
                                                        <td class="htd-1">
                                                            @lang('messages.Hotel Promotions')
                                                        </td>
                                                        <td class="htd-2">
                                                            {{ $promo_name }}
                                                        </td>
                                                    </tr>
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
                                                            {{ dateFormat($checkin) }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="htd-1">
                                                            @lang('messages.Check Out')
                                                        </td>
                                                        <td class="htd-2">
                                                            {{ dateFormat($checkout) }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                        </div>
                                        @if ($promo_benefits || $promo_include || $promo_additional_info)
                                            <div class="col-md-12">
                                                <div class="page-note">
                                                    @if ($promo_benefits)
                                                        <b>@lang('messages.Benefits') :</b>
                                                        {!! $promo_benefits !!}
                                                    @endif
                                                    @if ($promo_include)
                                                        <b>@lang('messages.Include') :</b>
                                                        {!! $promo_include !!}
                                                    @endif
                                                    @if ($promo_additional_info)
                                                        <b>@lang('messages.Additional Info') :</b>
                                                        {!! $promo_additional_info !!}
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        {{-- @if ($hotel->cancellation_policy)
                                            <div class="col-md-12">
                                                <div class="page-note">
                                                    <b>@lang('messages.Cancellation Policy') :</b>
                                                    {!! $hotel->cancellation_policy !!}
                                                </div>
                                            </div>
                                        @endif --}}
                                    </div>
                                    <div class="page-subtitle">@lang('messages.Guest and Room Details')</div>
                                    <form id="create-order" action="{{ route('order.create') }}" method="POST">
                                        @csrf
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
                                                                            @foreach ($hotel->extrabeds as $eb)
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
                                                    <select name="airport_shuttle_in" id="airportShuttleIn" type="text" class="form-control @error('airport_shuttle_in') is-invalid @enderror">
                                                        <option selected value="" data-transportin="0">@lang('messages.Select Transport')</option>
                                                        {{-- @if ($transports)
                                                            @foreach ($transports as $transport_in)
                                                                <option value="{{ $transport_in->id }}" data-transportin="1">{{ $transport_in->brand." ".$transport_in->name." - (".$transport_in->capacity.")" }}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="Request">@lang('messages.Request')</option>
                                                        @endif --}}
                                                        @if ($transports)
                                                            @foreach ($transports as $transport)
                                                                @php
                                                                    $transport_out = $transport_prices->where('transports_id',$transport->id)->first();
                                                                @endphp
                                                                @if ($transport_out)
                                                                    <option value="{{ $transport->id }}" data-transportin=1 data-transporpricein={{ $transport_out->calculatePrice($usdrates, $tax) }}>{{ $transport_out->transport->brand." ".$transport_out->transport->name." - (".$transport_out->transport->capacity.")" }}</option>
                                                                @else
                                                                    <option value="{{ $transport->id }}" data-transportin=1 data-transporpricein=0>{{ $transport->brand." ".$transport->name." - (".$transport->capacity.")" }}</option>
                                                                @endif
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
                                                    <select name="airport_shuttle_out" id="airportShuttleOut" type="text" class="form-control @error('airport_shuttle_out') is-invalid @enderror">
                                                        <option selected value="" data-transportout="0" data-transporpriceout=0>@lang('messages.Select Transport')</option>
                                                        @if ($transports)
                                                            @foreach ($transports as $transport)
                                                                @php
                                                                    $transport_out = $transport_prices->where('transports_id',$transport->id)->first();
                                                                @endphp
                                                                @if ($transport_out)
                                                                    <option value="{{ $transport->id }}" data-transportout=1 data-transporpriceout={{ $transport_out->calculatePrice($usdrates, $tax) }}>{{ $transport_out->transport->brand." ".$transport_out->transport->name." - (".$transport_out->transport->capacity.")" }}</option>
                                                                @else
                                                                    <option value="{{ $transport->id }}" data-transportout=1 data-transporpriceout=0>{{ $transport->brand." ".$transport->name." - (".$transport->capacity.")" }}</option>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <option value="Request" data-transportout="1">@lang('messages.Request')</option>
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
                                                    <textarea id="note" name="note" placeholder="@lang('messages.Optional')" class="tiny_mce form-control border-radius-0" value="{{ old('note') }}"></textarea>
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
                                                            <div id="airportShuttleText" class="normal-text">@lang('messages.Airport Shuttle')</div>
                                                            @if ($promotion_price > 0)
                                                                <div class="normal-text">@lang('messages.Suites and Villas')</div>
                                                                <div id="extraBedText" class="normal-text">@lang('messages.Extra Bed')</div>
                                                                <hr class="form-hr">
                                                                <div class="promo-text">@lang('messages.Promotion') <i>(@lang('messages.Discounts'))</i></div>
                                                                <hr class="form-hr">
                                                                <div class="total-price">@lang('messages.Total Price')</div>
                                                            @else
                                                                <div class="total-price">@lang('messages.Total Price')</div>
                                                            @endif
                                                        </div>
                                                        <div class="col-6 col-md-6 text-right">
                                                            <div id="airportShuttlePrice" class="text-price"><span id="airportShuttlePriceText"></span></div>
                                                            @if ($promotion_price > 0)
                                                                <div class="text-price"><span id="promoNormalPrice">{{ number_format($promo_price, 0, ".", ",") }}</span></div>
                                                                <div id="extraBedPrice" class="text-price"><span id='extraBedPriceTotal'>{{ number_format($promo_price, 0, ".", ",") }}</span></div>
                                                                <hr class="form-hr">
                                                                <div class="kick-back"><span id="promotionPrice">{{ number_format($promotion_price, 0, ".", ",") }}</span></div>
                                                                <hr class="form-hr">
                                                                <div class="total-price">
                                                                    <span id="finalprice">{{ number_format($final_price - $promotion_price, 0, ".", ",") }}</span>
                                                                </div>
                                                            @else
                                                                <div class="total-price"><span id="finalprice">{{ number_format($final_price , 0, ".", ",") }}</span></div>
                                                            @endif
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="duration" id="duration" value="{{ $duration }}">
                                        <input type="hidden" name="final_price" id="final_price" value="{{ $final_price }}">
                                        <input type="hidden" name="var_promo_price" id='var_promo_price' value="{{ $promo_price }}">
                                        <input type="hidden" name="promotion_price" id='var_promotion_price' value="{{ $promotion_price }}">
                                        <div class="card-box-footer">
                                            <button type="submit" form="create-order" id="normal-reserve" class="btn btn-primary"><i class="icon-copy fa fa-shopping-basket" aria-hidden="true"></i> @lang('messages.Order')</button>
                                            <button type="button" onclick="goBack()" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Cancel')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                @include('layouts.footer')
            </div>
        </div>
        

        <script type="text/javascript">
            function goBack() {
              window.history.back();
            }
            
            $(document).ready(function () {
                $(".kick-back").each(function() {
                    var price = parseFloat($(this).text());
                    if (!isNaN(price)) {
                        var formattedPrice = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(price);
                        $(this).text(formattedPrice);
                    }
                });
                var maxField = 8;
                var promotion_price = parseInt($('#var_promotion_price').val()) || 0;
                var duration = parseInt($('#duration').val()) || 0;
                var roomPrice = parseInt($('#var_price_pax').val()) || 0;
                var promoPrice = parseInt($('#var_promo_price').val()) || 0;
                var orderService = $('#services').val() || "Other";
                var roomCount = 1;
                var roomId = 1;
                var aiportStatusIn = 0;
                var transportPriceIn = 0;
                var transportPriceOut = 0;
                var aiportStatusOut = 0;

                
                
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
                    var totalPromoPrice = promoPrice * roomCount;
                    var finalPrice = totalPromoPrice;
                    var totalExtraBedPrice = 0;
                    var promoNormalPrice = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(totalPromoPrice);
                    $("select[name='extra_bed_id[]']").each(function () {
                        var selectedOption = $(this).find(":selected");
                        var ebPrice = parseInt(selectedOption.data('ebprice')) || 0;
                        var extraBedPrice = ebPrice * duration;
                        totalExtraBedPrice += extraBedPrice;
                    });

                    $("select[name='airport_shuttle_in']").each(function () {
                        var airportSelectedOption = $(this).find(":selected");
                        var inStatus = parseInt(airportSelectedOption.data('transportin')) || 0;
                        var inPrice = parseInt(airportSelectedOption.data('transporpricein')) || 0;
                        aiportStatusIn = inStatus;
                        transportPriceIn = inPrice;
                    });
                    $("select[name='airport_shuttle_out']").each(function () {
                        var airportSelectedOption = $(this).find(":selected");
                        var outStatus = parseInt(airportSelectedOption.data('transportout')) || 0;
                        var outPrice = parseInt(airportSelectedOption.data('transporpriceout')) || 0;
                        aiportStatusOut = outStatus;
                        transportPriceOut = outPrice;
                    });

                    finalPrice += totalExtraBedPrice;
                    finalPrice += transportPriceIn + transportPriceOut;
                    if (promotion_price > 0) {
                        finalPrice -= promotion_price;
                        $("#promotionPrice").text(promotion_price);
                        $("#promoNormalPrice").text(promoNormalPrice);
                    }
                    
                    $('#promo_price').val(totalPromoPrice);
                    $('#final_price').val(finalPrice);
                    $("#total_promo_price").text(totalPromoPrice);

                    // Baru Sampai disini
                    // if (aiportStatusIn == 1 || aiportStatusOut == 1) {
                    if (aiportStatusIn == 1 || aiportStatusOut == 1) {
                        if (aiportStatusIn == 1 && $aiportStatusOut == 1 ) {
                            if (transportPriceIn > 0 ) {
                                finalPrice =  new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(finalPrice);
                                $("#finalprice").text(finalPrice);
                            }else{
                                $("#finalprice").text("@lang('messages.To be advised')");
                            }
                        }else{
                            $("#finalprice").text("@lang('messages.To be advised')");
                        }
                        document.getElementById('airportShuttlePrice').hidden = false;
                        document.getElementById('airportShuttleText').hidden = false;
                    }else{
                        finalPrice =  new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(finalPrice);
                        $("#finalprice").text(finalPrice);
                        document.getElementById('airportShuttlePrice').hidden = true;
                        document.getElementById('airportShuttleText').hidden = true;
                    }

                    if (totalExtraBedPrice > 0) {
                        totalExtraBedPriceFormated =  new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(totalExtraBedPrice);
                        document.getElementById('extraBedPrice').hidden = false;
                        document.getElementById('extraBedText').hidden = false;
                        $("#extraBedPriceTotal").text(totalExtraBedPriceFormated);
                    }else{
                        document.getElementById('extraBedPrice').hidden = true;
                        document.getElementById('extraBedText').hidden = true;
                        $("#extraBedPriceTotal").text(totalExtraBedPrice);
                    }
                }
                
                
                $("#airportShuttleIn").change(function() {
                    var selectedValueIn = $(this).val();
                    var transportOptionIn = $(this).find("option:selected");
                    var aiportStatusIn = transportOptionIn.data("transportin");
                    if (aiportStatusIn == 1) {
                        $("#airportShuttlePrice").text("@lang('messages.To be advised')");
                        aiportStatusIn = aiportStatusIn;
                    } else {
                        aiportStatusIn = 0;
                    }
                    calculatePrices();
                });

                $("#airportShuttleOut").change(function() {
                    var selectedValueOut = $(this).val();
                    var transportOptionOut = $(this).find("option:selected");
                    var aiportStatusOut = transportOptionOut.data("transportout");
                    if (aiportStatusOut == 1) {
                        $("#airportShuttlePrice").text("@lang('messages.To be advised')");
                        aiportStatusOut = aiportStatusOut;
                    } else {
                        aiportStatusOut = 0;
                    }
                    calculatePrices();
                });


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
                                                    @foreach ($hotel->extrabeds as $eb)
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