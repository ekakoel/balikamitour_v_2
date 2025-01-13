@php
    use Carbon\Carbon;
    if ($order->service == "Hotel" or $order->service == "Hotel Package" or $order->service == "Hotel Promo") {
        $nor = $order->number_of_room;
        $nogr = json_decode($order->number_of_guests_room);
        $guest_detail = json_decode($order->guest_detail);
        $special_day = json_decode($order->special_day);
        $special_date = json_decode($order->special_date);
        $extra_bed = json_decode($order->extra_bed);
        $extra_bed_id = json_decode($order->extra_bed_id);
        $extra_bed_price = json_decode($order->extra_bed_price);
        $r=1;
        $room_price_normal = $order->price_pax + ($order->kick_back / $order->number_of_room);
        if (isset($extra_bed_price)) {
            $totalextrabed = array_sum($extra_bed_price);
        }else{
            $totalextrabed = 0;
        }
        $tp_room_and_suite = ($room_price_normal * $order->number_of_room) + $totalextrabed;
        if ($nor != "" or $order->number_of_guests < 1) {
            $optional_service_total_price = 0;
        }else{
            $optional_service_total_price = 0;
        }
    }else{
        $optional_service_total_price = 0;
    }
    
@endphp
@if ($order->status == "Approved" or $order->status == "Paid")
    <div class="col-md-4 mobile">
        <div class="card-box">
            <div class="card-box-title">
                <div class="subtitle"><i class="icon-copy fa fa-money" aria-hidden="true"></i> @lang('messages.Payment Status')</div>
            </div>
            @if ($invoice->due_date > $now)
                @if (isset($receipt))
                    @if ($receipt->status == "Paid")
                        <div class="pmt-container">
                            <i class="icon-copy fa fa-check-circle" aria-hidden="true"></i>
                            <div class="pmt-status">
                                @lang('messages.Paid')
                            </div>
                        </div>
                        <div class="pmt-des">
                            <b>{{ $invoice->inv_no }}</b>
                            <p>@lang('messages.Paid on') : {{ dateFormat($receipt->payment_date) }}<br>
                        </div>
                        <div class="view-receipt">
                            <a class="action-btn" href="modal" data-toggle="modal" data-target="#mobile-paid-receipt-{{ $receipt->id }}">
                                <i class="icon-copy fa fa-eye" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="modal fade" id="mobile-paid-receipt-{{ $receipt->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content modal-img">
                                    <div class="card-box">
                                        <div class="card-box-title">
                                            <div class="title"><i class="icon-copy fa fa-file-photo-o" aria-hidden="true"></i> @lang('messages.Payment Receipt')</div>
                                        </div>
                                        <img style="height: 630px;" src="{{ asset('storage/receipt/'.$receipt->receipt_img) }}" alt="">
                                        <div class="card-box-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($receipt->status == "Pending")
                        <div class="pmt-container pending">
                            <i class="icon-copy fa fa-clock-o" aria-hidden="true"></i>
                            <div class="pmt-status">
                                @lang('messages.On Review')
                            </div>
                        </div>
                        <div class="pmt-des">
                            <b>{{ $invoice->inv_no }}</b>
                            <p>@lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                            <p>@lang('messages.Payment Confirmation') : {{ dateFormat($receipt->created_at) }}</p>
                            
                        </div>
                    @elseif($receipt->status == "Invalid")
                        <div class="pmt-container unpaid">
                            <i class="icon-copy fa fa-window-close" aria-hidden="true"></i>
                            <div class="pmt-status">
                                @lang('messages.Invalid')
                            </div>
                        </div>
                        <div class="pmt-des">
                            <p><i style="color: red">{{ $receipt->note }}</i></p>
                            <b>{{ $invoice->inv_no }}</b>
                            <p>@lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                        </div>
                        <div class="view-receipt">
                            <a class="action-btn" href="modal" data-toggle="modal" data-target="#mobile-invalid-receipt-{{ $receipt->id }}">
                                <i class="icon-copy fa fa-eye" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="modal fade" id="mobile-invalid-receipt-{{ $receipt->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content modal-img">
                                    <div class="card-box">
                                        <div class="card-box-title">
                                            <div class="title"><i class="icon-copy fa fa-file-photo-o" aria-hidden="true"></i> @lang('messages.Payment Receipt')</div>
                                        </div>
                                        <img style="height: 630px;" src="{{ asset('storage/receipt/'.$receipt->receipt_img) }}" alt="">
                                        <div class="notification-text" style="margin-top: 8px; color:rgb(143, 0, 0);">
                                            {!! $receipt->note !!}
                                        </div>
                                        <div class="card-box-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="pmt-container unpaid">
                            <i class="icon-copy fa fa-window-close" aria-hidden="true"></i>
                            <div class="pmt-status">
                                @lang('messages.Unpaid')
                            </div>
                        </div>
                        <div class="pmt-des">
                            <b>{{ $invoice->inv_no }}</b>
                            <p>@lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                        </div>
                        <div class="view-receipt">
                            <a class="action-btn" href="modal" data-toggle="modal" data-target="#mobile-payment-receipt-{{ $receipt->id }}">
                                <i class="icon-copy fa fa-eye" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="modal fade" id="mobile-payment-receipt-{{ $receipt->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content modal-img">
                                    <div class="card-box">
                                        <div class="card-box-title">
                                            <div class="title"><i class="icon-copy fa fa-file-photo-o" aria-hidden="true"></i> @lang('messages.Payment Receipt')</div>
                                        </div>
                                        <img style="height: 630px;" src="{{ asset('storage/receipt/'.$receipt->receipt_img) }}" alt="">
                                        <div class="notification-text" style="margin-top: 8px; color:rgb(143, 0, 0);">
                                            {!! $receipt->note !!}
                                        </div>
                                        <div class="card-box-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="pmt-container pending">
                        <i class="icon-copy fa fa-hourglass" aria-hidden="true"></i>
                        <div class="pmt-status">
                            @lang('messages.Awaiting Payment')
                        </div>
                    </div>
                    <div class="pmt-des">
                        <b>{{ $invoice->inv_no }}</b>
                        <p>@lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                    </div>
                @endif
            @else
                @if (isset($receipt))
                    @if ($receipt->status == "Paid")
                        <div class="pmt-container">
                            <i class="icon-copy fa fa-check-circle" aria-hidden="true"></i>
                            <div class="pmt-status">
                                @lang('messages.Paid')
                            </div>
                        </div>
                        <div class="pmt-des">
                            <b>{{ $order->orderno." - ". $invoice->inv_no }}</b>
                            <p>@lang('messages.Paid on') : {{ dateFormat($receipt->payment_date) }}<br>
                            @lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                            
                        </div>
                        <div class="view-receipt">
                            <a class="action-btn" href="modal" data-toggle="modal" data-target="#mobile-receipt-{{ $receipt->id }}">
                                <i class="icon-copy fa fa-eye" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="modal fade" id="mobile-receipt-{{ $receipt->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content modal-img">
                                    <div class="card-box">
                                        <div class="card-box-title">
                                            <div class="title"><i class="icon-copy fa fa-file-photo-o" aria-hidden="true"></i> @lang('messages.Payment Receipt')</div>
                                        </div>
                                        <img style="height: 630px;" src="{{ asset('storage/receipt/'.$receipt->receipt_img) }}" alt="">
                                        <div class="card-box-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="pmt-container unpaid">
                            <i class="icon-copy fa fa-window-close" aria-hidden="true"></i>
                            <div class="pmt-status">
                                @lang('messages.Invalid')
                            </div>
                        </div>
                        <div class="pmt-des">
                            <b>{{ $invoice->inv_no }}</b>
                            <p>@lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                        </div>
                    @endif
                @else
                    <div class="pmt-container unpaid">
                        <i class="icon-copy fa fa-window-close" aria-hidden="true"></i>
                        <div class="pmt-status">
                            @lang('messages.Unpaid')
                        </div>
                    </div>
                    <div class="pmt-des">
                        <b>{{ $invoice->inv_no }}</b>
                        <p>@lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                    </div>
                {{-- <img src="{{ asset('storage/receipt/paid.png') }}" alt="receipt_paid"> "\f058"--}}
                @endif
            @endif
        </div>
    </div>
