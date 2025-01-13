@php
    $r = 1;
    use Carbon\Carbon;
@endphp
<div class="col-md-8">
    <div class="card-box">
        <div class="card-box-title">
            <div class="subtitle"><i class="fa fa-pencil"></i> @lang('messages.Edit Order')</div>
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
                    {{ dateFormat($order->created_at) }}
                </div>
            </div>
        </div>
        <form id="edit-order" action="{{ route('fupdate.order',$order->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="business-name">{{ $business->name }}</div>
            <div class="bussines-sub">{{ $business->caption }}</div>
            <hr class="form-hr">
            <div class="row">
                <div class="col-md-6">
                    <table class="table tb-list">
                        <tr>
                            <td class="htd-1">
                                @lang('messages.Order No')
                            </td>
                            <td class="htd-2">
                                <b>{{ $order->orderno }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td class="htd-1">
                                @lang('messages.Order Date')
                            </td>
                            <td class="htd-2">
                                {{ $created_at }}
                            </td>
                        </tr>
                        <tr>
                            <td class="htd-1">
                                @lang('messages.Service')
                            </td>
                            <td class="htd-2">
                                @lang('messages.'.$order->service)
                            </td>
                        </tr>
                        <tr>
                            <td class="htd-1">
                                @lang('messages.Location')
                            </td>
                            <td class="htd-2">
                                {{ $order->location }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    @if ($order->status == "Active")
                        <div class="page-status" style="color: rgb(0, 156, 21)"> @lang('messages.Confirmed') <span>@lang('messages.Status'):</span></div>
                    @elseif ($order->status == "Pending")
                        <div class="page-status" style="color: #dd9e00">@lang('messages.'.$order->status) <span>@lang('messages.Status'):</span></div>
                    @elseif ($order->status == "Rejected")
                        <div class="page-status" style="color: rgb(160, 0, 0)">@lang('messages.'.$order->status) <span>@lang('messages.Status'):</span></div>
                    @else
                        <div class="page-status" style="color: rgb(48, 48, 48)">@lang('messages.'.$order->status) <span>@lang('messages.Status'):</span></div>
                    @endif
                </div>
            </div>
            {{-- ORDER --}}
            <div class="page-subtitle">@lang('messages.Order')</div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table tb-list">
                        @switch($order->service)
                            @case('Hotel')
                                <tr>
                                    <td class="htd-1">@lang('messages.Hotel')</td>
                                    <td class="htd-2">{{ $order->servicename }}</td>
                                </tr>
                                @break
                            @case('Hotel Promo')
                                <tr>
                                    <td class="htd-1">@lang('messages.Promo')</td>
                                    <td class="htd-2">
                                        @if (!empty($order_promos))
                                            {{ implode(', ', $order_promos) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="htd-1">@lang('messages.Hotel')</td>
                                    <td class="htd-2">{{ $order->servicename }}</td>
                                </tr>
                                @break
                            @case('Hotel Package')
                                <tr>
                                    <td class="htd-1">@lang('messages.Package')</td>
                                    <td class="htd-2">{{ $order->package_name }}</td>
                                </tr>
                                <tr>
                                    <td class="htd-1">@lang('messages.Hotel')</td>
                                    <td class="htd-2">{{ $order->servicename }}</td>
                                </tr>
                                @break
                            @default
                                <tr>
                                    <td class="htd-1">@lang('messages.Hotel')</td>
                                    <td class="htd-2">{{ $order->servicename }}</td>
                                </tr>
                        @endswitch
                        <tr>
                            <td class="htd-1">@lang('messages.Room')</td>
                            <td class="htd-2">{{ $order->subservice }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table tb-list">
                        <tr>
                            <td class="htd-1">@lang('messages.Duration')</td>
                            <td class="htd-2">{{ $order->duration." " }}@lang('messages.Nights')</td>
                        </tr>
                        <tr>
                            <td class="htd-1">@lang('messages.Check In')</td>
                            <td class="htd-2">{{ $checkin }}</td>
                        </tr>
                        <tr>
                            <td class="htd-1">@lang('messages.Check Out')</td>
                            <td class="htd-2">{{ $checkout }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            {{-- <div class="page-text">
                @foreach ($descriptions as $key => $label)
                    @if (!empty($order->$key))
                        @php
                            $desc = implode('',json_decode($order->$key));
                        @endphp
                        @if ($desc != null)
                            <hr class="form-hr">
                            <b>{{ $label }} :</b>
                            {!! $desc !!}
                        @endif
                    @endif
                @endforeach
            </div> --}}
            {{-- Suites and Villas ================================================================================================================================ --}}
            <div class="page-subtitle" style="{{ !$isRoomInfoComplete ? 'background-color: #ffe3e3; border: 2px dotted red;' : '' }}">
                @lang('messages.Suites and Villas')
            </div>
            <div class="row">
                @if ($isRoomInfoComplete)
                    <div class="col-md-12">
                        <table class="data-table table nowrap">
                            <thead>
                                <tr>
                                    <th>@lang('messages.Room')</th>
                                    <th>@lang('messages.Number of Guests')</th>
                                    <th>@lang('messages.Guest Name')</th>
                                    <th>@lang('messages.Price')</th>
                                    <th>@lang('messages.Extra Bed')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roomDetails as $detail)
                                    @if ($detail['special_date'])
                                        <tr data-toggle="tooltip" data-placement="top" title="{{  date('Y-m-d',strtotime($detail['special_date'])).' '.$detail['special_day'] }}" style="background-color: #ffe695;">
                                    @else
                                        <tr>
                                    @endif
                                        <td>{{ $detail['room'] }}</td>
                                        <td>{{ $detail['number_of_guests'] }}</td>
                                        <td>{{ $detail['guest_name'] }}</td>
                                        <td>{{ $detail['price'] }}</td>
                                        <td>{{ $detail['extra_bed']?$detail['extra_bed']:"None"; }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <div class="box-price-kicked m-b-8">
                            <div class="row">
                                <div class="col-6">
                                    <div class="normal-text">@lang('messages.Price/pax')</div>
                                    <div class="normal-text">@lang('messages.Number of room')</div>
                                    @if ($totalExtraBedPrice>0)
                                        <div class="normal-text">@lang('messages.Extra Bed')</div>
                                    @endif
                                    <hr class="form-hr">
                                    <div class="subtotal-text">@lang('messages.Suites and Villas')</div>
                                </div>
                                <div class="col-6 text-right">
                                    <div class="text-price">{{ currencyFormatUsd($pricePerPax) }}</div>
                                    <div class="text-price">{{ $order->number_of_room }}</div>
                                    @if ($totalExtraBedPrice>0)
                                        <div class="text-price">{{ currencyFormatUsd($totalExtraBedPrice) }}</div>
                                    @endif
                                    <hr class="form-hr">
                                    <div class="subtotal-price">{{ currencyFormatUsd($totalRoomAndSuite) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-sm-12 m-b-18">
                        <div class="room-container">
                            <p style="color:brown;">
                                <i>@lang('messages.You have not selected a room on this booking!')</i>
                            </p>
                        </div>
                    </div>
                @endif
                <div class="col-md-12 text-right">
                    <a href="{{ route('edit.order.room',$order->id) }}">
                        <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="@lang('messages.Edit')">
                            <i class="icon-copy fa fa-pencil" aria-hidden="true"></i> 
                            {{ $isRoomInfoComplete ? __('messages.Edit') : __('messages.Add') }}
                        </button>
                    </a>
                </div>
            </div>

            {{-- ADDITIONAL SERVICES ========================================================================================================================================= --}}
            @if ($optionalRates->isNotEmpty() && $order->number_of_guests > 0)
                <div id="optional_service" class="page-subtitle">
                    @lang('messages.Additional Services')
                </div>
                @if ($decodedOptionalRates)
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="data-table table nowrap">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">@lang('messages.Date')</th>
                                        <th style="width: 5%;">@lang('messages.Number of Guests')</th>
                                        <th style="width: 15%;">@lang('messages.Service')</th>
                                        <th style="width: 10%;">@lang('messages.Price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($decodedOptionalRates['or_id'] as $index => $rateId)
                                        @php
                                            $optionalServiceName = $optionalRates->firstWhere('id', $rateId);
                                        @endphp
                                        <tr>
                                            <td>{{ dateFormat($decodedOptionalRates['or_service_date'][$index]) }}</td>
                                            <td>{{ $decodedOptionalRates['number_of_guest'][$index] }}</td>
                                            <td>{{ $optionalServiceName->name ?? '-' }}</td>
                                            <td>{{ "$ ".number_format($decodedOptionalRates['or_price_total'][$index], 0, ",", ".") }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="box-price-kicked m-b-8">
                                <div class="row">
                                    <div class="col-6 col-md-6">
                                        <div class="subtotal-text">@lang('messages.Additional Charge')</div>
                                    </div>
                                    <div class="col-6 col-md-6 text-right">
                                        <div class="subtotal-price">{{ "$ ".number_format($optionalServiceTotalPrice, 0, ",", ".") }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12 text-right">
                        @if (in_array($order->status, ['Draft', 'Rejected', 'Invalid']))
                            <a href="/editorder-optionalservice-{{ $order->id }}">
                                <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('messages.Edit')">
                                    <i class="icon-copy fa fa-pencil" aria-hidden="true"></i> 
                                    {{ $decodedOptionalRates ? __('messages.Edit') : __('messages.Add') }}
                                </button>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- AIRPORT SHUTTLE ========================================================================================================================================= --}}
            <div class="page-subtitle">@lang('messages.Flight and Transport Detail')</div>
            @php
                $statusValid = in_array($order->status, ['Draft', 'Rejected', 'Invalid']);
                $airportShuttles = $transports->keyBy('id');
            @endphp 
            <div class="row">
                <!-- Arrival Flight -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="opInputArrivalFlight">@lang('messages.Arrival Flight')</label>
                        <input type="text" id="opInputArrivalFlight" name="arrival_flight" class="form-control @error('arrival_flight') is-invalid @enderror" placeholder="@lang('messages.Arrival Flight')" value="{{ $order->arrival_flight }}">
                        @error('arrival_flight') <div class="alert alert-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                <!-- Arrival Date and Time -->
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="opInputArrivalTime">@lang('messages.Arrival Date and Time')</label>
                        <input readonly type="text" id="opInputArrivalTime" name="arrival_time" class="form-control datetimepicker @error('arrival_time') is-invalid @enderror" placeholder="@lang('messages.Select date and time')" value="{{ $order->arrival_time ? date('d F Y h:i a', strtotime($order->arrival_time)) : '' }}">
                        @error('arrival_time') <div class="alert alert-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                    {{-- BARU SAMPAI DISINI --}}
                <!-- Airport Shuttle In -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="airport_shuttle_in">@lang('messages.Airport Shuttle') <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.Request')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></label>
                        <select name="airport_shuttle_in" id="airport_shuttle_in" class="form-control @error('airport_shuttle_in') is-invalid @enderror">
                            <option value="">@lang('messages.None')</option>
                            @foreach ($transports as $transport)
                                @php
                                    $transport_in = $order->airport_shuttles->where('transport_id',$transport->id)->where('nav','In')->first();
                                @endphp
                                
                                @if ($transport_in)
                                    @php
                                        if ($transport_in->price_id) {
                                            $transport_price = $transport->prices->where('id',$transport_in->price_id)->first();
                                            $transport_in_price = $transport_price->calculatePrice($usdrates, $tax);
                                        }else{
                                            $transport_in_price = 0;
                                        }
                                    @endphp
                                    <option selected value="{{ $transport->id }}" data-transportin=1 data-transporpricein={{ $transport_in_price }}>{{ $transport_in->transport->brand." ".$transport_in->transport->name." - (".$transport_in->transport->capacity.")" }}</option>
                                @else
                                    <option value="{{ $transport->id }}" data-transportin=1 data-transporpricein=0>{{ $transport->brand." ".$transport->name." - (".$transport->capacity.")" }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('airport_shuttle_in') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>
            
                <!-- Departure Flight -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="opInputDepartureFlight">@lang('messages.Departure Flight')</label>
                        <input type="text" id="opInputDepartureFlight" name="departure_flight" class="form-control @error('departure_flight') is-invalid @enderror" placeholder="@lang('messages.Departure Flight')" value="{{ $order->departure_flight }}">
                        @error('departure_flight') <div class="alert alert-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
            
                <!-- Departure Date and Time -->
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="opInputDepartureTime">@lang('messages.Departure Date and Time')</label>
                        <input readonly type="text" id="opInputDepartureTime" name="departure_time" class="form-control datetimepicker @error('departure_time') is-invalid @enderror" placeholder="@lang('messages.Select date and time')" value="{{ $order->departure_time ? date('d F Y h:i a', strtotime($order->departure_time)) : '' }}">
                        @error('departure_time') <div class="alert alert-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
            
                <!-- Airport Shuttle Out -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="airport_shuttle_out">@lang('messages.Airport Shuttle') <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.Request')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></label>
                        <select name="airport_shuttle_out" id="airport_shuttle_out" class="form-control @error('airport_shuttle_out') is-invalid @enderror">
                            <option value="">@lang('messages.None')</option>
                            @foreach ($transports as $transport_out)
                                <option value="{{ $transport_out->id }}" {{ $order->airport_shuttle_out == $transport_out->id ? 'selected' : '' }}>
                                    {{ $transport_out->brand . ' ' . $transport_out->name . ' - ' . $transport_out->capacity }} @lang('messages.seats')
                                </option>
                            @endforeach
                        </select>
                        @error('airport_shuttle_out') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>
                @if (count($order->airport_shuttles)>0)
                    <div class="col-md-12">
                        <div class="box-price-kicked m-b-8">
                            <div class="row">
                                <div class="col-6">
                                    <div class="subtotal-text">@lang('messages.Airport Shuttle')</div>
                                </div>
                                <div class="col-6 text-right">
                                    <div class="subtotal-price">{{ $total_price_airport_shuttle > 0?currencyFormatUsd($total_price_airport_shuttle):__('messages.To be advised') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- NOTE ========================================================================================================================================= --}}
            <div class="page-subtitle">@lang('messages.Note')</div>
            <div class="row">
                <div class="col-md-12">
                <div class="form-group">
                    <textarea id="note" name="note" class="ckeditor form-control border-radius-0" placeholder="@lang('messages.Optional')" {{ !$statusValid ? 'readonly' : '' }}>{{ $order->note }}</textarea>
                    @error('note') <div class="alert alert-danger">{{ $message }}</div> @enderror
                </div>
                </div>
            </div>
            {{-- PRICES ========================================================================================================================================= --}}
            <div class="page-subtitle">@lang('messages.Price')</div>
            <div class="row">
                <div class="col-md-12 m-b-8">
                    <div class="box-price-kicked">
                        <div class="row">
                            <div class="col-6 col-md-6">
                                @if ($order->bookingcode_disc > 0 or $order->discounts > 0 or $optional_service_total_price > 0 or $order->kick_back > 0 or $total_promotion_disc > 0)
                                    <div class="promo-text">@lang('messages.Suites and Villas')</div>
                                    @if ($optional_service_total_price > 0)
                                        <div class="promo-text">@lang('messages.Additional Charge')</div>
                                    @endif

                                    <hr class="form-hr">

                                    @if ($order->kick_back > 0)
                                        <div class="promo-text">@lang('messages.Kick Back')</div>
                                    @endif

                                    @if ($order->bookingcode_disc > 0)
                                        <div class="promo-text">@lang('messages.Booking Code')</div>
                                    @endif

                                    @if ($order->discounts > 0)
                                        <div class="promo-text">@lang('messages.Discounts')</div>
                                    @endif
                                    
                                    @if ($total_promotion_disc > 0)
                                        <div class="promo-text">@lang('messages.Promotion')</div>
                                    @endif
                                    @if ($order->kick_back > 0 or $order->bookingcode_disc > 0 or $order->discounts > 0 or $total_promotion_disc > 0)
                                        <hr class="form-hr">
                                    @endif
                                @endif
                                @if (count($order->airport_shuttles)>0)
                                    <div class="normal-text">@lang('messages.Suites and Villas')</div>
                                    <div class="normal-text">@lang('messages.Airport Shuttle')</div>
                                    <hr class="form-hr">
                                    <div class="price-name">@lang('messages.Total Price')</div>
                                @else
                                    <div class="price-name">@lang('messages.Total Price')</div>
                                @endif
                            </div>
                            <div class="col-6 col-md-6 text-right">
                                @if ($order->bookingcode_disc > 0 or $order->discounts > 0 or $optional_service_total_price > 0 or $order->kick_back > 0 or $total_promotion_disc > 0)
                                    <div class="promo-text">{{ "$ ".number_format($totalRoomAndSuite, 0, ",", ".") }}</div>

                                    @if ($optional_service_total_price > 0)
                                        <div class="promo-text">{{ "$ ".number_format(($optional_service_total_price), 0, ",", ".") }}</div>
                                    @endif

                                    <hr class="form-hr">
                                    
                                    @if ($order->kick_back > 0)
                                        <div class="kick-back">{{ number_format($order->kick_back, 0, ",", ".") }}</div>
                                    @endif

                                    @if ($order->bookingcode_disc > 0)
                                        <div class="kick-back">{{ number_format($order->bookingcode_disc, 0, ",", ".") }}</div>
                                    @endif

                                    @if ($order->discounts > 0)
                                        <div class="kick-back">{{ number_format($order->discounts, 0, ",", ".") }}</div>
                                    @endif
                                    @if ($total_promotion_disc > 0)
                                        <div class="kick-back">{{ number_format($total_promotion_disc, 0, ",", ".") }}</div>
                                    @endif
                                
                                    @if ($order->kick_back > 0 or $order->bookingcode_disc > 0 or $order->discounts > 0 or $total_promotion_disc > 0)
                                        <hr class="form-hr">
                                    @endif
                                @endif
                                <div id="airportShuttlePrice" class="text-price"><span id="airportShuttleText">Airport Shuttle</span></div>
                                @if (count($order->airport_shuttles)>0)
                                    @if ($order->airport_shuttle_price > 0 )
                                        <div class="subtotal-price">{{ currencyFormatUsd($totalRoomAndSuite) }}</div>
                                        <div class="normal-text">{{ currencyFormatUsd($order->airport_shuttle_price) }}</div>
                                        <hr class="form-hr">
                                        <div class="usd-rate">{{ "$ ".number_format($order->final_price, 0, ",", ".") }}</div>
                                    @else
                                        <div class="subtotal-price">{{ currencyFormatUsd($totalRoomAndSuite) }}</div>
                                        <div class="normal-text">@lang('messages.To be advised')</div>
                                        <hr class="form-hr">
                                        <div class="usd-rate">@lang('messages.To be advised')</div>
                                    @endif
                                @else
                                    <div class="usd-rate">{{ "$ ".number_format($order->final_price, 0, ",", ".") }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="notif-modal text-left">
                        @if ($order->status == "Draft")
                            @if (Auth::user()->email == "" or Auth::user()->phone == "" or Auth::user()->office == "" or Auth::user()->address == "" or Auth::user()->country == "")
                                @lang('messages.Please complete your profile data first to be able to submit orders, by clicking this link') -> <a href="/profile">@lang('messages.Edit Profile')</a>
                            @else
                                @if ($order_status == "Invalid")
                                    @lang('messages.This order is invalid, please make sure all data is correct!')
                                @else
                                    @lang('messages.Please make sure all the data is correct before you submit the order!')
                                @endif
                            @endif
                        @elseif ($order->status == "Pending")
                            @lang('messages.We have received your order, we will contact you as soon as possible to validate the order!')
                        @elseif ($order->status == "Rejected")
                            {{ $order->msg }}
                        @elseif ($order->status == "Invalid")
                            {{ $order->msg }}
                        @endif
                    
                    </div>
                </div>
                @include('partials.order-hidden-fields', ['order' => $order, 'authUser' => Auth::user()])
                
            </div>
        </Form>
        <div class="card-box-footer">
            @if ($order->status == "Draft")
                    @if ($order->number_of_room == "" or $order->number_of_guests_room == "" or $order->guest_detail == "" or $order->guest_detail == ""  )
                        <button type="button" class="btn btn-light"><i class="icon-copy fa fa-info" aria-hidden="true"> </i> @lang('messages.You cannot submit this order')</button>
                    @else
                        @if ($order_status != "Invalid")
                            @if (Auth::user()->email == "" or Auth::user()->phone == "" or Auth::user()->office == "" or Auth::user()->address == "" or Auth::user()->country == "")
                                <button disabled type="submit" form="edit-order" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> @lang('messages.Submit')</button>
                            @else
                                <button type="submit" form="edit-order" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> @lang('messages.Submit')</button>
                            @endif
                        @else
                            <p class="notification-danger">@lang('messages.An error has occurred in the Suites and Villas section')</p>
                            <button disabled type="submit" form="edit-order" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> @lang('messages.Submit')</button>
                        @endif
                    @endif
                    <a href="/orders">
                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Cancel')</button>
                    </a>
                
            @elseif ($order->status == "Rejected")
                <form id="removeOrder" class="hidden" action="/fremove-order/{{ $order->id }}"method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <input type="hidden" name="status" value="Removed">
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                </form>
                <button type="submit" form="removeOrder" class="btn btn-danger"><i class="icon-copy fa fa-trash-o" aria-hidden="true"></i> @lang('messages.Delete')</button>
            @else
                <div class="form-group">
                    <a href="/orders">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="loading-icon hidden pre-loader">
    <div class="pre-loader-box">
        <div class="sys-loader-logo w3-center"> <img class="w3-spin" src="{{ asset('storage/icon/spinner.png') }}" alt="Bali Kami Tour Logo" loading='lazy'></div>
        <div class="loading-text">
            Submitting an Order...
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
        $("#edit-order").submit(function() {
            $(".result").text("");
            $(".loading-icon").removeClass("hidden");
            $(".submit").attr("disabled", true);
            $(".btn-txt").text("Processing ...");
        });

        var 
        function calculatePrices() {
            var totalPromoPrice = @json($order->price_total);
            var finalPrice = totalPromoPrice;

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
            
            $('#promo_price').val(totalPromoPrice);
            $('#final_price').val(finalPrice);
            $("#total_promo_price").text(totalPromoPrice);

            
            if (aiportStatusIn === 1 && aiportStatusOut === 1) {
                document.getElementById('airportShuttle').hidden = false;
                document.getElementById('airportShuttlePrice').hidden = false;
                document.getElementById('airportShuttleText').hidden = false;
                if (transportPriceIn > 0 && transportPriceOut > 0) {
                    $("#airportShuttleText").text(currencyFormatter.format(transportPriceIn + transportPriceOut));
                    $("#finalprice").text(currencyFormatter.format(finalPrice));
                }else{
                    $("#airportShuttleText").text("@lang('messages.To be advised')");
                    $("#finalprice").text("@lang('messages.To be advised')");
                }

            }else if (aiportStatusIn === 1){
                document.getElementById('airportShuttle').hidden = false;
                document.getElementById('airportShuttlePrice').hidden = false;
                document.getElementById('airportShuttleText').hidden = false;
                if (transportPriceIn > 0) {
                    $("#airportShuttleText").text(currencyFormatter.format(transportPriceIn + transportPriceOut));
                    $("#finalprice").text(currencyFormatter.format(finalPrice));
                }else{
                    $("#airportShuttleText").text("@lang('messages.To be advised')");
                    $("#finalprice").text("@lang('messages.To be advised')");
                }
            }else if (aiportStatusOut === 1){
                document.getElementById('airportShuttle').hidden = false;
                document.getElementById('airportShuttlePrice').hidden = false;
                document.getElementById('airportShuttleText').hidden = false;
                if (transportPriceOut > 0) {
                    $("#airportShuttleText").text(currencyFormatter.format(transportPriceIn + transportPriceOut));
                    $("#finalprice").text(currencyFormatter.format(finalPrice));
                }else{
                    $("#airportShuttleText").text("@lang('messages.To be advised')");
                    $("#finalprice").text("@lang('messages.To be advised')");
                }
            }else{
                document.getElementById('airportShuttle').hidden = true;
                document.getElementById('airportShuttleText').hidden = true;
                document.getElementById('airportShuttlePrice').hidden = true;
                $("#finalprice").text(currencyFormatter.format(finalPrice));
            }

            if (totalExtraBedPrice > 0) {
                document.getElementById('extraBedPrice').hidden = false;
                document.getElementById('extraBedText').hidden = false;
                document.getElementById('suitesAndVillasText').hidden = false;
                document.getElementById('suitesAndVillasPrice').hidden = false;
                $("#extraBedPriceTotal").text(currencyFormatter.format(totalExtraBedPrice));
                $("#suitesAndVillasPriceLable").text(currencyFormatter.format(totalPromoPrice));
            }else{
                document.getElementById('extraBedPrice').hidden = true;
                document.getElementById('extraBedText').hidden = true;
                document.getElementById('suitesAndVillasText').hidden = true;
                document.getElementById('suitesAndVillasPrice').hidden = true;
                $("#extraBedPriceTotal").text(currencyFormatter.format(totalExtraBedPrice));
                $("#suitesAndVillasPriceLable").text(currencyFormatter.format(totalPromoPrice));
            }
        }
        $("#airportShuttleIn").change(function() {
            calculatePrices();
        });

        $("#airportShuttleOut").change(function() {
            calculatePrices();
        });
    });
</script>