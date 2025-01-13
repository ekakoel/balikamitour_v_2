@section('title',__('Edit Room'))
@extends('layouts.head')
@section('content')
    <div class="mobile-menu-overlay"></div>
    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="title"><i class="icon-copy fa fa-tags"></i>&nbsp; @lang('messages.Edit Order')</div>
                            @include('partials.breadcrumbs', [
                                'breadcrumbs' => [
                                    ['url' => route('dashboard.index'), 'label' => __('messages.Dashboard')],
                                    ['url' => route('orders.index'), 'label' => __('messages.Order')],
                                    ['url' => route('order-hotel-promo-edit.view',$order->id), 'label' => $order->orderno],
                                    ['label' => __('messages.Edit Suites and Villas')],
                                ]
                            ])
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card-box">
                           <div class="card-box-title">
                            <div class="subtitle"><i class="fa fa-hotel"></i> @lang('messages.Suites and Villas')</div>
                        </div>
                        
                        <form id="edit-order-room" action="{{ route('fupdate.order.hotel.promo.room',$order->id) }}" method="post" enctype="multipart/form-data">
                            @method('put')
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <ol>
                                        @foreach ($order_rooms as $index=>$order_room)
                                            <li class="m-b-8">
                                                <input type="hidden" name="order_room_id[]" value="{{ $order_room->id }}">
                                                <div class="control-group">
                                                    <div class="{{ $order->room->capacity < $order_room->number_of_guests && !$order_room->extra_bed_price? "room-container-error":"room-container";}}">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="subtitle">
                                                                    {{ $order->room->rooms }}
                                                                    @if ($order->room->capacity < $order_room->number_of_guests && !$order_room->extra_bed_price)
                                                                        <p class="blink_me float-right p-r-27"><i>@lang('messages.This room need extra bed!')</i></p>
                                                                    @endif
                                                                </div>
                                                                @if ($index > 0)
                                                                    <button class="btn btn-remove remove" type="button"><i class="icon-copy fa fa-close" aria-hidden="true"></i></button>
                                                                @endif
                                                            </div>
                    
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="number_of_guests[]">@lang('messages.Number of Guest')</label>
                                                                    <input type="number" min="1" max="{{ $order->room->capacity }}" name="number_of_guests[]" class="form-control m-0 @error('number_of_guests[]') is-invalid @enderror" placeholder="@lang('messages.Number of guests')" value="{{ $order_room->number_of_guests }}" required>
                                                                    @error('number_of_guests[]')
                                                                        <div class="alert alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                    
                                                            <div class="col-sm-9">
                                                                <div class="form-group">
                                                                    <label for="guest_detail[]">@lang('messages.Guest Name')  
                                                                        <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.Child guests must include the age on the back of their name. ex: Children Name(age)')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i>
                                                                    </label>
                                                                    <input type="text" name="guest_detail[]" class="form-control m-0 @error('guest_detail[]') is-invalid @enderror" placeholder="@lang('messages.Separate names with commas')" value="{{ $order_room->guest_name }}" required>
                                                                    @error('guest_detail[]')
                                                                        <div class="alert alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="special_day[]">@lang('messages.Special Day') <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.If during your stay there are guests who have special days such as birthdays, aniversaries, and others')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i> </label>
                                                                    <input type="text" name="special_day[]" class="form-control m-0 @error('special_day[]') is-invalid @enderror" placeholder="@lang('messages.ex: Birthday')" value="{{ $order_room->special_event }}">
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
                                                                    <select name="special_date[]" class="form-control">
                                                                        <option {{ $order_room->special_event_date?"":"selected"; }} value="">@lang('messages.None')</option>
                                                                        @foreach ($date_stay as $datestay)
                                                                            <option {{ date('Y-m-d',strtotime($order_room->special_event_date)) === date('Y-m-d',strtotime($datestay))?"selected":""; }} value="{{ date('Y-m-d',strtotime($datestay)) }}">{{ dateFormat($datestay) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4" style="place-self: padding-bottom: 6px;">
                                                                <div class="form-group">
                                                                    <label for="extra_bed_id[]">@lang('messages.Extra Bed') <span> * </span> 
                                                                        <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" 
                                                                        title="@lang('messages.Choose an extra bed if the room is occupied by more than 2 guests.')" 
                                                                        class="icon-copy fa fa-info-circle" aria-hidden="true"></i>
                                                                    </label><br>
                                                                    <select name="extra_bed_id[]" type="text" class="form-control @error('extra_bed_id[]') is-invalid @enderror">
                                                                        <option {{ !$order_room->extra_bed_id?"selected":"" }} value="">@lang('messages.None')</option>
                                                                        @foreach ($extrabeds as $eb)
                                                                            <option {{ $order_room->extra_bed_id === $eb->id?"selected":""; }} value="{{ $eb->id }}">{{ $eb->name }} ({{ $eb->type }})</option>
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
                                                </div>
                                            </li>
                                        @endforeach
                                        <div class="after-add-more"></div>
                                    </ol>
                                </div>
                                <div class="col-md-12">
                                    @if ($order->request_quotation == "Yes")
                                        <p class="m-t-8">
                                            @lang('messages.You have booked rooms for more than 8 units before and, we will contact you as soon as possible to confirm the order, after you submit this order, thank you.')
                                        </p>
                                    @endif
                                </div>
                        
                                <input type="hidden" name="status" value="Draft">
                                <input type="hidden" name="price_pax" value="{{ $order_room['price_pax'] }}">
                            </div>
                        </form>
                        <div class="card-box-footer">
                            <button id="add" type="button" class="btn btn-primary add-more"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> @lang('messages.Add More Room')</button>
                            <button type="submit" form="edit-order-room" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> @lang('messages.Update')</button>
                            <a href="{{ route('order-hotel-promo-edit.view',$order->id) }}">
                                <button type="button" class="btn btn-danger"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Cancel')</button>
                            </a>
                        </div>
                            <div class="copy hide">
                                <li class="m-b-8">
                                    <div class="control-group">
                                        <div class="room-container ">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="subtitle">
                                                        {{ $order->room->rooms }}
                                                    </div>
                                                    <button class="btn btn-remove remove"  type="button"><i class="icon-copy fa fa-close" aria-hidden="true"></i> </button>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="number_of_guests_room[]">@lang('messages.Number of Guests')</label>
                                                        <input type="number" min="1" max={{ $order->room->capacity }} name="number_of_guests_room[]" class="form-control m-0 @error('number_of_guests_room[]') is-invalid @enderror" placeholder="@lang('messages.Number of Guests')" required>
                                                        @error('number_of_guests_room[]')
                                                            <div class="alert alert-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="form-group">
                                                        <label for="guest_detail[]">@lang('messages.Guest Name') <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.Child guests must include the age on the back of their name. ex: Children Name(age)')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i>  </label>
                                                        <input type="text" name="guest_detail[]" class="form-control m-0 @error('guest_detail[]') is-invalid @enderror" placeholder="Separate names with commas" required>
                                                        @error('guest_detail[]')
                                                            <div class="alert alert-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="special_day[]">@lang('messages.Special Day')<i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.If during your stay there are guests who have special days such as birthdays, aniversaries, and others')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i> </label>
                                                        <input type="text" name="special_day[]" class="form-control m-0 @error('special_day[]') is-invalid @enderror" placeholder="@lang('messages.ex: Birthday')" >
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
                                                        <select name="special_date[]" class="form-control">
                                                            <option selected value="">@lang('messages.None')</option>
                                                            @foreach ($date_stay as $datestay)
                                                                <option value="{{ date('Y-m-d',strtotime($datestay)) }}">{{ dateFormat($datestay) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4" style="place-self: padding-bottom: 6px;">
                                                    <div class="form-group">
                                                        <label for="extra_bed_id[]">@lang('messages.Extra Bed') <span> * </span> <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="@lang('messages.Choose an extra bed if the room is occupied by more than 2 guests')" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></label><br>
                                                        <select name="extra_bed_id[]" type="text" class="form-control @error('extra_bed_id[]') is-invalid @enderror" required>
                                                            <option selected value="">@lang('messages.Select extra bed')</option>
                                                            <option value="0">@lang('messages.None')</option>
                                                            @foreach ($extrabeds as $ebr)
                                                                <option value="{{ $ebr->id }}">{{ $ebr->name }} ({{ $ebr->type }})</option>
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
                                    </div>
                                </li>
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    var jmlh_room = 8;
                                    var ro = 1;
                                    $(".add-more").click(function(){ 
                                        if(ro < jmlh_room){ 
                                            ro++;
                                            
                                            var html = $(".copy").html();
                                            $(".after-add-more").before(html);
                                        }
                                    });
                            
                                    $("body").on("click",".remove",function(){ 
                                        $(this).parents(".control-group").remove();
                                    });
                                });
                            </script>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
    

    