@endif
<div class="col-md-8">
    <div class="card-box">
        <div class="card-box-title">
            <div class="subtitle"><i class="fa fa-eye"></i>@lang('messages.Detail Order')</div>
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
                    {{ dateFormat($order->created_at) }}
                </div>
            </div>
        </div>
        <div class="business-name">{{ $business->name }}</div>
        <div class="bussines-sub">{{ $business->caption }}</div>
        <hr class="form-hr">
        <div class="row">
            <div class="col-sm-6">
                <table class="table tb-list">
                    <tr>
                        <td class="htd-1">@lang('messages.Order No') </td>
                        <td class="htd-2"><b>{{ $order->orderno }}</b></td>
                    </tr>
                    <tr>
                        <td class="htd-1">@lang('messages.Order Date') </td>
                        <td class="htd-2">{{ dateFormat($order->created_at) }}</td>
                    </tr>
                    <tr>
                        <td class="htd-1">@lang('messages.Service') </td>
                        <td class="htd-2">
                            @if ($order->service == "Hotel")
                                @lang('messages.Hotel')
                            @elseif ($order->service == "Hotel Promo")
                               @lang('messages.Hotel Promo')
                            @elseif ($order->service == "Hotel Package")
                                @lang('messages.Hotel Package')
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="htd-1">@lang('messages.Region') </td>
                        <td class="htd-2">{{ $order->location }}</td>
                    </tr>
                    @if ($order->status == "Confirmed")
                        <tr>
                            <td class="htd-1">
                                @lang('messages.Reconfirm Date')
                            </td>
                            <td class="htd-2">
                                {{ dateFormat($invoice->due_date) }}
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="col-sm-6 text-right">
                @if ($order->status == "Active")
                    <div class="page-status order-status-active"> @lang('messages.Confirmed') <span>@lang('messages.Status'):</span></div>
                @elseif ($order->status == "Pending")
                    <div class="page-status order-status-pending">@lang('messages.'.$order->status) <span>@lang('messages.Status'):</span></div>
                @elseif ($order->status == "Rejected")
                    <div class="page-status order-status-rejected">@lang('messages.'.$order->status) <span>@lang('messages.Status'):</span></div>
                @elseif ($order->status == "Approved")
                    <div class="page-status order-status-approve">@lang('messages.'.$order->status) <span>@lang('messages.Status'):</span></div>
                @elseif ($order->status == "Confirmed")
                    <div class="page-status order-status-confirm">@lang('messages.'.$order->status) <span>@lang('messages.Status'):</span></div>
                @elseif ($order->status == "Paid")
                    <div class="page-status order-status-paid">@lang('messages.'.$order->status) <span>@lang('messages.Status'):</span></div>
                @else
                    <div class="page-status" style="color: rgb(48, 48, 48)">@lang('messages.'.$order->status) <span>@lang('messages.Status'):</span></div>
                @endif
            </div>
        </div>
        @if (isset($order->arrival_flight)  or isset($order->arrival_time) or isset($order->departure_flight) or isset($order->departure_time))
            <div class="page-subtitle m-b-8">@lang('messages.Flight Detail')</div>
                <div class="card-ptext-margin">
                
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table tb-list" >
                                <tr>
                                    <td class="htd-1">
                                        @lang('messages.Arrival Flight')
                                    </td>
                                    <td class="htd-2">
                                        {{ $order->arrival_flight }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="htd-1">
                                        @lang('messages.Arrival Time')
                                    </td>
                                    <td class="htd-2">
                                        {{ dateTimeFormat($order->arrival_time) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table tb-list" >
                                <tr>
                                    <td class="htd-1">
                                        @lang('messages.Departure Flight')
                                    </td>
                                    <td class="htd-2">
                                        {{ $order->departure_flight }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="htd-1">
                                        @lang('messages.Departure Time')
                                    </td>
                                    <td class="htd-2">
                                        {{ dateTimeFormat($order->departure_time) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        <div class="page-subtitle m-b-8">@lang('messages.Hotel Detail')</div>
        <div class="card-ptext-margin">

            <div class="row">
                <div class="col-md-6">
                    <table class="table tb-list">
                        @if (isset($order->confirmation_order))
                            <tr>
                                <td class="htd-1">
                                    @lang('messages.Confirmation No')
                                </td>
                                <td class="htd-2">
                                    <b>{{ $order->confirmation_order }}</b>
                                </td>
                            </tr>
                        @endif
                        @if ($order->service == "Hotel")
                            <tr>
                                <td class="htd-1">
                                    @lang('messages.Hotel')
                                </td>
                                <td class="htd-2">
                                    {{ $order->servicename }}
                                </td>
                            </tr>
                        @elseif ($order->service == "Hotel Promo")
                            @php
                                $pname = json_decode($order->promo_name);
                                $promo_name = implode(", ",$pname);
                            @endphp
                            <tr>
                                <td class="htd-1">
                                    @lang('messages.Promo')
                                </td>
                                <td class="htd-2">
                                    {!! $promo_name !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="htd-1">
                                    @lang('messages.Hotel')
                                </td>
                                <td class="htd-2">
                                    {{ $order->servicename }}
                                </td>
                            </tr>
                        @elseif ($order->service == "Hotel Package")
                            <tr>
                                <td class="htd-1">
                                    @lang('messages.Package')
                                </td>
                                <td class="htd-2">
                                    {{ $order->package_name }}
                                </td>
                            </tr>
                            <tr>
                                <td class="htd-1">
                                    @lang('messages.Hotel')
                                </td>
                                <td class="htd-2">
                                    {{ $order->servicename }}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="htd-1">
                                    @lang('messages.Hotel')
                                </td>
                                <td class="htd-2">
                                    {{ $order->servicename }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="htd-1">
                                @lang('messages.Room')
                            </td>
                            <td class="htd-2">
                                {{ $order->subservice }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table tb-list">
                        <tr>
                            <td class="htd-1">
                                @lang('messages.Duration')
                            </td>
                            <td class="htd-2">
                                {{ $order->duration." " }}@lang('messages.Nights')
                            </td>
                        </tr>
                        <tr>
                            <td class="htd-1">
                                @lang('messages.Check In')
                            </td>
                            <td class="htd-2">
                                {{ dateFormat($order->checkin) }} ({{ date('H:i', strtotime($hotel->checkin)) }})
                            </td>
                        </tr>
                        <tr>
                            <td class="htd-1">
                                @lang('messages.Check Out')
                            </td>
                            <td class="htd-2">
                                {{ dateFormat($order->checkout) }} ({{ date('H:i', strtotime($hotel->checkout)) }})
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            @if ($order->benefits != "")
                @php
                    $bene = json_decode($order->benefits);
                    $arr = is_array($bene);
                    if ($arr == 1) {
                        $benefits = implode($bene);
                    }else{
                        $benefits = $order->benefits;
                    }
                @endphp
                <div class="page-text">
                    <hr class="form-hr">
                    <b>@lang('messages.Benefit') :</b> <br>
                    {!! $benefits !!}
                </div>
            @endif
            @if ($order->destinations != "")
                <div class="page-text">
                    <hr class="form-hr">
                    <b>@lang('messages.Destinations') :</b> <br>
                    {!! $order->destinations !!}
                </div>
            @endif
            @if ($order->itinerary != "")
                <div class="page-text">
                    <hr class="form-hr">
                    <b>@lang('messages.Itinerary') :</b> <br>
                    {!! $order->itinerary !!}
                </div>
            @endif
            @if ($order->include != "")
                <div class="page-text">
                    <hr class="form-hr">
                    <b>@lang('messages.Include') :</b> <br>
                    {!! $order->include !!}
                </div>
            @endif
            @if ($order->additional_info != "")
                <div class="page-text">
                    <hr class="form-hr">
                    <b>@lang('messages.Additional Information') :</b> <br>
                    {!! $order->additional_info !!}
                </div>
            @endif
            @if ($order->cancellation_policy != "")
                <div class="page-text">
                    <hr class="form-hr">
                    <b>@lang('messages.Cancelation Policy') :</b>
                    <p>{!! $order->cancellation_policy !!}</p>
                </div>
            @endif
        </div>
        @if ($order->request_quotation == "Yes")
            <div class="col-md-12">
                <div class="checkbox">
                    <p style="color:blue;" class="m-t-8 m-b-18">
                        <i style="color:blue;" class="icon-copy fa fa-check-square" aria-hidden="true"></i> @lang('messages.You are requesting a quote for bookings of more than 8 rooms in this order. We will contact you as soon as possible to confirm your order.') 
                    </p>
                </div>
            </div>
        @else
            @if ($order->number_of_room == "" or $order->number_of_guests_room == "" or $order->guest_detail == "" )
                <div class="page-subtitle" style=" background-color: #ffe3e3; border: 2px dotted red;">@lang('messages.Suites and Villas')</div>
            @else
                <div class="page-subtitle">@lang('messages.Suites and Villas') </div>
            @endif
            <div class="row">

                @if ($order->number_of_room == "" or $order->number_of_guests_room == "" or $order->guest_detail == "" )
                    <div class="col-sm-12 m-b-18">
                        <div class="room-container ">
                            <p style="color:brown;"><i>@lang('messages.You have not selected a room on this booking!')</i></p>
                        </div>
                    </div>
                @else
                    <div class="col-md-12">
                        <table class="data-table table nowrap" >
                            <thead>
                                <tr>
                                    <th style="width: 5%;">@lang('messages.Room')</th>
                                    <th style="width: 5%;">@lang('messages.Number of Guest')</th>
                                    <th style="width: 15%; max-width:15%;">@lang('messages.Guest Name')</th>
                                    <th style="width: 10%;">@lang('messages.Price')</th>
                                    <th style="width: 10%;">@lang('messages.Extra Bed')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < $nor; $i++)
                                    @if ($special_day[$i] != "")
                                        <tr data-toggle="tooltip" data-placement="top" title="{{ dateFormat($special_date[$i])." ".$special_day[$i]  }}" style="background-color: #ffe695;">
                                    @else
                                        <tr >
                                    @endif
                                        <td>
                                            <div class="table-service-name">{{ $r }}</div>
                                        </td>
                                        <td>
                                            <div class="table-service-name">{{ $nogr[$i]." " }}@lang('messages.Guests')</div>
                                        </td>
                                        <td>
                                            <div class="table-service-name">{{ $guest_detail[$i] }}</div>
                                        </td>
                                        <td>
                                            <div class="table-service-name">{{ "$ ".number_format($order->price_pax*$order->duration, 0, ",", ".")  }}</div>
                                        </td>
                                        <td>
                                            @if ($extra_bed[$i] == "Yes")
                                                @if ($totalextrabed > 0)
                                                    @php
                                                        $extrabed = $extra_beds->where('id',$extra_bed_id[$i])->first();
                                                    @endphp
                                                    <div class="table-service-name">{{ $extrabed->name." (".$extrabed->type.") $".number_format($extra_bed_price[$i], 0, ",", ".")}}</div>
                                                @else
                                                    @php
                                                        $order_status = "Invalid";
                                                    @endphp
                                                    <p class="text-danger"><i>@lang('messages.Invalid') </i> <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.This room is occupied by more than 2 guests, and requires an extra bed, please edit it first to be able to submit an order')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></p>
                                                @endif
                                            @else
                                                <div class="table-service-name">-</div>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $r++;

                                    @endphp
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <div class="box-price-kicked">
                            @php
                                if ($totalextrabed > 0) {
                                    $total_extra_bed = array_sum($extra_bed_price);
                                }else{
                                    $total_extra_bed = 0;
                                }
                                $price_per_pax = $order->price_pax * $order->duration;
                                $total_room_and_suite = ($price_per_pax*$nor)+$total_extra_bed;
                            @endphp
                            <div class="row">
                                <div class="col-6 col-md-6">
                                    @if ($nor > 1)
                                        <div class="promo-text">@lang('messages.Price/pax')</div>
                                        <div class="promo-text">@lang('messages.Number of room')</div>
                                    @endif
                                    @if ($total_extra_bed > 0)
                                        <div class="promo-text">@lang('messages.Extra Bed')</div>
                                    @endif
                                    @if ($nor > 1 or $total_extra_bed > 0)
                                        <hr class="form-hr">
                                    @endif
                                    <div class="subtotal-text">@lang('messages.Suites and Villas')</div>
                                </div>
                                <div class="col-6 col-md-6 text-right">
                                    @if ($nor > 1)
                                        <div class="text-price">{{ "$ ".number_format($price_per_pax, 0, ",", ".")  }}</div>
                                        <div class="text-price">{{ $nor }}</div>
                                    @endif
                                    @if ($total_extra_bed > 0)
                                        <div class="text-price">{{ "$ ".number_format(($total_extra_bed), 0, ",", ".") }}</div>
                                    @endif
                                    @if ($nor > 1 or $total_extra_bed > 0)
                                        <hr class="form-hr"> 
                                    @endif
                                    <div class="subtotal-price">{{ "$ ".number_format(($total_room_and_suite), 0, ",", ".") }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if ($order->number_of_guests > 0)
                
                @if ($order->optional_price > 0)
                    <div id="optional_service" class="page-subtitle">@lang('messages.Additional Charge')</div>
                    <div class="row">
                        @php
                            $optional_rate_orders_id = json_decode($optional_rate_orders->optional_rate_id);
                            $optional_rate_orders_nog = json_decode($optional_rate_orders->number_of_guest);
                            $optional_rate_orders_sd = json_decode($optional_rate_orders->service_date);
                            $optional_rate_orders_pp = json_decode($optional_rate_orders->price_pax);
                            $optional_rate_orders_pt = json_decode($optional_rate_orders->price_total);
                            if ($optional_rate_orders_nog != "") {
                                $xsor = count($optional_rate_orders_nog);
                            }else{
                                $xsor = 0;
                                $optional_service_total_price = 0;
                            }
                        @endphp
                        <div class="col-sm-12">
                            <table class="data-table table nowrap" >
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">@lang('messages.Date')</th>
                                        <th style="width: 5%;">@lang('messages.Number of Guest')</th>
                                        <th style="width: 15%;">@lang('messages.Service')</th>
                                        <th style="width: 10%;">@lang('messages.Price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < $xsor; $i++)
                                        <tr>
                                            @php
                                                $optional_service_name = $optionalrates->where('id',$optional_rate_orders_id[$i])->first();
                                            @endphp
                                            <td>
                                                <div class="table-service-name">{{ dateFormat($optional_rate_orders_sd[$i]) }}</div>
                                            </td>
                                            <td>
                                                <div class="table-service-name">{{ $optional_rate_orders_nog[$i]." Guests" }}</div>
                                            </td>
                                            <td>
                                                <div class="table-service-name">{{ $optional_service_name->name }}</div>
                                            </td>
                                            
                                            <td>
                                                <div class="table-service-name">{{ "$ ".number_format($optional_rate_orders_pt[$i], 0, ",", ".") }}</div>
                                            </td>
                                        </tr>
                                        @php
                                            $r++;
                                            $optional_service_total_price = array_sum($optional_rate_orders_pt);
                                        @endphp
                                    @endfor
                                </tbody>
                            </table>
                            <div class="box-price-kicked">
                                <div class="row">
                                    <div class="col-6 col-md-6">
                                        <div class="subtotal-text">@lang('messages.Additional Charge')</div>
                                    </div>
                                    <div class="col-6 col-md-6 text-right">
                                        <div class="subtotal-price">{{ "$ ".number_format(($optional_service_total_price), 0, ",", ".") }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            {{-- ADDITIONAL SERVICE --}}
            @if (isset($additional_service))
                <div class="page-subtitle">@lang('messages.Additional Services')</div>
                <div class="row">
                    @if (isset($order->additional_service))
                        <div class="col-md-12">
                            <table class="data-table table nowrap" >
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">@lang('messages.Date')</th>
                                        <th style="width: 40%;">@lang('messages.Service')</th>
                                        <th style="width: 10%;">@lang('messages.QTY')</th>
                                        <th style="width: 10%;">@lang('messages.Price')</th>
                                        <th style="width: 10%;">@lang('messages.Total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cadser = count($additional_service);
                                    @endphp
                                    @for ($x = 0; $x < $cadser; $x++)
                                        <tr>
                                            <td>
                                                <div class="table-service-name">{{ dateFormat($additional_service_date[$x]) }}</div>
                                            </td>
                                            <td>
                                                <div class="table-service-name">{{ $additional_service[$x] }}</div>
                                            </td>
                                            <td>
                                                <div class="table-service-name">{{ $additional_service_qty[$x] }}</div>
                                            </td>
                                            
                                            <td>
                                                <div class="table-service-name">{{ "$ ".number_format($additional_service_price[$x], 0, ",", ".") }}</div>
                                            </td>
                                            <td>
                                                <div class="table-service-name">{{ "$ ".number_format($additional_service_price[$x]*$additional_service_qty[$x], 0, ",", ".") }}</div>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                            <div class="box-price-kicked m-b-8">
                                <div class="row">
                                    <div class="col-6 col-md-6">
                                        <div class="subtotal-text"> Total Additional Service</div>
                                    </div>
                                    <div class="col-6 col-md-6 text-right">
                                        <div class="subtotal-price">{{ "$ ".number_format(($total_additional_service), 0, ",", ".") }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            @if (isset($order->note))
                <div class="page-subtitle">@lang('messages.Note') / @lang('messages.Remark')</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-text">
                            <p>{!! $order->note !!}</p>
                        </div>
                    </div>
                </div>
            @endif
            @if (isset($order->airport_shuttle_in) or isset($order->airport_shuttle_out))
                <div class="page-subtitle">@lang('messages.Transport')</div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="data-table table nowrap" >
                            <thead>
                                <tr>
                                    <th style="width: 5%;">@lang('messages.No')</th>
                                    <th style="width: 20%;">@lang('messages.Date')</th>
                                    <th style="width: 15%;">@lang('messages.Service')</th>
                                    <th style="width: 10%;">@lang('messages.Transport')</th>
                                    <th style="width: 30%;">@lang('messages.Src') <=> @lang('messages.Dst') </th>
                                    <th style="width: 10%;">@lang('messages.Duration')</th>
                                    <th style="width: 10%;">@lang('messages.Distance')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($airport_shuttles as $nomor=>$airport_shuttle)
                                    <tr>
                                        <td>{{ ++$nomor }}</td>
                                        <td>
                                            <div class="table-service-name">{{ dateTimeFormat($airport_shuttle->date) }}</div>
                                        </td>
                                        <td>
                                            <div class="table-service-name">@lang('messages.Airport Shuttle')</div>
                                        </td>
                                        <td>
                                            <div class="table-service-name">{{ $airport_shuttle->transport }}</div>
                                        </td>
                                        <td>
                                            <div class="table-service-name">{{ $airport_shuttle->src }} - {{ $airport_shuttle->dst }}</div>
                                        </td>
                                        <td>
                                            <div class="table-service-name">{{ $airport_shuttle->duration }} @lang('messages.hours')</div>
                                        </td>
                                        <td>
                                            <div class="table-service-name">{{ $airport_shuttle->distance }} @lang('messages.Km')</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="box-price-kicked m-b-8">
                            <div class="row">
                                <div class="col-6 col-md-6">
                                    <div class="subtotal-text"> @lang('messages.Airport Shuttle')</div>
                                </div>
                                <div class="col-6 col-md-6 text-right">
                                    @if ($order->airport_shuttle_price > 0)
                                        <div class="subtotal-price">{{ "$ ".number_format(($order->airport_shuttle_price), 0, ",", ".") }}</div>
                                    @else
                                        <div class="subtotal-price"><i>@lang('messages.To be advised')</i></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="page-subtitle">@lang('messages.Price')</div>
            <div class="row">
                <div class="col-md-12">
                    <div class="page-note">
                        <div class="row">
                            <div class="col-6 col-md-6">
                                @if ($order->bookingcode_disc > 0 or $order->discounts > 0 or $optional_rate_orders or $order->promotion_disc or $order->kick_back)
                                    <div class="promo-text">@lang('messages.Suites and Villas')</div>
                                    @if ($optional_service_total_price > 0)
                                        <div class="promo-text">@lang('messages.Additional Charge')</div>
                                    @endif
                                    @if ($order->airport_shuttle_price > 0)
                                        <div class="promo-text">@lang('messages.Airport Shuttle')</div>
                                    @endif
                                    @if ($order->kick_back > 0)
                                        <div class="promo-text">@lang('messages.Kick Back')</div>
                                    @endif
                                    @if ($order->bookingcode_disc > 0)
                                        <div class="promo-text">@lang('messages.Booking Code')</div>
                                    @endif
                                    @if ($order->discounts > 0)
                                        <div class="promo-text">@lang('messages.Discounts')</div>
                                    @endif
                                    @if ($order->promotion_disc > 0)
                                        <div class="promo-text">@lang('messages.Promotion')</div>
                                    @endif
                                    <hr class="form-hr">
                                @endif
                                <div class="price-name">@lang('messages.Total Price')</div>
                            </div>
                            <div class="col-6 col-md-6 text-right">
                                @if ($order->bookingcode_disc > 0 or $order->discounts > 0 or $optional_rate_orders != "" or $order->promotion_disc or $order->kick_back)
                                    <div class="promo-text">{{ "$ ".number_format($total_room_and_suite, 0, ",", ".") }}</div>
                                    @if ($optional_service_total_price > 0)
                                        <div class="promo-text">{{ "$ ".number_format(($optional_service_total_price), 0, ",", ".") }}</div>
                                    @endif
                                    @if ($order->airport_shuttle_price > 0)
                                        <div class="promo-text">{{ "$ ".number_format($order->airport_shuttle_price, 0, ",", ".") }}</div>
                                    @endif
                                    @if ($order->kick_back > 0)
                                        <div class="kick-back">{{ number_format($order->kick_back, 0, ",", ".") }}</div>
                                    @endif
                                    @if ($order->bookingcode_disc > 0)
                                        <div class="kick-back">{{ number_format($order->bookingcode_disc, 0, ",", ".") }}</div>
                                    @endif
                                    @if ($order->discounts > 0)
                                        <div class="kick-back">{{ number_format($order->discounts, 0, ",", ".") }}</div>
                                    @endif
                                    @if ($order->promotion_disc > 0)
                                        @php
                                            $pd = json_decode($order->promotion_disc);
                                            $pro_desc = array_sum($pd);
                                        @endphp
                                        <div class="kick-back">{{ number_format($pro_desc, 0, ",", ".") }}</div>
                                    @endif
                                    <hr class="form-hr">
                                @endif
                                <div class="usd-rate">{{ "$ ".number_format($order->final_price, 0, ",", ".") }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="notif-modal text-left">
                        @if ($order->status == "Pending")
                            @lang('messages.We have received your order, we will contact you as soon as possible to validate the order!')
                        @elseif ($order->status == "Rejected")
                            {{ $order->msg }}
                        @elseif ($order->status == "Active")
                            @lang('messages.Your order has been verified, and everything looks good')
                        @elseif ($order->status == "Invalid")
                            {{ $order->msg }}
                        @elseif ($order->status == "Confirmed")
                            @php
                                $due_date = Carbon::parse($invoice->due_date);
                                $dl = $due_date->diffInDays($now);
                                $day_left = $dl+1;
                            @endphp
                            @lang('messages.Please be advised that you are reminded to approve your order before the reconfirm date. Kindly ensure to complete the approval process before the specified deadline. Thank you for your cooperation.')<br><br>
                            @if ($day_left < 3 )
                                <p class="blink_me">{{ $day_left }} @lang('messages.days left before your order is automatically canceled.')</p>
                            @elseif($day_left < 6)
                                <p>{{ $day_left }} @lang('messages.days left before your order is automatically canceled.')</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <div class="card-box-footer">
            @if ($order->status == "Confirmed")
                @if ($reservation->send == "yes")
                    <form id="approveOrder" class="hidden" action="/fapprove-order-{{ $order->id }}"method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                    </form>
                    <div class="notification-order text-left" style="max-width: 50%;">
                        <i>@lang('messages.In') {{ $payment_period }} @lang('messages.days, your order will be automatically canceled if not approved. Approve now!')</i>
                    </div>
                    <button type="submit" form="approveOrder" class="btn btn-primary"><i class="icon-copy ion-checkmark-circled"></i> @lang('messages.Approve Order')</button>
                @else
                    <div class="notification-order text-left">
                        <i>@lang('messages.Waiting for Contract')</i>
                    </div>
                @endif
            @elseif ($order->status == "Rejected")
                <form id="deleteOrder" class="display-content" action="/delete-order/{{ $order->id }}" method="post">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="author" value="{{ Auth::user()->id }}">
                </form>
                <button type="submit" form="deleteOrder" class="btn btn-dark" onclick="return confirm('@lang('messages.Are you sure?')');" type="submit" data-toggle="tooltip" data-placement="top" title="@lang('messages.Delete')"><i class="icon-copy fa fa-trash"></i> Delete Order</button>
            @elseif($order->status == "Approved" or $order->status == "Paid")
                @if ($status_contract == 1)
                    @if ($receipt == "")
                        <a href="modal" data-toggle="modal" data-target="#payment-confirmation-{{ $order->id }}">
                            <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-upload" aria-hidden="true"></i> @lang('messages.Payment Confirmation')</button>
                        </a>
                        {{-- MODAL PAYMENT CONFIRMATION --}}
                        <div class="modal fade" id="payment-confirmation-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="card-box">
                                        <div class="card-box-title text-left">
                                            <div class="title"><i class="icon-copy fa fa-usd" aria-hidden="true"></i>@lang('messages.Payment Confirmation')</div>
                                        </div>
                                        <form id="payment-confirm-{{ $order->id }}" action="/fpayment-confirmation-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row text-left">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="row m-t-27">
                                                                <div class="col-5"><p>@lang('messages.Order Number')</p></div>
                                                                <div class="col-7"><p><b>: {{ $order->orderno }}</b></p></div>
                                                                <div class="col-5"><p>@lang('messages.Reservation Number')</p></div>
                                                                <div class="col-7"><p><b>: {{ $reservation->rsv_no }}</b></p></div>
                                                                <div class="col-5"><p>@lang('messages.Invoice Number')</p></div>
                                                                <div class="col-7"><p><b>: {{ $invoice->inv_no }}</b></p></div>
                                                                <div class="col-5"><p>@lang('messages.Payment Dateline')</p></div>
                                                                <div class="col-7"><p>: {{ dateFormat($invoice->due_date) }}</p></div>
                                                                <div class="col-5"><p>@lang('messages.Amount')</p></div>
                                                                <div class="col-7"><p><b>: {{ "$ ".number_format($order->final_price, 0, ",", ".") }}</b></p></div>
                                                                <div class="col-12 m-t-18"><p><i class="icon-copy fa fa-exclamation" aria-hidden="true"></i> @lang('messages.Please make the payment before the due date and provide proof of payment to prevent the cancellation of your order.')</p></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="cover" class="form-label">@lang('messages.Receipt Image')</label>
                                                                <div class="dropzone">
                                                                    <div class="tour-receipt-div">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="receipt_name" class="form-label">@lang('messages.Select Receipt') </label><br>
                                                                <input type="file" name="receipt_name" id="receipt_name" class="custom-file-input @error('receipt_name') is-invalid @enderror" placeholder="Choose Cover" value="{{ old('receipt_name') }}" required>
                                                                @error('receipt_name')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            </div>
                                        </form>
                                        <div class="card-box-footer">
                                            <button type="submit" form="payment-confirm-{{ $order->id }}" class="btn btn-primary"><i class="icon-copy fa fa-upload" aria-hidden="true"></i> @lang('messages.Send')</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        @if ($receipt->status == "Invalid")
                            <a href="modal" data-toggle="modal" data-target="#invalid-payment-confirmation-{{ $order->id }}">
                                <button type="button" class="btn btn-primary desktop"><i class="icon-copy fa fa-upload" aria-hidden="true"></i> @lang('messages.Payment Confirmation')</button>
                            </a>
                            {{-- MODAL PAYMENT CONFIRMATION --}}
                            <div class="modal fade" id="invalid-payment-confirmation-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="card-box">
                                            <div class="card-box-title text-left">
                                                <div class="title"><i class="icon-copy fa fa-usd" aria-hidden="true"></i>@lang('messages.Payment Confirmation')</div>
                                            </div>
                                            <form id="invalidpayment-confirm-{{ $order->id }}" action="/fpayment-confirmation-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row text-left">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="row m-t-27">
                                                                    <div class="col-5"><p>@lang('messages.Order Number')</p></div>
                                                                    <div class="col-7"><p><b>: {{ $order->orderno }}</b></p></div>
                                                                    <div class="col-5"><p>@lang('messages.Reservation Number')</p></div>
                                                                    <div class="col-7"><p><b>: {{ $reservation->rsv_no }}</b></p></div>
                                                                    <div class="col-5"><p>@lang('messages.Invoice Number')</p></div>
                                                                    <div class="col-7"><p><b>: {{ $invoice->inv_no }}</b></p></div>
                                                                    <div class="col-5"><p>@lang('messages.Due Date')</p></div>
                                                                    <div class="col-7"><p>: {{ dateFormat($invoice->due_date) }}</p></div>
                                                                    <div class="col-5"><p>@lang('messages.Amount')</p></div>
                                                                    <div class="col-7"><p><b>: {{ "$ ".number_format($order->final_price, 0, ",", ".") }}</b></p></div>
                                                                    <div class="col-12 m-t-18"><p><i class="icon-copy fa fa-exclamation" aria-hidden="true"></i> @lang('messages.Please make the payment before the due date and provide proof of payment to prevent the cancellation of your order.')</p></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="dropzone" class="form-label">@lang('messages.Receipt Image')</label>
                                                                    <div class="dropzone">
                                                                        <div class="tour-receipt-div">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="receipt_name" class="form-label">@lang('messages.Select Receipt') </label><br>
                                                                    <input type="file" name="receipt_name" id="receipt_name" class="custom-file-input @error('receipt_name') is-invalid @enderror" placeholder="Choose Cover" value="{{ old('receipt_name') }}" required>
                                                                    @error('receipt_name')
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                </div>
                                            </form>
                                            <div class="card-box-footer">
                                                <button type="submit" form="invalidpayment-confirm-{{ $order->id }}" class="btn btn-primary"><i class="icon-copy fa fa-upload" aria-hidden="true"></i> Send</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    @if (config('app.locale') == "zh")
                        <a href="modal" data-toggle="modal" data-target="#contract-zh-{{ $order->id }}">
                            <button type="button" class="btn btn-primary desktop"><i class="icon-copy fa fa-file-pdf-o" aria-hidden="true"></i> 發票</button>
                        </a>
                        <a href='{{URL::to('/')}}/storage/document/invoice-{{ $inv_no }}-{{ $order->id }}_zh.pdf' target="_blank">
                            <button type="button" class="btn btn-primary mobile"><i class="fa fa-download"></i> 下載發票</button>
                        </a>
                        {{-- MODAL VIEW CONTRACT ZH --}}
                        <div class="modal fade" id="contract-zh-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content" style="padding: 0; background-color:transparent; border:none;">
                                    <div class="modal-body pd-5">
                                        <embed src="storage/document/invoice-{{ $inv_no."-".$order->id }}_zh.pdf" frameborder="10" width="100%" height="850px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="modal" data-toggle="modal" data-target="#contract-en-{{ $order->id }}">
                            <button type="button" class="btn btn-primary desktop"><i class="icon-copy fa fa-file-pdf-o" aria-hidden="true"></i> @lang('messages.Invoice')</button>
                        </a>
                        <a href='{{URL::to('/')}}/storage/document/invoice-{{ $inv_no }}-{{ $order->id }}_en.pdf' target="_blank">
                            <button type="button" class="btn btn-primary mobile"><i class="fa fa-download"></i> @lang('messages.Download Invoice')</button>
                        </a>
                        {{-- MODAL VIEW CONTRACT EN --}}
                        <div class="modal fade" id="contract-en-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content" style="padding: 0; background-color:transparent; border:none;">
                                    <div class="modal-body pd-5">
                                        <embed src="storage/document/invoice-{{ $inv_no."-".$order->id }}_en.pdf" frameborder="10" width="100%" height="850px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="notification-order text-left">
                        <i>@lang('messages.Waiting for Contract')</i>
                    </div>
                @endif
            @endif
            <a href="/orders">
                <button type="button" class="btn btn-danger"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
            </a>
            
        </div>
        
    </div>
</div>
@if ($order->status == "Approved" or $order->status == "Paid")
    <div class="col-md-4 desktop">
        <div class="card-box">
            <div class="card-box-title">
                <div class="subtitle"><i class="icon-copy fa fa-money" aria-hidden="true"></i> @lang('messages.Payment Status')</div>
            </div>
            @if (!$invoice == NULL)
                @if ($invoice->due_date > $now)
                    @if (isset($receipt))
                        @if ($receipt->status == "Paid")
                            <div class="pmt-container">
                                <i class="icon-copy fa fa-check-circle" aria-hidden="true"></i>
                                <div class="pmt-status">
                                    @lang('messages.Paid')
                                </div>
                            </div>
                            <div class="pmt-des">
                                <b>{{ $invoice->inv_no }}</b>
                                <p>@lang('messages.Paid on') : {{ dateFormat($receipt->payment_date) }}<br>
                            </div>
                            <div class="view-receipt">
                                <a class="action-btn" href="modal" data-toggle="modal" data-target="#paid-receipt-{{ $receipt->id }}">
                                    <i class="icon-copy fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="modal fade" id="paid-receipt-{{ $receipt->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content modal-img">
                                        <div class="card-box">
                                            <div class="card-box-title">
                                                <div class="title"><i class="icon-copy fa fa-file-photo-o" aria-hidden="true"></i> @lang('messages.Payment Receipt')</div>
                                            </div>
                                            <img style="height: 630px;" src="{{ asset('storage/receipt/'.$receipt->receipt_img) }}" alt="">
                                            <div class="card-box-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($receipt->status == "Pending")
                            <div class="pmt-container pending">
                                <i class="icon-copy fa fa-clock-o" aria-hidden="true"></i>
                                <div class="pmt-status">
                                    @lang('messages.On Review')
                                </div>
                            </div>
                            <div class="pmt-des">
                                <b>{{ $invoice->inv_no }}</b>
                                <p>@lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                                <p>@lang('messages.Payment Confirmation') : {{ dateFormat($receipt->created_at) }}</p>
                                
                            </div>
                        @elseif($receipt->status == "Invalid")
                            <div class="pmt-container unpaid">
                                <i class="icon-copy fa fa-window-close" aria-hidden="true"></i>
                                <div class="pmt-status">
                                    @lang('messages.Invalid')
                                </div>
                            </div>
                            <div class="pmt-des">
                                <p><i style="color: red">{{ $receipt->note }}</i></p>
                                <b>{{ $invoice->inv_no }}</b>
                                <p>@lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                            </div>
                            <div class="view-receipt">
                                <a class="action-btn" href="modal" data-toggle="modal" data-target="#invalid-receipt-{{ $receipt->id }}">
                                    <i class="icon-copy fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="modal fade" id="invalid-receipt-{{ $receipt->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content modal-img">
                                        <div class="card-box">
                                            <div class="card-box-title">
                                                <div class="title"><i class="icon-copy fa fa-file-photo-o" aria-hidden="true"></i> @lang('messages.Payment Receipt')</div>
                                            </div>
                                            <img style="height: 630px;" src="{{ asset('storage/receipt/'.$receipt->receipt_img) }}" alt="">
                                            <div class="notification-text" style="margin-top: 8px; color:rgb(143, 0, 0);">
                                                {!! $receipt->note !!}
                                            </div>
                                            <div class="card-box-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="pmt-container unpaid">
                                <i class="icon-copy fa fa-window-close" aria-hidden="true"></i>
                                <div class="pmt-status">
                                    @lang('messages.Unpaid')
                                </div>
                            </div>
                            <div class="pmt-des">
                                <b>{{ $invoice->inv_no }}</b>
                                <p>@lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                            </div>
                            <div class="view-receipt">
                                <a class="action-btn" href="modal" data-toggle="modal" data-target="#payment-receipt-{{ $receipt->id }}">
                                    <i class="icon-copy fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="modal fade" id="payment-receipt-{{ $receipt->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content modal-img">
                                        <div class="card-box">
                                            <div class="card-box-title">
                                                <div class="title"><i class="icon-copy fa fa-file-photo-o" aria-hidden="true"></i> @lang('messages.Payment Receipt')</div>
                                            </div>
                                            <img style="height: 630px;" src="{{ asset('storage/receipt/'.$receipt->receipt_img) }}" alt="">
                                            <div class="notification-text" style="margin-top: 8px; color:rgb(143, 0, 0);">
                                                {!! $receipt->note !!}
                                            </div>
                                            <div class="card-box-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="pmt-container pending">
                            <i class="icon-copy fa fa-hourglass" aria-hidden="true"></i>
                            <div class="pmt-status">
                                @lang('messages.Awaiting Payment')
                            </div>
                        </div>
                        <div class="pmt-des">
                            <b>{{ $invoice->inv_no }}</b>
                            <p>@lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                        </div>
                    @endif
                @else
                    @if (isset($receipt))
                        @if ($receipt->status == "Paid")
                            <div class="pmt-container">
                                <i class="icon-copy fa fa-check-circle" aria-hidden="true"></i>
                                <div class="pmt-status">
                                    @lang('messages.Paid')
                                </div>
                            </div>
                            <div class="pmt-des">
                                <b>{{ $order->orderno." - ". $invoice->inv_no }}</b>
                                <p>@lang('messages.Paid on') : {{ dateFormat($receipt->payment_date) }}<br>
                                @lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                                
                            </div>
                            <div class="view-receipt">
                                <a class="action-btn" href="modal" data-toggle="modal" data-target="#receipt-{{ $receipt->id }}">
                                    <i class="icon-copy fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="modal fade" id="receipt-{{ $receipt->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content modal-img">
                                        <div class="card-box">
                                            <div class="card-box-title">
                                                <div class="title"><i class="icon-copy fa fa-file-photo-o" aria-hidden="true"></i> @lang('messages.Payment Receipt')</div>
                                            </div>
                                            <img style="height: 630px;" src="{{ asset('storage/receipt/'.$receipt->receipt_img) }}" alt="">
                                            <div class="card-box-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="pmt-container unpaid">
                                <i class="icon-copy fa fa-window-close" aria-hidden="true"></i>
                                <div class="pmt-status">
                                    @lang('messages.Invalid')
                                </div>
                            </div>
                            <div class="pmt-des">
                                <b>{{ $invoice->inv_no }}</b>
                                <p>@lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                            </div>
                        @endif
                    @else
                        <div class="pmt-container unpaid">
                            <i class="icon-copy fa fa-window-close" aria-hidden="true"></i>
                            <div class="pmt-status">
                                @lang('messages.Unpaid')
                            </div>
                        </div>
                        <div class="pmt-des">
                            <b>{{ $invoice->inv_no }}</b>
                            <p>@lang('messages.Payment Dateline') : {{ dateFormat($invoice->due_date) }}</p>
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </div>
@endif