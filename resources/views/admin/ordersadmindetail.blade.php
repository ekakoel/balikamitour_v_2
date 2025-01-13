{{-- @include('layouts.loader') --}}

@php
    use Carbon\Carbon;
    $nor = $order->number_of_room;
    $nogr = json_decode($order->number_of_guests_room);
    $guest_detail = json_decode($order->guest_detail);
    $special_day = json_decode($order->special_day);
    $special_date = json_decode($order->special_date);
    $extra_bed = json_decode($order->extra_bed);
    $r=1;
    $room_price_normal = $nor * $order->normal_price;
    $kick_back = $order->kick_back;
    $room_price_total = $nor * $order->normal_price;
    $final_price = $order->final_price;
    $extra_bed_price = json_decode($order->extra_bed_price);
    $extra_bed_id = json_decode($order->extra_bed_id);
    if ($nor or $order->number_of_guests < 1) {
        $order->optional_price = 0;
    }
    if (isset($order->promotion)){
        $promotion_name = json_decode($order->promotion);
        $promotion_disc = json_decode($order->promotion_disc);
        $total_promotion_disc = array_sum($promotion_disc);
        $cpn = count($promotion_name);
    }else{
        $total_promotion_disc = 0;
    }
    
    if (isset($extra_bed_price)) {
        $total_extra_bed = array_sum($extra_bed_price);
    }else{
        $total_extra_bed = 0;
    }
    $price_per_pax = $order->price_pax * $order->duration;
    $total_room_and_suite = ($price_per_pax*$nor)+$total_extra_bed;
@endphp
@section('content')
    @extends('layouts.head')    
    <div class="mobile-menu-overlay"></div>
    @can('isAdmin')
        <div class="main-container">
            <div class="pd-ltr-20 xs-pd-20-10">
                <div class="min-height-200px">
                    <div class="page-header">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="title"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Validation Order</div>
                                <nav aria-label="breadcrumb" role="navigation">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="admin-panel">Admin Panel</a></li>
                                        <li class="breadcrumb-item"><a href="orders-admin">Orders Admin</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $order->orderno }}</li>
                                    </ol>
                                </nav>
                            </div>
                            
                        </div>
                    </div>
                    <div class="info-action">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-4 mobile">
                            <div class="row">
                                @include('layouts.attentions')
                                <div class="col-md-12">
                                    <div class="card-box">
                                        <div class="card-box-title">
                                            <div class="title">Status</div>
                                        </div> 
                                        <div class="order-status-container">
                                            @if ($order->status == "Active")
                                                <div class="status-active-color">Confirmed</div>
                                                @if ($reservation->send == "yes")
                                                    - <div class="send-email">Email <i class="icon-copy fa fa-envelope" aria-hidden="true"></i></div>
                                                @else
                                                    - <div class="not-send-email">Email <i class="icon-copy fa fa-envelope" aria-hidden="true"></i></div>
                                                @endif
                                                @if ($reservation->status == "Active")
                                                    - <div class="not-approved-order">Waiting <i class="icon-copy ion-clock"></i></div>
                                                @endif
                                            @elseif ($order->status == "Pending")
                                                <div class="status-pending-color">{{ $order->status }}</div>
                                            @elseif ($order->status == "Invalid")
                                                <div class="status-invalid-color">{{ $order->status }}</div>
                                            @elseif ($order->status == "Rejected")
                                                <div class="status-reject-color">{{ $order->status }}</div>
                                            @elseif ($order->status == "Confirmed")
                                                <div class="status-confirmed-color">{{ $order->status }}</div>
                                            @elseif ($order->status == "Approved")
                                                <div class="status-approved-color"><i class="icon-copy fa fa-check-circle" aria-hidden="true"></i> {{ $order->status }}</div>
                                                @if ($reservation->checkin > $now)
                                                    - <div class="standby-order"><p><i class="icon-copy ion-clock"> </i> {{ dateFormat($order->checkin) }}</p></div>
                                                @elseif ($reservation->checkin <= $now and $reservation->checkout > $now)
                                                    - <div class="ongoing-order">Ongoing <i class="icon-copy ion-android-walk"></i></div>
                                                @else
                                                    - <div class="final-order">Final</div>
                                                @endif
                                            @elseif($order->status == "Paid")
                                                <div class="status-paid-color"><i class="icon-copy fa fa-check-circle" aria-hidden="true"></i> {{ $order->status }} ({{ dateFormat($receipt->payment_date) }})</div>
                                            @else
                                                <div class="status-draf-color">{{ $order->status }}</div>
                                            @endif
                                        </div>
                                        @if (count($orderlogs)>0)
                                            <hr class="form-hr">
                                            <p><b>Order Log:</b></p>
                                            <table class="table tb-list">
                                                @foreach ($orderlogs as $no=>$orderlog)
                                                    @php
                                                        $adminorder = $admins->where('id',$orderlog->admin)->first();
                                                    @endphp
                                                    <tr>
                                                        <td>{{ ++$no.". " }}</td>
                                                        <td> {!! dateTimeFormat($orderlog->created_at) !!}</td>
                                                        <td>{!! $adminorder->code !!}</td>
                                                        <td><i>{!! $orderlog->action !!}</i></td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </div>
                                </div>
                                @if (count($order_notes)>0)
                                    <div class="col-md-12">
                                        {{-- ORDER NOTE --}}
                                        <div class="card-box">
                                            <div class="card-box-title">
                                                <div class="title">Order Note</div>
                                            </div> 
                                            @foreach ($order_notes as $order_note)
                                                <div class="container-order-note">
                                                    @php
                                                        $operator = Auth::user()->where('id',$order_note->user_id)->first();
                                                    @endphp
                                                    <p><b>{{ dateTimeFormat($order_note->created_at)." - ".$operator->name }}</b> (<i>{{ $order_note->status }}</i>)</p>
                                                    <p class="m-l-18">{!! $order_note->note !!}</p>
                                                    
                                                    <hr class="form-hr">
                                                </div>
                                            @endforeach
                                            @if ($order->status !== "Paid")
                                                <div class="card-box-footer">
                                                    <a href="modal" data-toggle="modal" data-target="#add-order-note-mobile"><button type="button" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Note</button></a>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- MODAL ORDER NOTE --}}
                                        <div class="modal fade" id="add-order-note-mobile" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="card-box">
                                                        <div class="card-box-title">
                                                            <div class="title"><i class="fa fa-plus" aria-hidden="true"></i> Add Note</div>
                                                        </div>
                                                        <form id="faddAddNoteMobile" action="/fadd-order-note-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="form-group row">
                                                                <label for="status" class="col-sm-12">Type</label>
                                                                <div class="col-sm-12">
                                                                    <select name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status') }}">
                                                                        <option selected value="Urgent">Urgent</option>
                                                                        <option value="Waiting">Waiting</option>
                                                                        <option value="Error">Error</option>
                                                                        <option value="Cancel">Cancel</option>
                                                                        <option value="Reject">Reject</option>
                                                                        <option value="Info">Info</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="order_note" class="col-sm-12">Note</label>
                                                                <div class="col-sm-12">
                                                                    <textarea id="order_note" name="order_note" placeholder="Insert order note" class="ckeditor form-control border-radius-0" autofocus required></textarea>
                                                                    @error('order_note')
                                                                        <div class="alert alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="user_id" value="{{ Auth::User()->id }}">
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                        </Form>
                                                        <div class="card-box-footer">
                                                            <div class="form-group">
                                                                <button type="submit" form="faddAddNoteMobile" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Submit</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- RECEIPT --}}
                                
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card-box">
                                <div class="card-box-title">
                                    <div class="title">
                                        Order {{ $order->orderno }}
                                    </div>
                                </div>
                                <div class="product-detail-wrap">
                                    <div class="row">
                                        <div class="col-6 col-md-6">
                                            <div class="order-bil text-left">
                                                <img src="/storage/logo/logo-color-bali-kami.png"alt="Bali Kami Tour & Travel">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6 text-right flex-end">
                                            <div class="label-title">ORDER</div>
                                        </div>
                                        <div class="col-md-12 text-right">
                                            <div class="label-date" style="width: 100%;">
                                                {{ dateFormat($order->created_at) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 m-t-8">
                                            <div class="business-name">{{ $business->name }}</div>
                                            <div class="bussines-sub">{{ $business->caption }}</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- CONFIRMATION NUMBER --}}
                                        @if ($order->status == "Pending")
                                            <div class="col-md-12">
                                                <div class="tab-inner-title {{ $order->confirmation_order?"":"empty-value" }}">
                                                    Confirmation Number
                                                </div>
                                                @if ($order->status != "Approved")
                                                    @if ($order->status != "Paid")
                                                        @if (!$order->confirmation_order)
                                                            <form id="updateConfirmationNumber" action="fupdate-confirmation-number-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="form-container-confirmation">
                                                                    <div class="form-group">
                                                                        <div class="btn-icon">
                                                                            <span><i class="icon-copy fi-key"></i></span>
                                                                            <input name="confirmation_order" type="text" value="{{ $order->confirmation_order }}" class="form-control input-icon @error('confirmation_order') is-invalid @enderror" placeholder="Confirmation Numbber" required>
                                                                        </div>
                                                                        @error('confirmation_order')
                                                                            <span class="invalid-feedback">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-btn">
                                                                        @if ($order->confirmation_order)
                                                                            <button type="submit" form="updateConfirmationNumber" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> @lang("messages.Update")</button>
                                                                        @else
                                                                            <button type="submit" form="updateConfirmationNumber" class="btn btn-primary"><i class="icon-copy fa fa-plus-circle" aria-hidden="true"></i> @lang("messages.Add")</button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        @else
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card-ptext-margin">
                                                                        <div class="row">
                                                                            @if ($order->handled_by != Auth::user()->id)
                                                                                <div class="col-md-12 text-center">
                                                                                    <div class="title">{{ $order->confirmation_order }}</div><br>
                                                                                    <hr class="form-hr">
                                                                                </div>
                                                                            @endif
                                                                            <div class="col-md-6">
                                                                                <div class="card-ptext-content">
                                                                                    <div class="ptext-title">Handled by</div>
                                                                                    <div class="ptext-value">{{ $handled_by->name }}</div>
                                                                                    <div class="ptext-title">Date</div>
                                                                                    <div class="ptext-value"><i>{{ dateTimeFormat($order->handled_date) }}</i></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if ($order->handled_by == Auth::user()->id)
                                                                <form id="updateConfirmationNumber" action="fupdate-confirmation-number-{{ $order->id }}" method="POST">
                                                                    @csrf
                                                                    @method("PUT")
                                                                    <div class="form-container-confirmation">
                                                                        <div class="form-group">
                                                                            <div class="btn-icon">
                                                                                <span><i class="icon-copy fi-key"></i></span>
                                                                                <input name="confirmation_order" type="text" value="{{ $order->confirmation_order }}" class="form-control input-icon @error('confirmation_order') is-invalid @enderror" placeholder="Confirmation Numbber" required>
                                                                            </div>
                                                                            @error('confirmation_order')
                                                                                <span class="invalid-feedback">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div>
                                                                        @if ($order->handled_by == Auth::user()->id)
                                                                            <div class="form-btn">
                                                                                @if ($order->confirmation_order)
                                                                                    <button type="submit" form="updateConfirmationNumber" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> @lang("messages.Update")</button>
                                                                                @else
                                                                                    <button type="submit" form="updateConfirmationNumber" class="btn btn-primary"><i class="icon-copy fa fa-plus-circle" aria-hidden="true"></i> @lang("messages.Add")</button>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @else
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="description author">
                                                                <b>Confirmation Number: </b><br>
                                                                <b>Handled by:</b><br>
                                                                <b>Date: </b><br>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 text-right">
                                                            <div class="description author">
                                                                <b style="text-transform: uppercase;">{{ $order->confirmation_order }} </b><br>
                                                                <b>{{ $handled_by->name }} </b><br>
                                                                <i>{{ dateTimeFormat($order->handled_date) }}</i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="col-md-12">
                                                <div class="page-subtitle {{ $order->confirmation_order?"":"empty-value" }}">
                                                    Confirmation Number
                                                </div>
                                                <div class="card-ptext-margin">
                                                    <div class="row">
                                                        <div class="col-12 text-center">
                                                            <b style="text-transform: uppercase;">{{ $order->confirmation_order }} </b><br>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <hr class="form-hr">
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="description author">
                                                                <b>Handled by: </b><br>
                                                                <b>Date: </b><br>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 text-right">
                                                            <div class="description author">
                                                                <b>{{ $handled_by->name }} </b><br>
                                                                <i>{{ dateTimeFormat($order->handled_date) }}</i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        {{-- RESERVATION  --}}
                                        <div class="col-md-6">
                                            <div class="tab-inner-title">Reservation
                                                @if ($order->handled_by)
                                                    @if ($order->handled_by == Auth::user()->id)
                                                        @if ($order->status != "Paid")
                                                            <span>
                                                                <a href="modal" data-toggle="modal" data-target="#update-reservation-{{ $reservation->id }}"> 
                                                                    <i class="icon-copy  fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit Reservation" aria-hidden="true"></i>
                                                                </a>
                                                            </span>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if ($order->status != "Paid")
                                                        <span>
                                                            <a href="modal" data-toggle="modal" data-target="#update-reservation-{{ $reservation->id }}"> 
                                                                <i class="icon-copy  fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit Reservation" aria-hidden="true"></i>
                                                            </a>
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                            {{-- MODAL UPDATE RESERVATION  --}}
                                            @if ($order->status != "Paid")
                                                <div class="modal fade" id="update-reservation-{{ $reservation->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content text-left">
                                                            <div class="card-box">
                                                                <div class="card-box-title">
                                                                    <div class="subtitle"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit Reservation</div>
                                                                </div>
                                                                <form id="updateReservation" action="/fupdate-reservation-pickup-name/{{ $reservation->id }}" method="post" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group row">
                                                                                <div class="col-md-6">
                                                                                    <label>In - Out</label>
                                                                                    <input readonly id="checkincout" name="checkincout" class="form-control @error('checkincout') is-invalid @enderror" type="text" placeholder="@lang('messages.Select date')" value="{{ dateFormat($reservation->checkin)." - ". dateFormat($reservation->checkout) }}" required>
                                                                                    @error('checkincout')
                                                                                        <span class="invalid-feedback">
                                                                                            {{ $message }}
                                                                                        </span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group">
                                                                                <label for="pickup_name">Pick up Name</label>
                                                                                    <select name="pickup_name" class="form-control @error('pickup_name') is-invalid @enderror">
                                                                                        @if ($reservation->pickup_name)
                                                                                            @php
                                                                                                $gst = $guests->where('id', $reservation->pickup_name)->first();
                                                                                            @endphp
                                                                                            @if (isset($gst))
                                                                                                <option selected value="{{ $gst->id }}">{{ $gst->name }}</option>
                                                                                            @else
                                                                                                <option selected value="">Select Guest</option>
                                                                                            @endif
                                                                                        @else
                                                                                            <option selected value="">Select Guest</option>
                                                                                        @endif
                                                                                        @foreach ($guests as $pname)
                                                                                            <option value="{{ $pname->id }}">{{ $pname->name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                @error('pickup_name')
                                                                                    <div class="alert-form">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                <div class="card-box-footer">
                                                                    <button type="submit" form="updateReservation" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Update</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="card-ptext-margin">
                                                <div class="card-ptext-content">
                                                    <div class="ptext-title">Reservation</div>
                                                    <div class="ptext-value">{{  $reservation->rsv_no }}</div>
                                                    <div class="ptext-title">Reservation Date</div>
                                                    <div class="ptext-value">{{ dateFormat($reservation->created_at) }}</div>
                                                    <div class="ptext-title">In & Out</div>
                                                    <div class="ptext-value">{{ dateFormat($reservation->checkin)." - ".dateFormat($reservation->checkout) }}</div>
                                                    <div class="ptext-title">Pick up Name</div>
                                                    <div class="ptext-value">
                                                        @if ($reservation->pickup_name)
                                                            @php
                                                                $gst_pname = $guests->where('id', $reservation->pickup_name)->first();
                                                            @endphp
                                                            @if (isset($gst_pname))
                                                                @if ($gst_pname->sex == "m")
                                                                    {{ "Mr. ". $gst_pname->name }}
                                                                @else
                                                                    {{ "Ms. ". $gst_pname->name }}
                                                                @endif
                                                            @else
                                                                ..........................
                                                            @endif
                                                        @else
                                                            ..........................
                                                        @endif
                                                    </div>
                                                    <div class="ptext-title">Phone</div>
                                                    <div class="ptext-value">
                                                        @if ($reservation->pickup_name)
                                                            @php
                                                                $gst_pphone = $guests->where('id', $reservation->pickup_name)->first();
                                                            @endphp
                                                            @if (isset($gst_pphone))
                                                                {{  $gst_pphone->phone }}
                                                            @else
                                                                ..........................
                                                            @endif
                                                        @else
                                                            ..........................
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($order->service == "Activity" or $order->service == "Tour Package" or $order->service == "Transport")
                                            {{-- PICK UP AND DROP OFF --}}
                                            <div class="col-md-6">
                                                <div class="tab-inner-title ">Pick up and Drop off
                                                    @if ($order->handled_by)
                                                        @if ($order->handled_by == Auth::user()->id)
                                                            @if ($order->status != "Paid")
                                                                @if ($reservation->status != "Active")
                                                                    <span>
                                                                        <a href="modal" data-toggle="modal" data-target="#updatePickupDropoff-{{ $order->id }}"> 
                                                                            <i class="icon-copy  fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit Pick up and Drop off" aria-hidden="true"></i>
                                                                        </a>
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if ($order->status != "Paid")
                                                            @if ($reservation->status != "Active")
                                                                <span>
                                                                    <a href="modal" data-toggle="modal" data-target="#updatePickupDropoff-{{ $order->id }}"> 
                                                                        <i class="icon-copy  fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit Pick up and Drop off" aria-hidden="true"></i>
                                                                    </a>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                                {{-- Modal Update PICK UP AND DROP OFF --}}
                                                @if ($order->status != "Paid")
                                                    <div class="modal fade" id="updatePickupDropoff-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content text-left">
                                                                <div class="card-box">
                                                                        <div class="card-box-title">
                                                                            <div class="subtitle"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit Pick up and Drop off</div>
                                                                        </div>
                                                                        <form id="update-pickup-dropoff-{{ $order->id }}" action="/fupdate-pickup-dropoff-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                                            @csrf
                                                                            @method('put')
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="pickup_date">Pick up Date</label>
                                                                                        <input readonly type="text" name="pickup_date" class="form-control datetimepicker @error('pickup_date') is-invalid @enderror" placeholder="Select pick up date" value="{{ dateTimeFormat($order->pickup_date) }}">
                                                                                       
                                                                                        @error('pickup_date')
                                                                                            <div class="alert-form">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="pickup_location">Pick up Location</label>
                                                                                        <input type="text" name="pickup_location" class="form-control @error('pickup_location') is-invalid @enderror" placeholder="Insert location" value="{{ $order->pickup_location }}">
                                                                                        @error('pickup_location')
                                                                                            <div class="alert-form">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="dropoff_date">Drop off Date</label>
                                                                                        <input readonly type="text" name="dropoff_date" class="form-control datetimepicker @error('dropoff_date') is-invalid @enderror" placeholder="Select pick up date" value="{{ dateTimeFormat($order->dropoff_date) }}">
                                                                                       
                                                                                        @error('dropoff_date')
                                                                                            <div class="alert-form">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="dropoff_location">Drop off Location</label>
                                                                                        <input type="text" name="dropoff_location" class="form-control @error('dropoff_location') is-invalid @enderror" placeholder="Insert location" value="{{ $order->dropoff_location }}">
                                                                                        @error('dropoff_location')
                                                                                            <div class="alert-form">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                        <div class="card-box-footer">
                                                                            <button type="submit" form="update-pickup-dropoff-{{ $order->id }}" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Update</button>
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="card-ptext-margin">
                                                    <div class="card-ptext-content">
                                                        <div class="ptext-title">Pick up Date</div>
                                                        @if ($order->pickup_date)
                                                            <div class="ptext-value">{{ dateTimeFormat($order->pickup_date) }}</div>
                                                        @else
                                                            <div class="ptext-value">..........................</div>
                                                        @endif
                                                        <div class="ptext-title">Pick up Location</div>
                                                        @if ($order->pickup_location)
                                                            <div class="ptext-value">{{ $order->pickup_location }}</div>
                                                        @else
                                                            <div class="ptext-value">..........................</div>
                                                        @endif
                                                        <div class="ptext-title">Drop off Date</div>
                                                        @if ($order->dropoff_date)
                                                            <div class="ptext-value">{{ dateTimeFormat($order->dropoff_date) }}</div>
                                                        @else
                                                            <div class="ptext-value">..........................</div>
                                                        @endif
                                                        <div class="ptext-title">Drop off Location</div>
                                                        @if ($order->dropoff_location)
                                                            <div class="ptext-value">{{ $order->dropoff_location }}</div>
                                                        @else
                                                            <div class="ptext-value">..........................</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{-- FLIGHT --}}
                                            <div class="col-md-6">
                                                <div class="tab-inner-title ">Flight
                                                    @if ($order->handled_by)
                                                        @if ($order->handled_by == Auth::user()->id)
                                                            @if ($order->status != "Paid")
                                                                <span>
                                                                    @if ($order->arrival_flight or $order->arrival_time or $order->departure_flight or $order->departure_time)
                                                                        <a href="modal" data-toggle="modal" data-target="#update-flight-{{ $order->id }}"> 
                                                                            <i class="icon-copy  fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit Flight" aria-hidden="true"></i>
                                                                        </a>
                                                                    @else
                                                                        <a href="modal" data-toggle="modal" data-target="#update-flight-{{ $order->id }}"> 
                                                                            <i class="icon-copy fa fa-plus-circle" data-toggle="tooltip" data-placement="top" title="Add Flight" aria-hidden="true"></i>
                                                                        </a>
                                                                    @endif
                                                                    
                                                                </span>
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if ($order->status != "Paid")
                                                            <span>
                                                                @if ($order->arrival_flight or $order->arrival_time or $order->departure_flight or $order->departure_time)
                                                                    <a href="modal" data-toggle="modal" data-target="#update-flight-{{ $order->id }}"> 
                                                                        <i class="icon-copy  fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit Flight" aria-hidden="true"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="modal" data-toggle="modal" data-target="#update-flight-{{ $order->id }}"> 
                                                                        <i class="icon-copy fa fa-plus-circle" data-toggle="tooltip" data-placement="top" title="Add Flight" aria-hidden="true"></i>
                                                                    </a>
                                                                @endif
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                                {{-- Modal Update Flight --------------------------------------------------------------------------------------------------------------- --}}
                                                @if ($order->status != "Paid")
                                                    <div class="modal fade" id="update-flight-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content text-left">
                                                                <div class="card-box">
                                                                        <div class="card-box-title">
                                                                            @if ($order->arrival_flight or $order->arrival_time or $order->departure_flight or $order->departure_time)
                                                                                <div class="subtitle"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit Flight</div>
                                                                            @else
                                                                                <div class="subtitle"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Flight</div>
                                                                            @endif
                                                                        </div>
                                                                        <form id="updateFlight-{{ $order->id }}" action="/fupdate-flight-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                                            @csrf
                                                                            @method('put')
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="arrival_flight">Arrival Flight</label>
                                                                                        <input type="text" name="arrival_flight" class="form-control @error('arrival_flight') is-invalid @enderror" placeholder="Insert arrival flight" value="{{ $order->arrival_flight }}">
                                                                                        @error('arrival_flight')
                                                                                            <div class="alert-form">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="arrival_time">Arrival Time </label>
                                                                                        <input readonly type="text" name="arrival_time" class="form-control datetimepicker @error('arrival_time') is-invalid @enderror" placeholder="Select arrival date and time" value="{{ $order->arrival_time }}">
                                                                                        @error('arrival_time')
                                                                                            <div class="alert-form">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="departure_flight">Departure Flight</label>
                                                                                        <input type="text" name="departure_flight" class="form-control @error('departure_flight') is-invalid @enderror" placeholder="Insert arrival flight" value="{{ $order->departure_flight }}">
                                                                                        @error('departure_flight')
                                                                                            <div class="alert-form">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="departure_time">Departure Time </label>
                                                                                        <input readonly type="text" name="departure_time" class="form-control datetimepicker @error('departure_time') is-invalid @enderror" placeholder="Select departure date and time" value="{{ $order->departure_time }}">
                                                                                        @error('departure_time')
                                                                                            <div class="alert-form">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                        <div class="card-box-footer">
                                                                            @if ($order->arrival_flight or $order->arrival_time or $order->departure_flight or $order->departure_time)
                                                                                <button form="updateFlight-{{ $order->id }}" type="submit" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Update</button>
                                                                            @else
                                                                                <button form="updateFlight-{{ $order->id }}" type="submit" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                                            @endif
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="card-ptext-margin">
                                                    <div class="card-ptext-content">
                                                        <div class="ptext-title">Arrival Flight</div>
                                                        @if ($reservation->arrival_flight)
                                                            <div class="ptext-value">{{ $reservation->arrival_flight }}</div>
                                                        @else
                                                            <div class="ptext-value">-</div>
                                                        @endif
                                                        <div class="ptext-title">Arrival Time</div>
                                                        @if ($reservation->arrival_time)
                                                            <div class="ptext-value">{{ $reservation->arrival_time }}</div>
                                                        @else
                                                            <div class="ptext-value">-</div>
                                                        @endif
                                                        <div class="ptext-title">Departure Flight</div>
                                                        @if ($reservation->departure_flight)
                                                            <div class="ptext-value">{{ $reservation->departure_flight }}</div>
                                                        @else
                                                            <div class="ptext-value">-</div>
                                                        @endif
                                                        <div class="ptext-title">Departure Time</div>
                                                        @if ($reservation->departure_time)
                                                            <div class="ptext-value">{{ $reservation->departure_time }}</div>
                                                        @else
                                                            <div class="ptext-value">-</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        {{-- AGENT --}}
                                        <div class="col-md-6">
                                            <div class="tab-inner-title ">Agent</div>
                                            <div class="card-ptext-margin">
                                                <div class="card-ptext-content">
                                                    <div class="ptext-title">Name</div>
                                                    <div class="ptext-value">
                                                        @if ($agent->name == "")
                                                            <p class="form-notif">Not available!</p>
                                                        @else
                                                            {{ $agent->name }}
                                                        @endif
                                                    </div>
                                                    <div class="ptext-title">Office</div>
                                                    <div class="ptext-value">
                                                        @if ($agent->office == "")
                                                            <p class="form-notif">Not available!</p>
                                                        @else
                                                            {{ $agent->office }}
                                                        @endif
                                                    </div>
                                                    <div class="ptext-title">Phone</div>
                                                    <div class="ptext-value">
                                                        @if ($agent->phone == "")
                                                            <p class="form-notif">Not available!</p>
                                                        @else
                                                            {{ $agent->phone }}
                                                        @endif
                                                    </div>
                                                    <div class="ptext-title">Email</div>
                                                    <div class="ptext-value">
                                                        @if ($agent->email == "")
                                                            <p class="form-notif">Not available!</p>
                                                        @else
                                                            {{ $agent->email }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- GUEST--}}
                                        <div class="col-md-6">
                                            <div class="tab-inner-title ">Guest
                                                @if ($order->handled_by)
                                                    @if ($order->handled_by == Auth::user()->id)
                                                        @if ($order->status != "Paid")
                                                            <span>
                                                                <a href="modal" data-toggle="modal" data-target="#add-guests-{{ $reservation->id }}"> 
                                                                    <i class="icon-copy fa fa-plus-circle" data-toggle="tooltip" data-placement="left" title="Add Guest" aria-hidden="true"></i>
                                                                </a>
                                                            </span>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if ($order->status != "Paid")
                                                        <span>
                                                            <a href="modal" data-toggle="modal" data-target="#add-guests-{{ $reservation->id }}"> 
                                                                <i class="icon-copy fa fa-plus-circle" data-toggle="tooltip" data-placement="left" title="Add Guest" aria-hidden="true"></i>
                                                            </a>
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                            {{-- Modal Add Guest --------------------------------------------------------------------------------------------------------------- --}}
                                            @if ($order->status != "Paid")
                                                <div class="modal fade" id="add-guests-{{ $reservation->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content text-left">
                                                            <div class="card-box">
                                                                <div class="card-box-title">
                                                                    <div class="subtitle"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Guest</div>
                                                                </div>
                                                                <form id="addGuest" action="/fadd-guest" method="post" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="name" class="col-sm-12 col-md-12 col-form-label">Name <span>*</span></label>
                                                                                <div class="col-sm-12">
                                                                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Insert guest name" value="{{ old('name') }}" required>
                                                                                </div>
                                                                                @error('name')
                                                                                    <div class="alert-form">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="name_mandarin" class="col-sm-12 col-md-12 col-form-label">Mandarin Name </label>
                                                                                <div class="col-sm-12">
                                                                                <input type="text" name="name_mandarin" class="form-control @error('name_mandarin') is-invalid @enderror" placeholder="Insert guest name" value="{{ old('name_mandarin') }}">
                                                                                </div>
                                                                                @error('name_mandarin')
                                                                                    <div class="alert-form">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="sex" class="col-sm-12 col-md-12 col-form-label">Gender <span>*</span></label>
                                                                                <div class="col-sm-12">
                                                                                    <select name="sex" class="form-control @error('sex') is-invalid @enderror" value="{{ old('sex') }}" required>
                                                                                        <option selected value="">Select</option>
                                                                                        <option value="m">Male</option>
                                                                                        <option value="f">Female</option>
                                                                                    </select>
                                                                                    @error('sex')
                                                                                        <div class="alert-form">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="phone" class="col-sm-12 col-md-12 col-form-label">Phone Number</label>
                                                                                <div class="col-sm-12">
                                                                                <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Insert phone number" value="{{ old('phone') }}">
                                                                                </div>
                                                                                @error('phone')
                                                                                    <div class="alert-form">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" name="rsv_id" value="{{ $reservation->id }}">
                                                                    </div>
                                                                </form>
                                                                <div class="card-box-footer">
                                                                    <button type="submit" form="addGuest" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if (count($guests) > 0)
                                                <div class="card-ptext-margin">
                                                    <table class="table tb-list" style="padding: 0 !important">
                                                        @foreach ($guests as $number=>$guest)
                                                            <form id="deleteGuest{{ $guest->id }}" action="/delete-guest/{{ $guest->id }}" method="post">
                                                                @csrf
                                                                @method('delete')
                                                            </form>
                                                            <tr>
                                                                <td style="padding: 0 !important">
                                                                    <div class="reservation-guest">
                                                                        @if ($guest->sex == "m")
                                                                            {{ ++$number.". " }}Mr. {{ $guest->name }} @if ($guest->date_of_birth)
                                                                                {{ " (".dateFormat($guest->date_of_birth).") (". Carbon::parse($guest->date_of_birth)->age.")" }}
                                                                            @endif
                                                                        @else
                                                                            @if (Carbon::parse($guest->date_of_birth)->age > 17)
                                                                                {{ ++$number.". " }}Ms. {{ $guest->name }} @if ($guest->date_of_birth)
                                                                                    {{ " (".dateFormat($guest->date_of_birth).") (". Carbon::parse($guest->date_of_birth)->age.")" }}
                                                                                @endif
                                                                            @else
                                                                                {{ ++$number.". " }}Ms. {{ $guest->name }} @if ($guest->date_of_birth)
                                                                                    {{ " (".dateFormat($guest->date_of_birth).") (". Carbon::parse($guest->date_of_birth)->age.")" }}
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                        
                                                                        @if ($order->handled_by)
                                                                            @if ($order->handled_by == Auth::user()->id)
                                                                                @if ($order->status != "Paid")
                                                                                    <span>
                                                                                        <a href="modal" data-toggle="modal" data-target="#edit-guest-{{ $guest->id }}"> 
                                                                                            <button class="btn btn-update" data-toggle="tooltip" data-placement="left" title="Edit {{ $guest->name }}"><i class="icon-copy fa fa-pencil p-0"></i></button>
                                                                                        </a>
                                                                                        <button form="deleteGuest{{ $guest->id }}" class="btn btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="left" title="Delete {{ $guest->name }}"><i class="icon-copy fa fa-trash p-0"></i></button>
                                                                                    </span>
                                                                                @endif
                                                                            @endif
                                                                        @else
                                                                            @if ($order->status == "Pending")
                                                                                <span>
                                                                                    <a href="modal" data-toggle="modal" data-target="#edit-guest-{{ $guest->id }}"> 
                                                                                        <button form="deleteGuest{{ $guest->id }}" class="btn btn-update" data-toggle="tooltip" data-placement="left" title="Edit {{ $guest->name }}"><i class="icon-copy fa fa-pencil p-0"></i></button>
                                                                                    </a>
                                                                                </span>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                                {{-- Modal Edit Guest --------------------------------------------------------------------------------------------------------------- --}}
                                                @if ($order->status != "Paid")
                                                    <div class="modal fade" id="edit-guest-{{ $guest->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content text-left">
                                                                <div class="card-box">
                                                                    <div class="card-box-title">
                                                                        <div class="subtitle"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Edit Guest</div>
                                                                    </div>
                                                                    <form id="updateGuest-{{ $guest->id }}" action="/fupdate-guest/{{ $guest->id }}" method="post" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('put')
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group row">
                                                                                    <label for="name" class="col-sm-12 col-md-12 col-form-label">Name <span>*</span></label>
                                                                                    <div class="col-sm-12">
                                                                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Insert guest name" value="{{ $guest->name }}" required>
                                                                                    </div>
                                                                                    @error('name')
                                                                                        <div class="alert-form">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group row">
                                                                                    <label for="name_mandarin" class="col-sm-12 col-md-12 col-form-label">Mandarin Name </label>
                                                                                    <div class="col-sm-12">
                                                                                    <input type="text" name="name_mandarin" class="form-control @error('name_mandarin') is-invalid @enderror" placeholder="Insert guest name" value="{{ $guest->name_mandarin }}">
                                                                                    </div>
                                                                                    @error('name_mandarin')
                                                                                        <div class="alert-form">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-2">
                                                                                <div class="form-group row">
                                                                                    <label for="sex" class="col-sm-12 col-md-12 col-form-label">Gender <span>*</span></label>
                                                                                    <div class="col-sm-12">
                                                                                        <select name="sex" class="form-control @error('sex') is-invalid @enderror" required>
                                                                                            <option selected value="{{ $guest->sex }}">@if ($guest->sex == "m")Male @else Female @endif</option>
                                                                                            @if ($guest->sex == "m")
                                                                                                <option value="f">Female</option>
                                                                                            @else
                                                                                                <option value="m">Male</option>
                                                                                            @endif
                                                                                        </select>
                                                                                        @error('sex')
                                                                                            <div class="alert-form">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group row">
                                                                                    <label for="date_of_birth" class="col-sm-12 col-md-12 col-form-label">Date of Birth</label>
                                                                                    <div class="col-sm-12">
                                                                                    <input readonly type="text" name="date_of_birth" class="form-control date-picker @error('date_of_birth') is-invalid @enderror" placeholder="Date of birth" value="{{ $guest->date_of_birth }}">
                                                                                    </div>
                                                                                    @error('date_of_birth')
                                                                                        <div class="alert-form">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group row">
                                                                                    <label for="phone" class="col-sm-12 col-md-12 col-form-label">Phone Number</label>
                                                                                    <div class="col-sm-12">
                                                                                    <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Insert phone number" value="{{ $guest->phone }}">
                                                                                    </div>
                                                                                    @error('phone')
                                                                                        <div class="alert-form">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                    <div class="card-box-footer">
                                                                        <button type="submit" form="updateGuest-{{ $guest->id }}" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Update</button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="card-ptext-margin">-</div>
                                            @endif
                                            {{-- Modal Add Guest --------------------------------------------------------------------------------------------------------------- --}}
                                            @if ($reservation->status != "Active")
                                                <div class="modal fade" id="add-guests-{{ $reservation->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content text-left">
                                                            <div class="card-box">
                                                                <div class="card-box-title">
                                                                    <div class="subtitle"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Guest</div>
                                                                </div>
                                                                
                                                                <form id="addGuest" action="/fadd-guest" method="post" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="name" class="col-sm-12 col-md-12 col-form-label">Name <span>*</span></label>
                                                                                <div class="col-sm-12">
                                                                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Insert guest name" value="{{ old('name') }}" required>
                                                                                </div>
                                                                                @error('name')
                                                                                    <div class="alert-form">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="name_mandarin" class="col-sm-12 col-md-12 col-form-label">Mandarin Name </label>
                                                                                <div class="col-sm-12">
                                                                                <input type="text" name="name_mandarin" class="form-control @error('name_mandarin') is-invalid @enderror" placeholder="Insert guest name" value="{{ old('name_mandarin') }}">
                                                                                </div>
                                                                                @error('name_mandarin')
                                                                                    <div class="alert-form">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <div class="form-group row">
                                                                                <label for="sex" class="col-sm-12 col-md-12 col-form-label">Gender <span>*</span></label>
                                                                                <div class="col-sm-12">
                                                                                    <select name="sex" class="form-control @error('sex') is-invalid @enderror" value="{{ old('sex') }}" required>
                                                                                        <option selected value="">Select</option>
                                                                                        <option value="m">Male</option>
                                                                                        <option value="f">Female</option>
                                                                                    </select>
                                                                                    @error('sex')
                                                                                        <div class="alert-form">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group row">
                                                                                <label for="date_of_birth" class="col-sm-12 col-md-12 col-form-label">Date of Birth</label>
                                                                                <div class="col-sm-12">
                                                                                <input readonly type="text" name="date_of_birth" class="form-control date-picker @error('date_of_birth') is-invalid @enderror" placeholder="Date of birth" value="{{ old('date_of_birth') }}">
                                                                                </div>
                                                                                @error('date_of_birth')
                                                                                    <div class="alert-form">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="phone" class="col-sm-12 col-md-12 col-form-label">Phone Number</label>
                                                                                <div class="col-sm-12">
                                                                                <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Insert phone number" value="{{ old('phone') }}">
                                                                                </div>
                                                                                @error('phone')
                                                                                    <div class="alert-form">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        
                                                                            <input type="hidden" name="rsv_id" value="{{ $reservation->id }}">
                                                                    </div>
                                                                </form>
                                                                <div class="card-box-footer">
                                                                    <button type="submit" form="addGuest" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- GUIDE --}}
                                        <div class="col-md-6">
                                            <div class="tab-inner-title ">Guide
                                                @if ($order->handled_by)
                                                    @if ($order->handled_by == Auth::user()->id)
                                                        @if ($order->status != "Paid")
                                                            @if (!$guideOrder)
                                                                <span>
                                                                    <a href="modal" data-toggle="modal" data-target="#add-guide-{{ $order->id }}"> 
                                                                        <i class="icon-copy fa fa-plus-circle" data-toggle="tooltip" data-placement="left" title="Add Guide" aria-hidden="true"></i>
                                                                    </a>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @else
                                                    @if ($order->status != "Paid")
                                                        @if (!$guideOrder)
                                                            <span>
                                                                <a href="modal" data-toggle="modal" data-target="#add-guide-{{ $order->id }}"> 
                                                                    <i class="icon-copy fa fa-plus-circle" data-toggle="tooltip" data-placement="left" title="Add Guide" aria-hidden="true"></i>
                                                                </a>
                                                            </span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                            @if ($guideOrder)
                                                <form id="deleteGuideOrder{{ $order->id }}" action="/fdelete-guide-order-{{ $order->id }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                </form>
                                                <div class="card-ptext-margin">
                                                    <div class="reservation-guest">
                                                        @if ($guideOrder->sex == "f")
                                                            Ms. {{ $guideOrder->name }}
                                                        @else
                                                            Mr. {{ $guideOrder->name }}
                                                        @endif
                                                        <i>({{ $guideOrder->language }})</i>
                                                        @if ($order->handled_by)
                                                            @if ($order->handled_by == Auth::user()->id)
                                                                @if ($order->status != "Paid")
                                                                    <span>
                                                                        <a href="modal" data-toggle="modal" data-target="#edit-guide-{{ $order->id }}"> 
                                                                            <button class="btn btn-update" data-toggle="tooltip" data-placement="left" title="Edit {{ $guideOrder->name }}"><i class="icon-copy fa fa-pencil p-0"></i></button>
                                                                        </a>
                                                                        <button  class="btn btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="left" title="Delete {{ $guideOrder->name }}"><i class="icon-copy fa fa-trash p-0"></i></button>
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if ($order->status != "Paid")
                                                                <span>
                                                                    <a href="modal" data-toggle="modal" data-target="#edit-guide-{{ $order->id }}"> 
                                                                        <button class="btn btn-update" data-toggle="tooltip" data-placement="left" title="Edit {{ $guideOrder->name }}"><i class="icon-copy fa fa-pencil"></i></button>
                                                                    </a>
                                                                    <button form="deleteGuideOrder{{ $order->id }}" class="btn btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="left" title="Delete {{ $guideOrder->name }}"><i class="icon-copy fa fa-trash p-0"></i></button>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                        
                                                {{-- Modal Edit Guide --------------------------------------------------------------------------------------------------------------- --}}
                                                @if ($reservation->status != "Paid")
                                                    <div class="modal fade" id="edit-guide-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content text-left">
                                                                <div class="card-box">
                                                                    <div class="card-box-title">
                                                                        <div class="subtitle"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Edit Guide</div>
                                                                    </div>
                                                                    <form id="editGuideOrder" action="/fedit-guide-order-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('put')
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <div class="form-group row">
                                                                                    <label for="guide_id" class="col-sm-12 col-md-12 col-form-label">Select Guide</label>
                                                                                    <div class="col-sm-12">
                                                                                        <select name="guide_id" class="form-control @error('guide_id') is-invalid @enderror" value="{{ old('guide_id') }}">
                                                                                            <option selected value="{{ $guideOrder->id }}">{{ $guideOrder->name }}</option>
                                                                                            @foreach ($guides as $guide)
                                                                                                <option value="{{ $guide->id }}">{{ $guide->name }}</option>
                                                                                            @endforeach
                                                                                            
                                                                                        </select>
                                                                                        @error('guide_id')
                                                                                            <div class="alert-form">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="rsv_id" value="{{ $reservation->id }}">
                                                                        </div>
                                                                    </form>
                                                                    <div class="card-box-footer">
                                                                        <button type="submit" form="editGuideOrder" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Change</button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="card-ptext-margin">
                                                    -
                                                </div>
                                            @endif
                                            {{-- Modal Add Guide --------------------------------------------------------------------------------------------------------------- --}}
                                            @if ($reservation->status != "Paid")
                                                <div class="modal fade" id="add-guide-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content text-left">
                                                            <div class="card-box">
                                                                <div class="card-box-title">
                                                                    <div class="subtitle"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Guide</div>
                                                                </div>
                                                                <form id="addGuideOrder" action="/fadd-guide-order-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group row">
                                                                                <label for="guide_id" class="col-sm-12 col-md-12 col-form-label">Select Guide</label>
                                                                                <div class="col-sm-12">
                                                                                    <select name="guide_id" class="form-control @error('guide_id') is-invalid @enderror" value="{{ old('guide_id') }}">
                                                                                        <option selected value="">Select Guide</option>
                                                                                        @foreach ($guides as $guide)
                                                                                            <option value="{{ $guide->id }}">{{ $guide->name }}</option>
                                                                                        @endforeach
                                                                                        
                                                                                    </select>
                                                                                    @error('guide_id')
                                                                                        <div class="alert-form">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" name="rsv_id" value="{{ $reservation->id }}">
                                                                    </div>
                                                                </form>
                                                                <div class="card-box-footer">
                                                                    <button type="submit" form="addGuideOrder" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- DRIVER --}}
                                        <div class="col-md-6">
                                            <div class="tab-inner-title ">Driver
                                                @if ($order->handled_by)
                                                    @if ($order->handled_by == Auth::user()->id)
                                                        @if ($order->status != "Paid")
                                                            @if (!$driverOrder)
                                                                <span>
                                                                    <a href="modal" data-toggle="modal" data-target="#add-driver-{{ $order->id }}"> 
                                                                        <i class="icon-copy fa fa-plus-circle" data-toggle="tooltip" data-placement="left" title="Add Driver" aria-hidden="true"></i>
                                                                    </a>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @else
                                                    @if ($order->status != "Paid")
                                                        @if (!$driverOrder)
                                                            <span>
                                                                <a href="modal" data-toggle="modal" data-target="#add-driver-{{ $order->id }}"> 
                                                                    <i class="icon-copy fa fa-plus-circle" data-toggle="tooltip" data-placement="left" title="Add Driver" aria-hidden="true"></i>
                                                                </a>
                                                            </span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                            @if ($driverOrder)
                                                <form id="deleteDriverOrder{{ $order->id }}" action="/fdelete-driver-order-{{ $order->id }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                </form>
                                                <div class="card-ptext-margin">
                                                    <div class="reservation-guest">
                                                        Mr. {{ $driverOrder->name." (".$driverOrder->phone.")" }}
                                                        @if ($order->handled_by)
                                                            @if ($order->handled_by == Auth::user()->id)
                                                                @if ($order->status != "Paid")
                                                                    <span>
                                                                        <a href="modal" data-toggle="modal" data-target="#edit-driver-{{ $order->id }}"> 
                                                                            <button class="btn btn-update" data-toggle="tooltip" data-placement="left" title="Change {{ $driverOrder->name }}"><i class="icon-copy fa fa-pencil p-0"></i></button>
                                                                        </a>
                                                                        
                                                                        <button form="deleteDriverOrder{{ $order->id }}" class="btn btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="left" title="Delete {{ $driverOrder->name }}"><i class="icon-copy fa fa-trash p-0"></i></button>
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if ($order->status != "Paid")
                                                                <span>
                                                                    <a href="modal" data-toggle="modal" data-target="#edit-driver-{{ $order->id }}"> 
                                                                        <button class="btn btn-update" data-toggle="tooltip" data-placement="left" title="Change {{ $driverOrder->name }}"><i class="icon-copy fa fa-pencil p-0"></i></button>
                                                                    </a>
                                                                    <button form="deleteDriverOrder{{ $order->id }}" class="btn btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="left" title="Delete {{ $driverOrder->name }}"><i class="icon-copy fa fa-trash p-0"></i></button>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                {{-- Modal Edit Driver --------------------------------------------------------------------------------------------------------------- --}}
                                                @if ($reservation->status != "Approved")
                                                    <div class="modal fade" id="edit-driver-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content text-left">
                                                                <div class="card-box">
                                                                    <div class="card-box-title">
                                                                        <div class="subtitle"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Change Driver</div>
                                                                    </div>
                                                                    <form id="editGuideOrder" action="/fedit-driver-order-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('put')
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <div class="form-group row">
                                                                                    <label for="driver_id" class="col-sm-12 col-md-12 col-form-label">Select Guide</label>
                                                                                    <div class="col-sm-12">
                                                                                        <select name="driver_id" class="form-control @error('driver_id') is-invalid @enderror" value="{{ old('driver_id') }}">
                                                                                            <option selected value="{{ $driverOrder->id }}">{{ $driverOrder->name }}</option>
                                                                                            @foreach ($drivers as $driver)
                                                                                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                                                                            @endforeach
                                                                                            
                                                                                        </select>
                                                                                        @error('driver_id')
                                                                                            <div class="alert-form">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="rsv_id" value="{{ $reservation->id }}">
                                                                        </div>
                                                                    </form>
                                                                    <div class="card-box-footer">
                                                                        <button type="submit" form="editGuideOrder" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Change</button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="card-ptext-margin">
                                                    -
                                                </div>
                                            @endif
                                            {{-- Modal Add Driver --------------------------------------------------------------------------------------------------------------- --}}
                                            @if ($order->status != "Paid")
                                                <div class="modal fade" id="add-driver-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content text-left">
                                                            <div class="card-box">
                                                                <div class="card-box-title">
                                                                    <div class="subtitle"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Driver</div>
                                                                </div>
                                                                <form id="addDriverOrder" action="/fadd-driver-order-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group row">
                                                                                <label for="driver_id" class="col-sm-12 col-md-12 col-form-label">Driver</label>
                                                                                <div class="col-sm-12">
                                                                                    <select name="driver_id" class="form-control @error('driver_id') is-invalid @enderror" value="{{ old('driver_id') }}">
                                                                                        <option selected value="">Select Driver</option>
                                                                                        @foreach ($drivers as $driver)
                                                                                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                                                                        @endforeach
                                                                                        
                                                                                    </select>
                                                                                    @error('driver_id')
                                                                                        <div class="alert-form">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" name="rsv_id" value="{{ $reservation->id }}">
                                                                    </div>
                                                                </form>
                                                                <div class="card-box-footer">
                                                                    <button type="submit" form="addDriverOrder" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- ORDER / SERVICE --}}
                                        <div class="col-md-12">
                                            <div id="order" class="tab-inner-title">
                                                Order
                                            </div>
                                            <div class="card-ptext-margin">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card-ptext-content">
                                                            <div class="ptext-title">Order No.</div>
                                                            <div class="ptext-value"><b>{{ $order->orderno }}</b></div>
                                                            @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package")
                                                                <div class="ptext-title">Hotel</div>
                                                            @elseif ($order->service == "Tour Package")
                                                                <div class="ptext-title">Partner</div>
                                                            @elseif ($order->service == "Activity")
                                                                <div class="ptext-title">Partner</div>
                                                            @elseif ($order->service == "Transport")
                                                                <div class="ptext-title">Transport</div>
                                                            @endif
                                                            <div class="ptext-value">{{ $order->servicename }}</div>
                                                            @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package")
                                                                <div class="ptext-title">Room</div>
                                                            @elseif ($order->service == "Tour Package")
                                                                <div class="ptext-title">Tour Package</div>
                                                            @elseif ($order->service == "Activity")
                                                                <div class="ptext-title">Activity</div>
                                                            @elseif ($order->service == "Transport")
                                                                <div class="ptext-title">Type</div>
                                                            @endif
                                                            <div class="ptext-value">{{ $order->subservice }}</div>
                                                            @if ($order->service == "Hotel Promo")
                                                                @php
                                                                    $hp = json_decode($order->promo_name);
                                                                    $hotel_promo = implode(", ",$hp);
                                                                @endphp
                                                                <div class="ptext-title">Promo</div>
                                                                <div class="ptext-value">{{ $hotel_promo }}</div>
                                                            @elseif ($order->service == "Hotel Package")
                                                                <div class="ptext-title">Package</div>
                                                                <div class="ptext-value">{{ $order->package_name }}</div>
                                                            @elseif ($order->service == "Transport")
                                                                <div class="ptext-title">Capacity</div>
                                                                <div class="ptext-value">{{ ': '. $order->capacity . ' Seats' }}</div>
                                                            @endif
                                                            @if ($order->status == "Pending")
                                                                @php
                                                                    $ar = is_array($guest_detail);
                                                                @endphp
                                                                
                                                                <div class="ptext-title">Guest Detail</div>
                                                                <div class="ptext-value">
                                                                    @if ($ar == 1)
                                                                        @php
                                                                            $guests_name = implode(', ',$guest_detail);
                                                                        @endphp
                                                                        {{ $guests_name }}
                                                                    @else
                                                                        {!!  $order->guest_detail !!}
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card-ptext-content">
                                                            @if ($order->service == "Hotel" or $order->service == "Hotel Package" or $order->service == "Hotel Promo")
                                                                @php
                                                                    $final_date = Carbon::parse($now);
                                                                    $cin = Carbon::parse($order->checkin);
                                                                    $release_limit = $final_date->diffInDays($cin);
                                                                @endphp
                                                                <div class="ptext-title">@lang('messages.Service') </div>
                                                                <div class="ptext-value">{{ $order->service }}</div>
                                                                <div class="ptext-title">@lang('messages.Number of Room') </div>
                                                                <div class="ptext-value">
                                                                    @if ($order->number_of_room > 0)
                                                                        {{ $order->number_of_room." Unit" }}
                                                                    @else
                                                                        {{ $order->number_of_room." Unit" }}
                                                                    @endif
                                                                </div>
                                                                <div class="ptext-title">@lang('messages.Duration') </div>
                                                                <div class="ptext-value">{{ $order->duration . ' Night' }}</div>
                                                                <div class="ptext-title">@lang('messages.Check-in') </div>
                                                                <div class="ptext-value">{{ dateFormat($order->checkin) }}</div>
                                                                <div class="ptext-title">@lang('messages.Check-out') </div>
                                                                <div class="ptext-value">{{ dateFormat($order->checkout) }}</div>
                                                            @elseif ($order->service == "Tour Package")
                                                                @php
                                                                    $final_date = Carbon::parse($now);
                                                                    $cin = Carbon::parse($order->checkin);
                                                                    $release_limit = $final_date->diffInDays($cin);
                                                                @endphp 
                                                                <div class="ptext-title">@lang('messages.Service') </div>
                                                                <div class="ptext-value">{{ $order->service }}</div>
                                                                <div class="ptext-title">Duration </div>
                                                                @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package")
                                                                    @if ($order->duration > 1)
                                                                        <div class="ptext-value">{{ $order->duration." nights" }}</div>
                                                                    @else
                                                                        <div class="ptext-value">{{ $order->duration." night" }}</div>
                                                                    @endif
                                                                @elseif ($order->service == "Tour Package")
                                                                    @if ($order->duration == 1)
                                                                        <div class="ptext-value">{{ $order->duration."D" }}</div>
                                                                    @elseif ($order->duration == 2)
                                                                        <div class="ptext-value">{{ ($order->duration)."D/".($order->duration - 1)."N" }}</div>
                                                                    @elseif ($order->duration == 3)
                                                                        <div class="ptext-value">{{ ($order->duration)."D/".($order->duration - 1)."N" }}</div>
                                                                    @elseif ($order->duration == 4)
                                                                        <div class="ptext-value">{{ ($order->duration)."D/".($order->duration - 1)."N" }}</div>
                                                                    @elseif ($order->duration == 5)
                                                                        <div class="ptext-value">{{ ($order->duration)."D/".($order->duration - 1)."N" }}</div>
                                                                    @elseif ($order->duration == 6)
                                                                        <div class="ptext-value">{{ ($order->duration)."D/".($order->duration - 1)."N" }}</div>
                                                                    @elseif ($order->duration == 7)
                                                                        <div class="ptext-value">{{ ($order->duration)."D/".($order->duration - 1)."N" }}</div>
                                                                    @endif
                                                                @else
                                                                    @if ($order->duration > 1)
                                                                        <div class="ptext-value">{{ $order->duration." hours" }}</div>
                                                                    @else
                                                                        <div class="ptext-value">{{ $order->duration." hour" }}</div>
                                                                    @endif
                                                                @endif
                                                                <div class="ptext-title">@lang('messages.Tour Start') </div>
                                                                <div class="ptext-value">{{ dateFormat($order->checkin) }}</div>
                                                                <div class="ptext-title">@lang('messages.Tour End') </div>
                                                                <div class="ptext-value">{{ dateFormat($order->checkout) }}</div>
                                                            @elseif ($order->service == "Activity")
                                                                <div class="ptext-title">@lang('messages.Service') </div>
                                                                <div class="ptext-value">{{ $order->service }}</div>
                                                                <div class="ptext-title">@lang('messages.Duration') </div>
                                                                <div class="ptext-value">{{ $order->duration." hours" }}</div>
                                                                <div class="ptext-title">@lang('messages.Activity Start') </div>
                                                                <div class="ptext-value">{{ dateTimeFormat($order->travel_date) }}</div>
                                                                <div class="ptext-title">@lang('messages.Activity End') </div>
                                                                <div class="ptext-value">
                                                                    <?php
                                                                        $activity_duration = $order->duration;
                                                                        $activity_end=dateTimeFormat('+'.$activity_duration.'hours', strtotime($order->travel_date)); 
                                                                    ?>
                                                                    {{ ': ' .dateTimeFormat($activity_end) }}
                                                                </div>
                                                            @elseif ($order->service == "Transport")
                                                                <div class="ptext-title">@lang('messages.Service') </div>
                                                                <div class="ptext-value">{{ $order->service." (".$order->service_type.")" }}</div>
                                                                <div class="ptext-title">@lang('messages.Duration') </div>
                                                                <div class="ptext-value">{{ $order->duration." hours" }}</div>
                                                                <div class="ptext-title">Start </div>
                                                                <div class="ptext-value">{{ dateTimeFormat($order->pickup_date) }}</div>
                                                                <div class="ptext-title">End </div>
                                                                <div class="ptext-value">
                                                                    <?php 
                                                                        $duration = $order->duration;
                                                                        $return_date=dateTimeFormat( '+'.$duration.'hours', strtotime($order->dropoff_date));
                                                                    ?>
                                                                    {{ dateTimeFormat($return_date) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- BENEFITS --}}
                                    @if ($order->status == "Active" or $order->status == "Approved" or $order->status == "Paid" or $order->status == "Rejected" or $order->status == "Confirmed")
                                        @if (isset($order->benefits))
                                            @php
                                                $benefits = json_decode($order->benefits);
                                                if (isset($benefits)) {
                                                    $cain = count($benefits);
                                                }else {
                                                    $cain = 0;
                                                }
                                            @endphp
                                            @if ($cain >0)
                                                <div class="page-text">
                                                    <hr class="form-hr">
                                                    <b>@lang('messages.Benefits') :</b>
                                                    @if (isset($benefits))
                                                        @foreach ($benefits as $benefit)
                                                            {!! $benefit !!}
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @else
                                                <div class="page-text">
                                                    <hr class="form-hr">
                                                    <b>@lang('messages.Benefits') :</b>
                                                    {!! $order->benefits !!}
                                                </div>
                                            @endif
                                        @endif
                                        {{-- DESTINATION --}}
                                        @if ($order->destinations)
                                            <div class="page-text">
                                                <hr class="form-hr">
                                                <b>@lang('messages.Destinations') :</b> <br>
                                                {!! $order->destinations !!}
                                            </div>
                                        @endif
                                        {{-- ITINERARY --}}
                                        @if ($order->itinerary)
                                            <div class="page-text">
                                                <hr class="form-hr">
                                                <b>@lang('messages.Itinerary') :</b> <br>
                                                {!! $order->itinerary !!}
                                            </div>
                                        @endif
                                        {{-- INCLUDE --}}
                                        @if (isset($order->include))
                                            @php
                                                $includes = json_decode($order->include);
                                                if (isset($includes)) {
                                                    $cincl = count($includes);
                                                }else {
                                                    $cincl = 0;
                                                }
                                            @endphp
                                            @if ($cincl >0)
                                                <div class="page-text">
                                                    <hr class="form-hr">
                                                    <b>@lang('messages.Include') :</b>
                                                    @if (isset($includes))
                                                        @foreach ($includes as $include)
                                                            {!! $include !!}
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @else
                                                <div class="page-text">
                                                    <hr class="form-hr">
                                                    <b>@lang('messages.Include') :</b>
                                                    {!! $order->include !!}
                                                </div>
                                            @endif
                                        @endif
                                        @if (isset($order->additional_info))
                                            @php
                                                $additional_infos = json_decode($order->additional_info);
                                                if (isset($additional_infos)) {
                                                    $cain = count($additional_infos);
                                                }else {
                                                    $cain = 0;
                                                }
                                            @endphp
                                            @if ($cain >0)
                                                <div class="page-text">
                                                    <hr class="form-hr">
                                                    <b>@lang('messages.Additional Information') :</b>
                                                    @if (isset($additional_infos))
                                                        @foreach ($additional_infos as $additional_info)
                                                            {!! $additional_info !!}
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @else
                                                <div class="page-text">
                                                    <hr class="form-hr">
                                                    <b>@lang('messages.Additional Information') :</b>
                                                    {!! $order->additional_info !!}
                                                </div>
                                            @endif
                                        @endif
                                        @if ($order->cancellation_policy)
                                            <div class="page-text">
                                                <hr class="form-hr">
                                                <b>@lang('messages.Cancellation Policy') :</b>
                                                <p>{!! $order->cancellation_policy !!}</p>
                                            </div>
                                        @endif
                                    @endif
                                    
                                    {{-- Room and Suite ===================================================================================================================== --}}
                                    @if ($order->service == "Hotel" or $order->service == "Hotel Package" or $order->service == "Hotel Promo")
                                        @if ($order->number_of_room == "" or $order->number_of_guests_room == "" or $order->guest_detail == "" )
                                            <div class="tab-inner-title" style=" background-color: #ffe3e3; border: 2px dotted red;">Suites and Villas</div>
                                        @else
                                            <div class="tab-inner-title">Suites and Villas</div>
                                        @endif
                                        <div class="row">
                                            @if ($order->number_of_room == "" or $order->number_of_guests_room == "" or $order->guest_detail == "" )
                                                <div class="col-sm-12 m-b-18">
                                                    <div class="room-container ">
                                                        <p style="color:brown;"><i>There are no rooms booked in this booking!</i></p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-12">
                                                    <table class="data-table table nowrap" >
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 5%;">Room</th>
                                                                <th style="width: 5%;">Nuber Of Guests</th>
                                                                <th style="width: 15%; max-width:15%;">Guests Name</th>
                                                                <th style="width: 10%;">Price</th>
                                                                <th style="width: 10%;">Extra Bed</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @for ($i = 0; $i < $nor; $i++)
                                                                @if ($special_day[$i])
                                                                    <tr data-toggle="tooltip" data-placement="top" title="{{ dateFormat($special_date[$i])." ".$special_day[$i]  }}" style="background-color: #ffe695;">
                                                                @else
                                                                    <tr>
                                                                @endif
                                                                    <td>
                                                                        <div class="table-service-name">{{ $r }}</div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="table-service-name">{{ $nogr[$i]." Guests" }}</div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="table-service-name">{{ $guest_detail[$i] }}</div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="table-service-name">{{ "$ ".number_format($order->normal_price, 0, ",", ".") }}</div>
                                                                    </td>
                                                                    <td>
                                                                        @if ($extra_bed[$i] == "Yes")
                                                                            @php
                                                                                $extrabed = $extra_beds->where('id',$extra_bed_id[$i])->first();
                                                                                // $ebed = json_decode($extra_bed_id);
                                                                            @endphp
                                                                            @if ($extra_bed_price[$i] != "0")
                                                                                <div class="table-service-name">{{ $extrabed->name." (".$extrabed->type.") $".number_format($extra_bed_price[$i], 0, ",", ".")}}</div>
                                                                            @else
                                                                                @php
                                                                                    $order_status = "Invalid";
                                                                                @endphp
                                                                                <p class="text-danger"><i>Invalid! </i> <i style="color: #7e7e7e;" data-toggle="tooltip" data-placement="top" title="This room is occupied by more than 2 guests, and requires an extra bed, please edit it first to be able to submit an order" class="icon-copy fa fa-info-circle" aria-hidden="true"></i></p>
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
                                                    <div class="box-price-kicked m-b-8">
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
                                            @if ($order->handled_by)
                                                @if ($order->handled_by == Auth::user()->id)
                                                    @if ($order->status != "Paid")
                                                        @if ($order->number_of_room == "" or $order->number_of_guests_room == "" or $order->guest_detail == "" or $order->guest_detail == ""  )
                                                            <div class="col-md-6 text-right">
                                                                <a href="/admin-edit-order-room-{{ $order->id }}">
                                                                    <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                                </a>
                                                            </div>
                                                        @else
                                                            @if ($order->request_quotation == "Yes")
                                                                <div class="col-md-8">
                                                                    <p style="color: blue">This order makes room reservations for more than 8 rooms, confirm immediately.</p>
                                                                </div>
                                                                <div class="col-md-4 text-right">
                                                                    <a href="/admin-edit-order-room-{{ $order->id }}">
                                                                        <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <div class="col-md-12 text-right">
                                                                    <a href="/admin-edit-order-room-{{ $order->id }}">
                                                                        <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                                                    </a>
                                                                </div>
                                                            @endif 
                                                        @endif
                                                    @endif
                                                @endif
                                            @else
                                                @if ($order->status != "Paid")
                                                    @if ($order->number_of_room == "" or $order->number_of_guests_room == "" or $order->guest_detail == "" or $order->guest_detail == ""  )
                                                        <div class="col-md-6 text-right">
                                                            <a href="/admin-edit-order-room-{{ $order->id }}">
                                                                <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                            </a>
                                                        </div>
                                                    @else
                                                        @if ($order->request_quotation == "Yes")
                                                            <div class="col-md-8">
                                                                <p style="color: blue">This order makes room reservations for more than 8 rooms, confirm immediately.</p>
                                                            </div>
                                                            <div class="col-md-4 text-right">
                                                                <a href="/admin-edit-order-room-{{ $order->id }}">
                                                                    <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="col-md-12 text-right">
                                                                <a href="/admin-edit-order-room-{{ $order->id }}">
                                                                    <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                                                </a>
                                                            </div>
                                                        @endif 
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        {{-- ADDITIONAL CHARGE   ===================================================================================================================== --}}
                                        @if ($order->number_of_guests > 0)
                                            @if(isset($optional_rate_order))
                                                @php
                                                    $optional_rate_orders_id = json_decode($optional_rate_order->optional_rate_id);
                                                    $optional_rate_orders_nog = json_decode($optional_rate_order->number_of_guest);
                                                    $optional_rate_orders_sd = json_decode($optional_rate_order->service_date);
                                                    $optional_rate_orders_pp = json_decode($optional_rate_order->price_pax);
                                                    $optional_rate_orders_pt = json_decode($optional_rate_order->price_total);
                                                    if ($optional_rate_orders_nog) {
                                                        $xsor = count($optional_rate_orders_nog);
                                                    }else{
                                                        $xsor = 0;
                                                        $order->optional_price = 0;
                                                    }
                                                @endphp
                                                <div id="optional_service" class="tab-inner-title">Additional Charge</div>
                                                @if ($optional_rate_orders_id)
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table class="data-table table nowrap" >
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 10%;">Date</th>
                                                                        <th style="width: 5%;">Number of Guests</th>
                                                                        <th style="width: 15%;">Services</th>
                                                                        <th style="width: 10%;">Price</th>
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
                                                                            $order->optional_price = array_sum($optional_rate_orders_pt);
                                                                        @endphp
                                                                    @endfor
                                                                </tbody>
                                                            </table>
                                                            <div class="box-price-kicked m-b-8">
                                                                <div class="row">
                                                                    <div class="col-6 col-md-6">
                                                                        <div class="subtotal-text">Additional Charges</div>
                                                                    </div>
                                                                    <div class="col-6 col-md-6 text-right">
                                                                        <div class="subtotal-price">{{ "$ ".number_format(($order->optional_price), 0, ",", ".") }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                @else
                                                    <div class="row">
                                                        <div class="col-sm-12 m-b-18">
                                                            <div class="card-ptext-margin">
                                                                <i style="color: red;">In this order there is no optional charge added!</i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($order->handled_by)
                                                    @if ($order->handled_by == Auth::user()->id)
                                                        @if ($order->status != "Paid")
                                                            <div class="row">
                                                                @if ($optional_rate_orders_id)
                                                                    <div class="col-md-12 text-right">
                                                                        <a href="/optional-rate-add-{{ $order->id }}">
                                                                            <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit additional charges"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                                                        </a>
                                                                    
                                                                    </div>
                                                                @else
                                                                    <div class="col-md-12 text-right">
                                                                        <a href="/optional-rate-add-{{ $order->id }}">
                                                                            <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Add additional charges"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                                        </a>
                                                                    </div>
                                                                @endif   
                                                            </div>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if ($order->status != "Paid")
                                                        <div class="row">
                                                            @if ($optional_rate_orders_id)
                                                                <div class="col-md-12 text-right">
                                                                    <a href="/optional-rate-add-{{ $order->id }}">
                                                                        <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit additional charges"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                                                    </a>
                                                                
                                                                </div>
                                                            @else
                                                                <div class="col-md-12 text-right">
                                                                    <a href="/optional-rate-add-{{ $order->id }}">
                                                                        <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Add additional charges"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                                    </a>
                                                                </div>
                                                            @endif   
                                                        </div>
                                                    @endif
                                                @endif
                                                @if (isset($optional_rate_orders_id))
                                                    @php
                                                        $decode_total_price = json_decode($optional_rate_order->price_total);
                                                        $optional_rate = $optional_rate_order->where('order_id','=',$order->id)->get();
                                                        $price_unit = array_sum($decode_total_price);
                                                        $total_price = $price_unit + $order->price_total; 
                                                    @endphp
                                                @endif
                                            @else
                                                @if ($order->status != "Paid")
                                                    <div id="optional_service" class="tab-inner-title">Additional Charges</div>
                                                    <div class="row">
                                                        <div class="col-sm-12 m-b-8">
                                                            <div class="card-ptext-margin">
                                                                <i style="color: red;">In this order there is no optional charge added!</i>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12 text-right">
                                                            <a href="/optional-rate-add-{{ $order->id }}">
                                                                <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit optional service"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Add</button>
                                                            </a>
                                                        </div>
                                                        
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                    {{-- ADDITIONAL SERVICES --}}
                                    <div class="row" id="additionalServices">
                                        <div class="col-md-12">
                                            <div class="tab-inner-title">Additional Services</div>
                                        </div>
                                        @if ($order->additional_service)
                                            <div class="col-md-12">
                                                <table class="data-table table nowrap" >
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 30%;">Date</th>
                                                            <th style="width: 40%;">Service</th>
                                                            <th style="width: 10%;">QTY</th>
                                                            <th style="width: 10%;">Price</th>
                                                            <th style="width: 10%;">Total</th>
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
                                        @else
                                            <div class="col-md-12">
                                                <div class="card-ptext-margin">
                                                    <i style="color: red;">In this order there is no additional service added!</i>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($order->handled_by)
                                            @if ($order->handled_by == Auth::user()->id)
                                                @if ($order->status != "Paid")
                                                    @if ($order->additional_service)
                                                        <div class="col-md-12 text-right">
                                                            <a href="/edit-additional-services-{{ $order->id }}">
                                                                <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit additional charge"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="col-md-12 text-right">
                                                            <a href="/edit-additional-services-{{ $order->id }}">
                                                                <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Add additional charge"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        @else
                                            @if ($order->status != "Paid")
                                                @if ($order->additional_service)
                                                    <div class="col-md-12 text-right">
                                                        <a href="/edit-additional-services-{{ $order->id }}">
                                                            <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit additional charge"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 text-right">
                                                        <a href="/edit-additional-services-{{ $order->id }}">
                                                            <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Add additional charge"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                    
                                    
                                    {{-- AIRPORT SHUTTLE --}}
                                    @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package" )
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="tab-inner-title">Airport Shuttle</div>
                                            </div>
                                            <div class="col-md-12">
                                                <table class="data-table table nowrap" >
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5%;">No</th>
                                                            <th style="width: 20%;">Date</th>
                                                            <th style="width: 10%;">Transport</th>
                                                            <th style="width: 25%;">Src <=> Dst</th>
                                                            <th style="width: 10%;">Duration</th>
                                                            <th style="width: 10%;">Distance</th>
                                                            <th style="width: 12%;">Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($airport_shuttles as $noairport => $airport_shuttle)
                                                            <tr>
                                                                <td>{{ ++$noairport }}</td>
                                                                <td>{{ dateTimeFormat($airport_shuttle->date) }}</td>
                                                                <td>{{ $airport_shuttle->transport }}</td>
                                                                <td>{{ $airport_shuttle->src." <=> ".$airport_shuttle->dst }}</td>
                                                                <td>{{ $airport_shuttle->duration." hours" }}</td>
                                                                <td>{{ $airport_shuttle->distance." Km" }}</td>
                                                                <td>{{ "$ ". number_format($airport_shuttle->price , 0, ",", ".") }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="box-price-kicked m-b-8">
                                                    <div class="row">
                                                        <div class="col-6 col-md-6">
                                                            <div class="subtotal-text"> Airport Shuttle</div>
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
                                            @if ($order->handled_by)
                                                @if ($order->handled_by == Auth::user()->id)
                                                    @if ($order->status != "Paid")
                                                        @if (count($airport_shuttles) > 0)
                                                            <div class="col-md-12 text-right">
                                                                <a href="/edit-airport-shuttle-{{ $order->id }}">
                                                                    <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="col-md-12 text-right">
                                                                <a href="/edit-airport-shuttle-{{ $order->id }}">
                                                                    <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                            @else
                                                @if ($order->status != "Paid")
                                                    @if (count($airport_shuttles) > 0)
                                                        <div class="col-md-12 text-right">
                                                            <a href="/edit-airport-shuttle-{{ $order->id }}">
                                                                <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="col-md-12 text-right">
                                                            <a href="/edit-airport-shuttle-{{ $order->id }}">
                                                                <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                    @if ($order->status == "Active" or $order->status == "Approved" or $order->status == "Paid" or $order->status == "Rejected" or $order->status == "Confirmed")
                                        @if (isset($order->note))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="tab-inner-title">Remark</div>
                                                    <p>{!! $order->note !!}</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    @if ($order->status != "Pending")
                                        <form id="fupdate-order" action="/fadmin-update-order/{{ $order->id }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                                            <div class="row">
                                                @if ($order->service == "Hotel Package" or $order->service == "Hotel Promo")
                                                    <div class="col-md-12">
                                                        <div class="tab-inner-title">Benefits</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <textarea id="benefits" name="benefits" placeholder="Optional" class="ckeditor form-control border-radius-0">{!! $order->benefits !!}</textarea>
                                                            @error('benefits')
                                                                <div class="alert alert-danger">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($order->service == "Tour Package")
                                                    <div class="col-md-12">
                                                        <div class="tab-inner-title">Destinations</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <textarea id="destinations" name="destination" placeholder="Optional" class="ckeditor form-control border-radius-0">{!! $order->destinations !!}</textarea>
                                                            @error('destination')
                                                                <div class="alert alert-danger">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($order->service == "Activity" or $order->service == "Tour Package")
                                                    <div class="col-md-12">
                                                        <div class="tab-inner-title">Itinerary</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <textarea id="itinerary" name="itinerary" placeholder="Optional" class="ckeditor form-control border-radius-0">{!! $order->itinerary !!}</textarea>
                                                            @error('itinerary')
                                                                <div class="alert alert-danger">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-md-12">
                                                    <div class="tab-inner-title">Include</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <textarea id="include" name="include" placeholder="Optional" class="ckeditor form-control border-radius-0">{!! $order->include !!}</textarea>
                                                        @error('include')
                                                            <div class="alert alert-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="tab-inner-title">Additional Information</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <textarea id="additional_info" name="additional_info" placeholder="Optional" class="ckeditor form-control border-radius-0">{!! $order->additional_info !!}</textarea>
                                                        @error('additional_info')
                                                            <div class="alert alert-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="tab-inner-title">Remark</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <textarea id="note" name="note" placeholder="Optional" class="ckeditor form-control border-radius-0">{!! $order->note !!}</textarea>
                                                        @error('note')
                                                            <div class="alert alert-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </Form>
                                    @endif
                                    <div id="optional_service" class="tab-inner-title m-t-8">Total Price</div>
                                    <div class="row">
                                        <div class="col-md-12 m-b-8">
                                            <div class="box-price-kicked">
                                                <div class="row">
                                                    <div class="col-6 col-md-6">
                                                        @if ($order->optional_price or $order->bookingcode_disc > 0 or $order->discounts > 0 or $total_promotion_disc > 0 or $order->airport_shuttle_price or $order->kick_back)
                                                            @if ($order->service == "Activity" or $order->service == "Transport")
                                                                <div class="promo-text">Price / Pax</div>
                                                            @elseif($order->service == "Tour Package")
                                                                <div class="promo-text">Price / Pax</div>
                                                                <div class="promo-text">Number of Guests</div>
                                                            @else
                                                                <div class="promo-text">@lang('messages.Suites and Villas')</div>
                                                            @endif
                                                            @if ($order->optional_price > 0)
                                                                <div class="promo-text">@lang('messages.Additional Charge')</div>
                                                            @endif
                                                            @if ($total_additional_service > 0)
                                                                <div class="promo-text">Additional Charges</div>
                                                            @endif
                                                            @if ($order->airport_shuttle_price > 0)
                                                                <div class="promo-text">Airport Shuttle</div>
                                                            @endif
                                                            @if ($order->bookingcode_disc > 0 or $order->discounts > 0 or $total_promotion_disc > 0 or $order->kick_back)
                                                                <div class="promo-text" style="font-size: 0.8rem"><b>Normal Price</b></div>
                                                                <hr class="form-hr">
                                                                @if ($total_promotion_disc > 0)
                                                                    <div class="promo-text">@lang('messages.Promotion')</div>
                                                                @endif
                                                                @if ($order->bookingcode_disc > 0)
                                                                    <div class="promo-text">@lang('messages.Booking Code')</div>
                                                                @endif
                                                                @if ($order->discounts > 0)
                                                                    <div class="promo-text">@lang('messages.Discounts')</div>
                                                                @endif
                                                                @if ($order->kick_back)
                                                                    <div class="promo-text">@lang('messages.Kick Back')</div>
                                                                @endif
                                                            @endif
                                                           
                                                            <hr class="form-hr">
                                                        @else
                                                            @if ($order->service == "Activity" or $order->service == "Transport")
                                                                <div class="promo-text">@lang('messages.Normal Price'):</div>
                                                            @elseif($order->service == "Tour Package")
                                                                <div class="promo-text">Price / Pax</div>
                                                                <div class="promo-text">Number of Guests</div>
                                                            @else
                                                                <div class="promo-text">@lang('messages.Suites and Villas')</div>
                                                            @endif
                                                            @if ($order->optional_price > 0)
                                                                <div class="promo-text">@lang('messages.Additional Charge')</div>
                                                            @endif
                                                            @if ($total_additional_service > 0)
                                                                <div class="promo-text">Additional Charges</div>
                                                            @endif
                                                            @if ($order->airport_shuttle_price > 0)
                                                                <div class="promo-text">Airport Shuttle</div>
                                                            @endif
                                                            <hr class="form-hr">
                                                        @endif
                                                        <div class="price-name">Total Price USD</div>
                                                        @if ($invoice)
                                                            @if ($invoice->currency->name == "CNY")
                                                                <div class="price-name">Total Price CNY</div>
                                                            @elseif ($invoice->currency->name == "TWD")
                                                                <div class="price-name">Total Price TWD</div>
                                                            @elseif ($invoice->currency->name == "IDR")
                                                                <div class="price-name">Total Price IDR</div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="col-6 col-md-6 text-right">
                                                        @if ($order->optional_price > 0 or $order->bookingcode_disc > 0 or $order->discounts > 0 or $total_promotion_disc > 0 or $order->airport_shuttle_price or $order->kick_back)
                                                            @if ($order->service == "Activity" or $order->service == "Transport")
                                                                <div class="promo-text">{{ "$ ".number_format(($order->normal_price), 0, ",", ".") }}</div>
                                                            @elseif($order->service == "Tour Package")
                                                                <div class="promo-text">{{ "$ ".number_format(($order->price_pax), 0, ",", ".") }}</div>
                                                                <div class="promo-text">{{ $order->number_of_guests }}</div>
                                                            @else
                                                                <div class="promo-text">{{ "$ ".number_format($total_room_and_suite, 0, ",", ".") }}</div>
                                                            @endif
                                                            @if ($order->optional_price > 0)
                                                                <div class="promo-text">{{ "$ ".number_format(($order->optional_price), 0, ",", ".") }}</div>
                                                            @endif
                                                            @if ($total_additional_service > 0)
                                                                <div class="promo-text">{{ "$ ".number_format($total_additional_service, 0, ",", ".") }}</div>
                                                            @endif
                                                            @if ($order->airport_shuttle_price > 0)
                                                                <div class="promo-text">{{ "$ ".number_format($order->airport_shuttle_price, 0, ",", ".") }}</div>
                                                            @endif
                                                            @if ($order->bookingcode_disc > 0 or $order->discounts > 0 or $total_promotion_disc > 0 or $order->kick_back)
                                                                <div class="promo-text"><span>{{ number_format(($order->final_price + $order->bookingcode_disc + $order->discounts + $total_promotion_disc + $order->kick_back), 0, ",", ".") }}</span></div>
                                                                <hr class="form-hr">
                                                                @if ($total_promotion_disc > 0)
                                                                    <div class="kick-back">{{ number_format($total_promotion_disc, 0, ",", ".") }}</div>
                                                                @endif
                                                                @if ($order->bookingcode_disc > 0)
                                                                    <div class="kick-back">{{ number_format($order->bookingcode_disc, 0, ",", ".") }}</div>
                                                                @endif
                                                                @if ($order->discounts > 0)
                                                                    <div class="kick-back">{{ number_format($order->discounts, 0, ",", ".") }}</div>
                                                                @endif
                                                                @if ($order->kick_back > 0)
                                                                    <div class="kick-back">{{ number_format($order->kick_back, 0, ",", ".") }}</div>
                                                                @endif
                                                            @endif
                                                            <hr class="form-hr">
                                                        @else
                                                            @if ($order->service == "Activity" or $order->service == "Transport")
                                                                <div class="promo-text">{{ "$ ".number_format($order->normal_price, 0, ",", ".") }}</div>
                                                            @elseif($order->service == "Tour Package")
                                                                <div class="promo-text">{{ "$ ".number_format(($order->price_pax), 0, ",", ".") }}</div>
                                                                <div class="promo-text">{{ $order->number_of_guests }}</div>
                                                            @else
                                                                <div class="promo-text">{{ "$ ".number_format($total_room_and_suite, 0, ",", ".") }}</div>
                                                            @endif
                                                            @if ($order->optional_price > 0)
                                                                <div class="promo-text">{{ "$ ".number_format($order->optional_price, 0, ",", ".") }}</div>
                                                            @endif
                                                            @if ($total_additional_service > 0)
                                                                <div class="promo-text">{{ "$ ".number_format($total_additional_service, 0, ",", ".") }}</div>
                                                            @endif
                                                            @if ($order->airport_shuttle_price > 0)
                                                                <div class="promo-text">{{ "$ ".number_format($order->airport_shuttle_price, 0, ",", ".") }}</div>
                                                            @endif
                                                            <hr class="form-hr">
                                                        @endif
                                                        <div class="usd-rate">{{ "$ ".number_format($order->final_price, 0, ",", ".") }}</div>
                                                        @if ($invoice)
                                                            @if ($invoice->bank->currency == 'CNY')
                                                                <div class="usd-rate">{{ "¥ ".number_format($invoice->total_cny, 0, ",", ".") }}</div>
                                                            @elseif ($invoice->bank->currency == 'TWD')
                                                                <div class="usd-rate">{{ "$ ".number_format($invoice->total_twd, 0, ",", ".") }}</div>
                                                            @elseif ($invoice->bank->currency == 'IDR')
                                                                <div class="usd-rate">{{ "Rp ".number_format($invoice->total_idr, 0, ",", ".") }}</div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($order->handled_by)
                                            @if ($order->handled_by == Auth::user()->id)
                                                @if ($order->status != "Paid")
                                                    <div class="col-md-12 text-right">
                                                        @if ($order->discounts > 0)
                                                            <a href="modal" data-toggle="modal" data-target="#remove-discounts-{{ $order->id }}"><button type="button" class="btn btn-secondary"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove Discounts</button></a>
                                                            <a href="modal" data-toggle="modal" data-target="#discounts-{{ $order->id }}"><button type="button" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit Discounts</button></a>
                                                        @else
                                                            <a href="modal" data-toggle="modal" data-target="#discounts-{{ $order->id }}"><button type="button" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Discounts</button></a>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if ($order->status != "Paid")
                                                <div class="col-md-12 text-right">
                                                    @if ($order->discounts > 0)
                                                        <a href="modal" data-toggle="modal" data-target="#remove-discounts-{{ $order->id }}"><button type="button" class="btn btn-secondary"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove Discounts</button></a>
                                                        <a href="modal" data-toggle="modal" data-target="#discounts-{{ $order->id }}"><button type="button" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit Discounts</button></a>
                                                    @else
                                                        <a href="modal" data-toggle="modal" data-target="#discounts-{{ $order->id }}"><button type="button" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Discounts</button></a>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                        
                                    </div>
                                    {{-- CURRENCY --}}
                                    @if ($order->status == "Pending" or $order->status == "Active")
                                        <div id="optional_service" class="tab-inner-title m-t-8">Currency</div>
                                        <div class="row urgent-box">
                                            <div class="col-md-12">
                                                <div class="notif-modal text-left">
                                                    Please select BANK and Currency, depend on Agent currency before you confirm the order!
                                                </div>
                                            </div>
                                            <div class="col-md-12" style="place-self:center;">
                                                <form id="factivate-order" action="/factivate-order/{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('put')
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="bank">BANK</label>
                                                                <select name="bank" class="custom-select">
                                                                    @foreach ($banks as $bank)
                                                                        <option {{ $bank->currency == "USD"?"selected":""; }} value="{{ $bank->id }}">{{ $bank->bank }}</option>
                                                                    @endforeach
                                                                   
                                                                </select>
                                                                @error('note')
                                                                    <div class="alert alert-danger">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="currency">Currency</label>
                                                                <select name="currency" class="custom-select">
                                                                    @foreach ($rates as $rate)
                                                                        <option {{ $rate->name == "USD"?"selected":""; }} value="{{ $rate->id }}">{{ $rate->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('note')
                                                                    <div class="alert alert-danger">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($order->status != "Paid")
                                        <div class="card-ptext-margin m-t-8">
                                            <div class="notif-modal text-left">
                                                Please make sure all the data is correct before you confirm the order!
                                            </div>
                                        </div>
                                    @else
                                        <div class="card-ptext-margin m-t-8">
                                            <div class="notif-modal text-left">
                                                This order has been validated and the status is Active!
                                            </div>
                                        </div>
                                    @endif
                                    <div class="card-box-footer">
                                        @if ($order->handled_by)
                                            @if ($order->handled_by == Auth::user()->id)
                                                @if ($order->status !== "Paid")
                                                    @if ($order->status !== "Archive")
                                                        <a href="modal" data-toggle="modal" data-target="#archive-order-{{ $order->id }}"><button type="button" class="btn btn-dark"><i class="icon-copy fa fa-archive" aria-hidden="true"></i> Archive</button></a>
                                                    @endif
                                                @endif
                                                @if ($order->status !== "Paid")
                                                    @if ($order->status !== "Rejected")
                                                        <a href="modal" data-toggle="modal" data-target="#reject-order-{{ $order->id }}"><button type="button" class="btn btn-rejected"><i class="fa fa-ban" aria-hidden="true"></i> Reject</button></a>
                                                    @endif
                                                @endif
                                                @if ($order->status == "Pending")
                                                    <button type="submit" form="factivate-order" class="btn btn-success"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Confirm</button>
                                                @else
                                                    <form id="sendConfirmation" class="hidden" action="/fsend-confirmation-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('put')
                                                    </form>
                                                    <form id="resendConfirmation" class="hidden" action="/fresend-confirmation-order-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('put')
                                                    </form>
                                                    @if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_en.pdf") or File::exists("storage/document/invoice-".$inv_no."-".$order->id."_zh.pdf"))
                                                        <a href="print-contract-order-{{ $order->id }}" target="__blank" >
                                                            <button type="button" class="btn btn-primary desktop"><i class="icon-copy fa fa-print" aria-hidden="true"></i> Print Document</button>
                                                        </a>
                                                    @endif
                                                    @if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_en.pdf"))
                                                        <a href="modal" data-toggle="modal" data-target="#contract-en-{{ $order->id }}">
                                                            <button type="button" class="btn btn-primary desktop"><i class="icon-copy fa fa-file-pdf-o" aria-hidden="true"></i> Invoice EN</button>
                                                        </a>
                                                        <a href='{{URL::to('/')}}/storage/document/invoice-{{ $inv_no }}-{{ $order->id }}_en.pdf' target="_blank">
                                                            <button type="button" class="btn btn-primary mobile"><i class="fa fa-download"></i> Download Invoice EN</button>
                                                        </a>
                                                    @endif
                                                    @if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_zh.pdf"))
                                                        <a href="modal" data-toggle="modal" data-target="#contract-zh-{{ $order->id }}">
                                                            <button type="button" class="btn btn-primary desktop"><i class="icon-copy fa fa-file-pdf-o" aria-hidden="true"></i> Invoice ZH</button>
                                                        </a>
                                                        <a href='{{URL::to('/')}}/storage/document/invoice-{{ $inv_no }}-{{ $order->id }}_zh.pdf' target="_blank">
                                                            <button type="button" class="btn btn-primary mobile"><i class="fa fa-download"></i> Download Invoice ZH</button>
                                                        </a>
                                                    @endif
                                                    @if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_en.pdf") or File::exists("storage/document/invoice-".$inv_no."-".$order->id."_zh.pdf"))
                                                    @else
                                                        <form id="generateInvoice" class="hidden" action="/fgenerate-invoice-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('put')
                                                        </form>
                                                        <button type="submit" form="generateInvoice" class="btn btn-primary"><i class="icon-copy fa fa-file-pdf-o" aria-hidden="true"></i> Generate Invoice</button>
                                                    @endif
                                                    @if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_en.pdf") or File::exists("storage/document/invoice-".$inv_no."-".$order->id."_zh.pdf"))
                                                        @if (!$reservation->send)
                                                            <button type="submit" form="sendConfirmation" class="btn btn-primary"><i class="icon-copy fa fa-envelope" aria-hidden="true"></i> Send Confirmation</button>
                                                            <div class="loading-icon hidden pre-loader">
                                                                <div class="pre-loader-box">
                                                                    <div class="sys-loader-logo w3-center"> <img class="w3-spin" src="{{ asset('storage/icon/spinner.png') }}" alt="Bali Kami Tour Logo"></div>
                                                                    <div class="loading-text">
                                                                        Sending the Confirmation Order...
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            @if ($order->status != "Paid")
                                                                <button type="submit" form="factivate-order" class="btn btn-primary"><i class="icon-copy fa fa-envelope" aria-hidden="true"></i> Reconfirm</button>
                                                                <div class="loading-icon hidden pre-loader">
                                                                    <div class="pre-loader-box">
                                                                        <div class="sys-loader-logo w3-center"> <img class="w3-spin" src="{{ asset('storage/icon/spinner.png') }}" alt="Bali Kami Tour Logo"></div>
                                                                        <div class="loading-text">
                                                                            Resend Confirmation Order...
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endif
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
                                                @endif
                                                @if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_en.pdf") or File::exists("storage/document/invoice-".$inv_no."-".$order->id."_zh.pdf"))
                                                    @if ($order->status == "Confirmed" and $invoice->due_date <= $now)
                                                        <form id="sendApprovalEmail" class="hidden" action="/fsend-approval-email-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('put')
                                                        </form>
                                                        <button type="submit" form="sendApprovalEmail" class="btn btn-warning"><i class="icon-copy fa fa-exclamation-circle" aria-hidden="true"></i> Send Approval Email</button>
                                                    @endif
                                                @endif
                                            @endif
                                        @else 
                                            @if ($order->status != "Archive" or $order->status !== "Paid")
                                                <a href="modal" data-toggle="modal" data-target="#archive-order-{{ $order->id }}"><button type="button" class="btn btn-dark"><i class="icon-copy fa fa-archive" aria-hidden="true"></i> Archive</button></a>
                                            @endif
                                            @if ($order->status != "Rejected" or $order->status !== "Paid")
                                                <a href="modal" data-toggle="modal" data-target="#reject-order-{{ $order->id }}"><button type="button" class="btn btn-rejected"><i class="fa fa-ban" aria-hidden="true"></i> Reject</button></a>
                                            @endif
                                            @if ($order->status != "Paid")
                                                <button type="submit" form="factivate-order" class="btn btn-success"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Confirm</button>
                                                <button type="submit" form="fupdate-order" class="btn btn-primary"><i class="icon-copy fa fa-save" aria-hidden="true"></i> Save</button>
                                            @else
                                                <form id="sendConfirmation" class="hidden" action="/fsend-confirmation-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('put')
                                                </form>
                                                <form id="resendConfirmation" class="hidden" action="/fresend-confirmation-order-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('put')
                                                </form>

                                                @if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_en.pdf") or File::exists("storage/document/invoice-".$inv_no."-".$order->id."_zh.pdf"))
                                                    <a href="print-contract-order-{{ $order->id }}" target="__blank" >
                                                        <button type="button" class="btn btn-primary desktop"><i class="icon-copy fa fa-print" aria-hidden="true"></i> Print Document</button>
                                                    </a>
                                                @endif
                                                @if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_en.pdf"))
                                                    <a href="modal" data-toggle="modal" data-target="#contract-en-{{ $order->id }}">
                                                        <button type="button" class="btn btn-primary desktop"><i class="icon-copy fa fa-file-pdf-o" aria-hidden="true"></i> Invoice EN</button>
                                                    </a>
                                                    <a href='{{URL::to('/')}}/storage/document/invoice-{{ $inv_no }}-{{ $order->id }}_en.pdf' target="_blank">
                                                        <button type="button" class="btn btn-primary mobile"><i class="fa fa-download"></i> Download Invoice EN</button>
                                                    </a>
                                                @endif
                                                @if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_zh.pdf"))
                                                    <a href="modal" data-toggle="modal" data-target="#contract-zh-{{ $order->id }}">
                                                        <button type="button" class="btn btn-primary desktop"><i class="icon-copy fa fa-file-pdf-o" aria-hidden="true"></i> Invoice ZH</button>
                                                    </a>
                                                    <a href='{{URL::to('/')}}/storage/document/invoice-{{ $inv_no }}-{{ $order->id }}_zh.pdf' target="_blank">
                                                        <button type="button" class="btn btn-primary mobile"><i class="fa fa-download"></i> Download Invoice ZH</button>
                                                    </a>
                                                @endif
                                                @if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_en.pdf") or File::exists("storage/document/invoice-".$inv_no."-".$order->id."_zh.pdf"))
                                                @else
                                                    <form id="generateInvoice" class="hidden" action="/fgenerate-invoice-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('put')
                                                    </form>
                                                    <button type="submit" form="generateInvoice" class="btn btn-primary"><i class="icon-copy fa fa-file-pdf-o" aria-hidden="true"></i> Generate Invoice</button>
                                                @endif
                                                @if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_en.pdf") or File::exists("storage/document/invoice-".$inv_no."-".$order->id."_zh.pdf"))
                                                    @if ($reservation->send == "")
                                                        <button type="submit" form="sendConfirmation" class="btn btn-primary"><i class="icon-copy fa fa-envelope" aria-hidden="true"></i> Send Confirmation</button>
                                                        <div class="loading-icon hidden pre-loader">
                                                            <div class="pre-loader-box">
                                                                <div class="sys-loader-logo w3-center"> <img class="w3-spin" src="{{ asset('storage/icon/spinner.png') }}" alt="Bali Kami Tour Logo"></div>
                                                                <div class="loading-text">
                                                                    Sending the Confirmation Order...
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @if ($order->status != "Paid")
                                                            <button type="submit" form="factivate-order" class="btn btn-primary"><i class="icon-copy fa fa-envelope" aria-hidden="true"></i> Reconfirm</button>
                                                            <div class="loading-icon hidden pre-loader">
                                                                <div class="pre-loader-box">
                                                                    <div class="sys-loader-logo w3-center"> <img class="w3-spin" src="{{ asset('storage/icon/spinner.png') }}" alt="Bali Kami Tour Logo"></div>
                                                                    <div class="loading-text">
                                                                        Resend Confirmation Order...
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                                
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
                                            @endif
                                            @if (File::exists("storage/document/invoice-".$inv_no."-".$order->id."_en.pdf") or File::exists("storage/document/invoice-".$inv_no."-".$order->id."_zh.pdf"))
                                                @if ($order->status == "Confirmed" and $invoice->due_date <= $now)
                                                    <form id="sendApprovalEmail" class="hidden" action="/fsend-approval-email-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('put')
                                                    </form>
                                                    <button type="submit" form="sendApprovalEmail" class="btn btn-warning"><i class="icon-copy fa fa-exclamation-circle" aria-hidden="true"></i> Send Approval Email</button>
                                                @endif
                                            @endif
                                        @endif
                                        <a href="/orders-admin" ><button type="button" class="btn btn-danger"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Close</button></a>
                                    </div>
                                    <script>
                                        $(document).ready(function() {
                                            $("#sendConfirmation").submit(function() {
                                                $(".result").text("");
                                                $(".loading-icon").removeClass("hidden");
                                                $(".submit").attr("disabled", true);
                                                $(".btn-txt").text("Processing ...");
                                            });
                                        });
                                        $(document).ready(function() {
                                            $("#resendConfirmation").submit(function() {
                                                $(".result").text("");
                                                $(".loading-icon").removeClass("hidden");
                                                $(".submit").attr("disabled", true);
                                                $(".btn-txt").text("Processing ...");
                                            });
                                        });
                                    </script>
                                </div>

                                {{-- Modal Add Discount --------------------------------------------------------------------------------------------------------------- --}}
                                <div class="modal fade" id="discounts-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="card-box">
                                                @if ($order->discounts > 0)
                                                    <div class="card-box-title">
                                                        <div class="title"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit Discounts</div>
                                                    </div>
                                                @else
                                                    <div class="card-box-title">
                                                        <div class="title"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Discounts</div>
                                                    </div>
                                                @endif
                                                <form id="update-discount" action="/fupdate-order-discounts/{{ $order->id }}"method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('put')
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="alasan_discounts">The reason for giving discounts!</label>
                                                                <div class="col-sm-12 col-md-12">
                                                                    <textarea id="alasan_discounts" name="alasan_discounts" placeholder="Add your reason here" class="ckeditor form-control border-radius-0" autofocus required>{{ $order->alasan_discounts }}</textarea>
                                                                    @error('alasan_discounts')
                                                                        <div class="alert alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="discounts">Discounts</label>
                                                                <div class="col-sm-12 col-md-12">
                                                                    <input type="number" min="1" name="discounts" placeholder="Amount" class="form-control" value="{{ $order->discounts }}" required>
                                                                    @error('discounts')
                                                                        <div class="alert alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="author" value="{{ Auth::User()->id }}">
                                                </Form>
                                                <div class="card-box-footer">
                                                    <div class="form-group">
                                                        @if ($order->discounts > 0)
                                                            <button type="submit" form="update-discount" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Update</button>
                                                        @else
                                                            <button type="submit" form="update-discount" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                        @endif
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Modal Remove Discount --------------------------------------------------------------------------------------------------------------- --}}
                                <div class="modal fade" id="remove-discounts-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="card-box">
                                                <div class="card-box-title">
                                                    <div class="title"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove Discounts</div>
                                                </div>
                                                <form id="remove-discount" action="/fremove-order-discounts/{{ $order->id }}"method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('put')
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <b>Are you sure to remove the discount on this order?</b>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="author" value="{{ Auth::User()->id }}">
                                                    <input type="hidden" name="alasan_discounts" value="">
                                                    <input type="hidden" name="discounts" value="">
                                                </Form>
                                                <div class="card-box-footer">
                                                    <div class="form-group">
                                                        <button type="submit" form="remove-discount" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Yes</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Modal Archived Order --------------------------------------------------------------------------------------------------------------- --}}
                                <div class="modal fade" id="archive-order-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="card-box">
                                                <div class="card-box-title">
                                                    <div class="title"><i class="icon-copy fa fa-archive" aria-hidden="true"></i> Archive orders</div>
                                                </div>
                                                <form id="arsipkan-order-{{ $order->id }}" action="/farchive-order/{{ $order->id }}"method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('put')
                                                    <div class="form-group row">
                                                        <label for="msg" class="col-sm-12 col-md-12 col-form-label">Why the order is Archived?</label>
                                                        <div class="col-sm-12 col-md-12">
                                                            <textarea id="msg" name="msg" placeholder="Add your reason here" class="ckeditor form-control border-radius-0" autofocus required></textarea>
                                                            @error('msg')
                                                                <div class="alert alert-danger">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    {{-- TRAC RECORD --}}
                                                    <input type="hidden" name="status_awal" value="{{ $order->status }}">
                                                    <input type="hidden" name="number_of_guests_awal" value="{{ $order->number_of_guests }}">
                                                    <input type="hidden" name="guest_detail_awal" value="{{ $order->guest_detail }}">
                                                    <input type="hidden" name="arrival_flight_awal" value="{{ $order->arrival_flight }}">
                                                    <input type="hidden" name="arrival_time_awal" value="{{ $order->arrival_time }}">
                                                    <input type="hidden" name="departure_flight_awal" value="{{ $order->departure_flight }}">
                                                    <input type="hidden" name="departure_time_awal" value="{{ $order->departure_time }}">
                                                    <input type="hidden" name="price_pax_awal" value="{{ $order->price_pax }}">
                                                    <input type="hidden" name="price_total_awal" value="{{ $order->price_total }}">
                                                    {{-- END TRAC RECORD --}}
                                                    <input type="hidden" name="admin" value="{{ Auth::User()->name }}">
                                                    <input type="hidden" name="author" value="{{ Auth::User()->id }}">
                                                </Form>
                                                <div class="card-box-footer">
                                                    <button type="submit" form="arsipkan-order-{{ $order->id }}" class="btn btn-primary">Archive</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Modal Order not confirmed --------------------------------------------------------------------------------------------------------------- --}}
                                <div class="modal fade" id="reject-order-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="card-box">
                                                <div class="card-box-title">
                                                    <div class="subtitle"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Reject orders</div>
                                                </div>
                                                <form id="rejected-order" action="/fupdate-order-rejected/{{ $order->id }}"method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('put')
                                                    <div class="form-group row">
                                                        <label for="msg" class="col-sm-12 col-md-12 col-form-label">Why the order is rejected? <span> *</span></label>
                                                        <div class="col-sm-12 col-md-12">
                                                            <textarea id="msg" name="msg" placeholder="Add your reason here" class="ckeditor form-control border-radius-0" autofocus required></textarea>
                                                            @error('msg')
                                                                <div class="alert alert-danger">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="form-group">
                                                            {{-- ACTION LOG --}}
                                                            <input type="hidden" name="action_note" value="">
                                                            <input type="hidden" name="page" value="order-admin-detail">
                                                            <input type="hidden" name="initial_state" value="{{ $order->status }}">
                                                            <input type="hidden" name="service" value="Order">
                                                            {{-- END ACTION LOG --}}
                                                            {{-- TRAC RECORD --}}
                                                            <input type="hidden" name="status_awal" value="{{ $order->status }}">
                                                            <input type="hidden" name="number_of_guests_awal" value="{{ $order->number_of_guests }}">
                                                            <input type="hidden" name="guest_detail_awal" value="{{ $order->guest_detail }}">
                                                            <input type="hidden" name="arrival_flight_awal" value="{{ $order->arrival_flight }}">
                                                            <input type="hidden" name="arrival_time_awal" value="{{ $order->arrival_time }}">
                                                            <input type="hidden" name="departure_flight_awal" value="{{ $order->departure_flight }}">
                                                            <input type="hidden" name="departure_time_awal" value="{{ $order->departure_time }}">
                                                            <input type="hidden" name="price_pax_awal" value="{{ $order->price_pax }}">
                                                            <input type="hidden" name="price_total_awal" value="{{ $order->price_total }}">
                                                            {{-- END TRAC RECORD --}}
                                                            <input type="hidden" name="author" value="{{ Auth::User()->id }}">
                                                           
                                                        </div>
                                                    </div>
                                                </Form>
                                                <div class="card-box-footer">
                                                    <button form="rejected-order" type="submit" id="normal-reserve" class="btn btn-danger"><i class="fa fa-ban" aria-hidden="true"></i> Reject Order</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Modal Order Invalid --------------------------------------------------------------------------------------------------------------- --}}
                                <div class="modal fade" id="invalid-order-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="card-box">
                                                <div class="card-box-title">
                                                    <div class="title"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Invalid orders</div>
                                                </div>
                                                <form id="invalid-order" action="/fupdate-order-invalid/{{ $order->id }}"method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('put')
                                                    <div class="form-group row">
                                                        <label for="msg" class="col-sm-12 col-md-12 col-form-label">Why the order is invalid?</label>
                                                        <div class="col-sm-12 col-md-12">
                                                            <textarea id="msg" name="msg" placeholder="Add your reason here" class="ckeditor form-control border-radius-0" autofocus required></textarea>
                                                            @error('msg')
                                                                <div class="alert alert-danger">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="form-group">
                                                            {{-- TRAC RECORD --}}
                                                            <input type="hidden" name="status_awal" value="{{ $order->status }}">
                                                            <input type="hidden" name="number_of_guests_awal" value="{{ $order->number_of_guests }}">
                                                            <input type="hidden" name="guest_detail_awal" value="{{ $order->guest_detail }}">
                                                            <input type="hidden" name="arrival_flight_awal" value="{{ $order->arrival_flight }}">
                                                            <input type="hidden" name="arrival_time_awal" value="{{ $order->arrival_time }}">
                                                            <input type="hidden" name="departure_flight_awal" value="{{ $order->departure_flight }}">
                                                            <input type="hidden" name="departure_time_awal" value="{{ $order->departure_time }}">
                                                            <input type="hidden" name="price_pax_awal" value="{{ $order->price_pax }}">
                                                            <input type="hidden" name="price_total_awal" value="{{ $order->price_total }}">
                                                            {{-- END TRAC RECORD --}}
                                                            <input type="hidden" name="admin" value="{{ Auth::User()->name }}">
                                                            <input type="hidden" name="author" value="{{ Auth::User()->id }}">
                                                        </div>
                                                    </div>
                                                </Form>
                                                <div class="card-box-footer">
                                                    <button type="submit" form="invalid-order" id="normal-reserve" class="btn btn-primary">Invalid</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 desktop">
                            <div class="row">
                                @include('layouts.attentions')
                                <div class="col-md-12">
                                    <div class="card-box">
                                        <div class="card-box-title">
                                            <div class="title">Status</div>
                                        </div> 
                                        <div class="order-status-container">
                                            @if ($order->status == "Active")
                                                <div class="status-active-color">Confirmed</div>
                                                @if ($reservation->send == "yes")
                                                    - <div class="send-email">Email <i class="icon-copy fa fa-envelope" aria-hidden="true"></i></div>
                                                @else
                                                    - <div class="not-send-email">Email <i class="icon-copy fa fa-envelope" aria-hidden="true"></i></div>
                                                @endif
                                                @if ($reservation->status == "Active")
                                                    - <div class="not-approved-order">Waiting <i class="icon-copy ion-clock"></i></div>
                                                @endif
                                            @elseif ($order->status == "Pending")
                                                <div class="status-pending-color">{{ $order->status }}</div>
                                            @elseif ($order->status == "Invalid")
                                                <div class="status-invalid-color">{{ $order->status }}</div>
                                            @elseif ($order->status == "Rejected")
                                                <div class="status-reject-color">{{ $order->status }}</div>
                                            @elseif ($order->status == "Confirmed")
                                                <div class="status-confirmed-color">{{ $order->status }}</div>
                                            @elseif ($order->status == "Approved")
                                                <div class="status-approved-color"><i class="icon-copy fa fa-check-circle" aria-hidden="true"></i> {{ $order->status }}</div>
                                                @if ($reservation->checkin > $now)
                                                    - <div class="standby-order"><p><i class="icon-copy ion-clock"> </i> {{ dateFormat($order->checkin) }}</p></div>
                                                @elseif ($reservation->checkin <= $now and $reservation->checkout > $now)
                                                    - <div class="ongoing-order">Ongoing <i class="icon-copy ion-android-walk"></i></div>
                                                @else
                                                    - <div class="final-order">Final</div>
                                                @endif
                                            @elseif($order->status == "Paid")
                                                <div class="status-paid-color"><i class="icon-copy fa fa-check-circle" aria-hidden="true"></i> {{ $order->status }} ({{ dateFormat($receipt->payment_date) }})</div>
                                            @else
                                                <div class="status-draf-color">{{ $order->status }}</div>
                                            @endif
                                        </div>
                                        @if (count($orderlogs)>0)
                                            <hr class="form-hr">
                                            <p><b>Order Log:</b></p>
                                            <table class="table tb-list">
                                                @foreach ($orderlogs as $no=>$orderlog)
                                                    @php
                                                        $adminorder = $admins->where('id',$orderlog->admin)->first();
                                                    @endphp
                                                    <tr>
                                                        <td>{{ ++$no.". " }}</td>
                                                        <td> {!! dateTimeFormat($orderlog->created_at) !!}</td>
                                                        <td>{!! $adminorder->code !!}</td>
                                                        <td><i>{!! $orderlog->action !!}</i></td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </div>
                                </div>
                                @if (count($order_notes)>0)
                                    <div class="col-md-12">
                                        {{-- ORDER NOTE --}}
                                        <div class="card-box">
                                            <div class="card-box-title">
                                                <div class="title">Order Note</div>
                                            </div> 
                                            @foreach ($order_notes as $order_note)
                                                <div class="container-order-note">
                                                    @php
                                                        $operator = Auth::user()->where('id',$order_note->user_id)->first();
                                                    @endphp
                                                    <p><b>{{ dateTimeFormat($order_note->created_at)." - ".$operator->name }}</b> (<i>{{ $order_note->status }}</i>)</p>
                                                    <p class="m-l-18">{!! $order_note->note !!}</p>
                                                    
                                                    <hr class="form-hr">
                                                </div>
                                            @endforeach
                                            @if ($order->status !== "Paid")
                                                <div class="card-box-footer">
                                                    <a href="modal" data-toggle="modal" data-target="#add-order-note"><button type="button" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Note</button></a>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- MODAL ORDER NOTE --}}
                                        <div class="modal fade" id="add-order-note" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="card-box">
                                                        <div class="card-box-title">
                                                            <div class="title"><i class="fa fa-plus" aria-hidden="true"></i> Add Note</div>
                                                        </div>
                                                        <form id="faddAddNote" action="/fadd-order-note-{{ $order->id }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="form-group row">
                                                                <label for="status" class="col-sm-12">Type</label>
                                                                <div class="col-sm-12">
                                                                    <select name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status') }}">
                                                                        <option selected value="Urgent">Urgent</option>
                                                                        <option value="Waiting">Waiting</option>
                                                                        <option value="Error">Error</option>
                                                                        <option value="Cancel">Cancel</option>
                                                                        <option value="Reject">Reject</option>
                                                                        <option value="Info">Info</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="order_note" class="col-sm-12">Note</label>
                                                                <div class="col-sm-12">
                                                                    <textarea id="order_note" name="order_note" placeholder="Insert order note" class="ckeditor form-control border-radius-0" autofocus required></textarea>
                                                                    @error('order_note')
                                                                        <div class="alert alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="user_id" value="{{ Auth::User()->id }}">
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                        </Form>
                                                        <div class="card-box-footer">
                                                            <div class="form-group">
                                                                <button type="submit" form="faddAddNote" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Submit</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- RECEIPT --}}
                                @if ($order->status == "Approved")
                                    @if ($invoice)
                                        @if (count($receipts)>0)
                                            <div class="col-md-12">
                                                <div class="card-box">
                                                    <div class="card-box-title">
                                                        <div class="title">Payment Receipt</div>
                                                        @if ($invoice->balance > 0)
                                                            <span>
                                                                <a class="action-btn" href="modal" data-toggle="modal" data-target="#desktop-admin-add-receipt-wedding-{{ $order->id }}">
                                                                    <i class="icon-copy fa fa-plus" aria-hidden="true"></i>
                                                                </a>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="pmt-des m-b-8">
                                                        <div class="card-ptext-content">
                                                            <div class="ptext-title">Payment deatline</div>
                                                            <div class="ptext-value">{{ dateFormat($invoice->due_date) }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="pmt-des m-b-8">
                                                        <div class="card-ptext-content">
                                                            @if ($invoice->currency->name == 'USD')
                                                                <div class="ptext-title">Total Invoice USD</div>
                                                                <div class="ptext-value">{{ "$ ".number_format($invoice->total_usd, 0, ",", ".") }}</div>
                                                            @elseif ($invoice->currency->name == 'TWD')
                                                                <div class="ptext-title">Total Invoice USD</div>
                                                                <div class="ptext-value">{{ "$ ".number_format($invoice->total_usd, 0, ",", ".") }}</div>
                                                                <div class="ptext-title">Total Invoice TWD</div>
                                                                <div class="ptext-value">{{ "NT$ ".number_format($invoice->total_twd, 0, ",", ".") }}</div>
                                                            @elseif ($invoice->currency->name == 'CNY')
                                                                <div class="ptext-title">Total Invoice USD</div>
                                                                <div class="ptext-value">{{ "$ ".number_format($invoice->total_usd, 0, ",", ".") }}</div>
                                                                <div class="ptext-title">Total Invoice CNY</div>
                                                                <div class="ptext-value">{{ "¥ ".number_format($invoice->total_cny, 0, ",", ".") }}</div>
                                                            @elseif ($invoice->currency->name == 'IDR')
                                                                <div class="ptext-title">Total Invoice USD</div>
                                                                <div class="ptext-value">{{ "$ ".number_format($invoice->total_usd, 0, ",", ".") }}</div>
                                                                <div class="ptext-title">Total Invoice IDR</div>
                                                                <div class="ptext-value">{{ "Rp ".number_format($invoice->total_idr, 0, ",", ".") }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="m-b-18">
                                                        @foreach ($receipts as $receipt)
                                                            <a class="card-link" href="modal" data-toggle="modal" data-target="#desktop-receipt-{{ $receipt->id }}">
                                                                <div class="card-ptext-margin">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <div class="pmt-container">
                                                                                <i class="icon-copy fa fa-file-image-o" aria-hidden="true"></i>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <div class="pmt-des">
                                                                                <b>
                                                                                    {{ $invoice->inv_no }}
                                                                                    @if ($receipt->status == "Valid")
                                                                                        <i data-toggle="tooltip" data-placement="top" title="@lang('messages.Verified')" style="color: rgb(0, 167, 14); cursor:help" class="icon-copy fa fa-check-circle" aria-hidden="true"></i>
                                                                                    @else
                                                                                        <i style="font-size: 0.7rem; color:rgb(255 0 0);">{{ $receipt->status }}</i>
                                                                                    @endif
                                                                                </b>
                                                                                <div class="card-ptext-content">
                                                                                    <div class="ptext-title">Recived on</div>
                                                                                    <div class="ptext-value">{{ dateFormat($receipt->created_at) }}</div>
                                                                                    @if ($receipt->kurs_name == 'USD')
                                                                                        <div class="ptext-title">Amount</div>
                                                                                        <div class="ptext-value">{{ $receipt->kurs_name }} {{ "$ ".number_format($receipt->amount, 0, ",", ".") }}</div>
                                                                                    @elseif($receipt->kurs_name == 'TWD')
                                                                                        <div class="ptext-title">Amount</div>
                                                                                        <div class="ptext-value">{{ $receipt->kurs_name }} {{ "NT$ ".number_format($receipt->amount, 0, ",", ".") }}</div>
                                                                                    @elseif($receipt->kurs_name == 'CNY')
                                                                                        <div class="ptext-title">Amount</div>
                                                                                        <div class="ptext-value">{{ $receipt->kurs_name }} {{ "¥ ".number_format($receipt->amount, 0, ",", ".") }}</div>
                                                                                    @elseif($receipt->kurs_name == 'IDR')
                                                                                        <div class="ptext-title">Amount</div>
                                                                                        <div class="ptext-value">{{ $receipt->kurs_name }} {{ "Rp ".number_format($receipt->amount, 0, ",", ".") }}</div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @if ($receipt->status != "Valid")
                                                                        <div class="view-receipt">
                                                                            <a href="modal" data-toggle="modal" data-target="#desktop-receipt-{{ $receipt->id }}">
                                                                                <i class="icon-copy fa fa-pencil" aria-hidden="true"></i>
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </a>
                                                            {{-- MODAL VALIDATE RECEIPT --}}
                                                            <div class="modal fade" id="desktop-receipt-{{ $receipt->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="card-box">
                                                                            <div class="card-box-title text-left">
                                                                                <div class="title"> <i class="icon-copy fa fa-file-image-o" aria-hidden="true"></i>Validate Receipt</div>
                                                                            </div>
                                                                                <form id="confirmation-payment-desktop-{{ $receipt->id }}" action="/forder-wedding-confirmation-payment-{{ $receipt->id }}" method="post" enctype="multipart/form-data">
                                                                                    @csrf
                                                                                    <div class="row text-left">
                                                                                        <div class="col-md-12">
                                                                                            <div class="row">
                                                                                                <div class="col-sm-6">
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-12 text-center">
                                                                                                            <div class="modal-receipt-container">
                                                                                                                <img src="/storage/receipt/{{ $receipt->receipt_img }}" alt="">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-sm-6">
                                                                                                    <div class="row">
                                                                                                        <div class="col-5"><p>Order Number</p></div>
                                                                                                        <div class="col-7"><p><b>: {{ $order->orderno }}</b></p></div>
                                                                                                        <div class="col-5"><p>Reservation Number</p></div>
                                                                                                        <div class="col-7"><p><b>: {{ $reservation->rsv_no }}</b></p></div>
                                                                                                        <div class="col-5"><p>Invoice Number</p></div>
                                                                                                        <div class="col-7"><p><b>: {{ $invoice->inv_no }}</b></p></div>
                                                                                                        <div class="col-5"><p>Due Date</p></div>
                                                                                                        <div class="col-7"><p>: {{ dateFormat($invoice->due_date) }}</p></div>
                                                                                                        
                                                                                                        @if ($receipt->status == 'Valid')
                                                                                                            <div class="col-12">
                                                                                                                <hr class="form-hr">
                                                                                                            </div>
                                                                                                            <div class="col-5"><p>Payment Date</p></div>
                                                                                                            <div class="col-7"><p>: {{ dateFormat($receipt->payment_date) }}</p></div>
                                                                                                            <div class="col-5">
                                                                                                                <p>Payment Amount</p>
                                                                                                            </div>
                                                                                                            <div class="col-7">
                                                                                                                @if ($receipt->kurs_name == "USD")
                                                                                                                    <p><b>: {{ "$ ".number_format($receipt->amount, 0, ",", ".") }}</b></p>
                                                                                                                @elseif ($receipt->kurs_name == "CNY")
                                                                                                                    <p><b>: {{ "¥ ".number_format($receipt->amount, 0, ",", ".") }}</b></p>
                                                                                                                @elseif ($receipt->kurs_name == "TWD")
                                                                                                                    <p><b>: {{ "NT$ ".number_format($receipt->amount, 0, ",", ".") }}</b></p>
                                                                                                                @elseif ($receipt->kurs_name == "IDR")
                                                                                                                    <p><b>: {{ "Rp ".number_format($receipt->amount, 0, ",", ".") }}</b></p>
                                                                                                                @endif
                                                                                                            </div>
                                                                                                            <div class="col-12">
                                                                                                                <hr class="form-hr">
                                                                                                            </div>
                                                                                                        @endif
                                                                                                        @if ($order->handled_by == $admin->id)
                                                                                                            @if ($invoice->balance > 0)
                                                                                                                <div class="col-md-12">
                                                                                                                    <div class="form-group">
                                                                                                                        <label for="status" class="form-label">Receipt Status <span>*</span></label>
                                                                                                                        <select name="status" class="custom-select @error('status') is-invalid @enderror" required>
                                                                                                                            <option selected value="{{ $receipt->status }}">{{ $receipt->status }}</option>
                                                                                                                            <option value="Valid">Valid</option>
                                                                                                                            <option value="Invalid">Invalid</option>
                                                                                                                        </select>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                @if ($receipt->status == "Pending" or $receipt->status == "Invalid")
                                                                                                                    <div class="col-md-12">
                                                                                                                        <div class="form-group">
                                                                                                                            <label for="kurs" class="form-label">Currency<span>*</span></label>
                                                                                                                            <select name="kurs" class="custom-select @error('kurs') is-invalid @enderror" required>
                                                                                                                                <option selected value="{{ $receipt->kurs_name ? $receipt->kurs_name : '' }}">{{ $receipt->kurs_name ? $receipt->kurs_name : 'Select Currency' }}</option>
                                                                                                                                <option value="USD">USD</option>
                                                                                                                                <option value="CNY">CNY</option>
                                                                                                                                <option value="TWD">TWD</option>
                                                                                                                                <option value="IDR">IDR</option>
                                                                                                                            </select>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-md-12">
                                                                                                                        <div class="form-group">
                                                                                                                            <label for="amoun" class="form-label col-form-label">Amount</label>
                                                                                                                            <input type="text" name="amount" class="input-icon form-control @error('amount') is-invalid @enderror" placeholder="Insert Amount" value="{{ $receipt->amount }}" required>
                                                                                                                            @error('amount')
                                                                                                                                <div class="alert alert-danger">{{ $message }}</div>
                                                                                                                            @enderror
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                @else
                                                                                                                    <input hidden type="text" name="kurs" value="{{ $receipt->kurs_name }}">
                                                                                                                    <input hidden type="text" name="amount" value="{{ $receipt->amount }}">
                                                                                                                @endif
                                                                                                                <div class="col-md-12">
                                                                                                                    <div class="form-group">
                                                                                                                        <label for="payment_date" class="form-label col-form-label">Payment Date</label>
                                                                                                                        <input readonly type="text" id="payment_date" name="payment_date" class="form-control date-picker @error('payment_date') is-invalid @enderror" placeholder="Payment Date" value="{{ dateFormat($receipt->payment_date) }}" required>
                                                                                                                        @error('payment_date')
                                                                                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                                                                                        @enderror
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-12">
                                                                                                                    <div class="form-group">
                                                                                                                        <label for="note">Descritption </label>
                                                                                                                        <textarea name="note" class="ckeditor form-control @error('note') is-invalid @enderror" placeholder="Description">{{ $receipt->note }}</textarea>
                                                                                                                        @error('note')
                                                                                                                            <span class="invalid-feedback">
                                                                                                                                <strong>{{ $message }}</strong>
                                                                                                                            </span>
                                                                                                                        @enderror
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                                                                            @endif
                                                                                                        @else
                                                                                                            <div class="col-12">
                                                                                                                <div class="notification">
                                                                                                                    @php
                                                                                                                        $validator = $admins->where('id',$order->handled_by)->first()
                                                                                                                    @endphp
                                                                                                                    Only the {{ $validator?$validator->name:"Admin" }} can validate the receipt for this order!
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                </form>
                                                                            <div class="card-box-footer">
                                                                                @if ($order->handled_by == $admin->id)
                                                                                    @if ($invoice->balance > 0)
                                                                                        <button type="submit" form="confirmation-payment-desktop-{{ $receipt->id }}" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> {{ $receipt->status == 'Pending'?"Validate":"Update" }}</button>
                                                                                    @endif
                                                                                @endif
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @php
                                                        $total_payment_usd = $receipts->where('status', 'Valid')->where('kurs_name', 'USD')->sum('amount');
                                                        $total_payment_cny = $receipts->where('status', 'Valid')->where('kurs_name', 'CNY')->sum('amount');
                                                        $total_payment_twd = $receipts->where('status', 'Valid')->where('kurs_name', 'TWD')->sum('amount');
                                                        $total_payment_idr = $receipts->where('status', 'Valid')->where('kurs_name', 'IDR')->sum('amount');
                                                    @endphp
                                                    @if ($total_payment_usd > 0)
                                                        <div class="pmt-des">
                                                            <div class="card-ptext-content">
                                                                <div class="ptext-title">Total Payment USD</div>
                                                                <div class="ptext-value">{{ "$ ".number_format($total_payment_usd, 0, ",", ".") }}</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($total_payment_cny > 0)
                                                        <div class="pmt-des">
                                                            <div class="card-ptext-content">
                                                                <div class="ptext-title">Total Payment CNY</div>
                                                                <div class="ptext-value">{{ "¥ ".number_format($total_payment_cny, 0, ",", ".") }}</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($total_payment_twd > 0)
                                                        <div class="pmt-des">
                                                            <div class="card-ptext-content">
                                                                <div class="ptext-title">Total Payment TWD</div>
                                                                <div class="ptext-value">{{ "NT$ ".number_format($total_payment_twd, 0, ",", ".") }}</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($total_payment_idr > 0)
                                                        <div class="pmt-des">
                                                            <div class="card-ptext-content">
                                                                <div class="ptext-title">Total Payment IDR</div>
                                                                <div class="ptext-value">{{ "Rp ".number_format($total_payment_idr, 0, ",", ".") }}</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($total_payment_usd > 0 || $total_payment_cny > 0 || $total_payment_twd > 0 || $total_payment_idr > 0)
                                                        <hr class="form-hr">
                                                    @endif
                                                    <div class="pmt-des m-t-8">
                                                        <div class="card-ptext-content">
                                                            <div class="ptext-title"><b>Balance</b></div>
                                                            @if ($invoice)
                                                                @if ($invoice->balance <= 1)
                                                                    <div class="ptext-value usd-rate">Paid</div>
                                                                @else
                                                                    @if ($invoice->currency->name == "USD")
                                                                        <div class="ptext-value"><b>{{ "$ ".number_format($invoice->balance, 0, ",", ".") }}</b></div>
                                                                    @elseif ($invoice->currency->name == "CNY")
                                                                        <div class="ptext-value"><b>{{ "¥ ".number_format($invoice->balance, 0, ",", ".") }}</b></div>
                                                                    @elseif ($invoice->currency->name == "TWD")
                                                                        <div class="ptext-value"><b>{{ "NT$ ".number_format($invoice->balance, 0, ",", ".") }}</b></div>
                                                                    @else
                                                                        <div class="ptext-value"><b>{{ "Rp ".number_format($invoice->balance, 0, ",", ".") }}</b></div>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-12">
                                                <div class="card-box">
                                                    <div class="card-box-title">
                                                        <div class="title">Payment Receipt</div>
                                                        <span>
                                                            <a class="action-btn" href="modal" data-toggle="modal" data-target="#desktop-admin-add-receipt-wedding-{{ $order->id }}">
                                                                <i class="icon-copy fa fa-plus" aria-hidden="true"></i>
                                                            </a>
                                                        </span>
                                                    </div>
                                                    <div class="pmt-des">
                                                        <div class="card-ptext-content">
                                                            <div class="ptext-title">Invoice No</div>
                                                            <div class="ptext-value">{{ $invoice->inv_no }}</div>
                                                            <div class="ptext-title">Payment deatline</div>
                                                            <div class="ptext-value">{{ dateFormat($invoice->due_date) }}</div>
                                                            @if ($invoice->currency->name == 'USD')
                                                                <div class="ptext-title"><b>Total Price</b></div>
                                                                <div class="ptext-value"><b>{{ "$ ".number_format($invoice->total_usd, 0, ",", ".") }}</b></div>
                                                            @elseif ($invoice->currency->name == 'TWD')
                                                                <div class="ptext-title"><b>Total USD</b></div>
                                                                <div class="ptext-value"><b>{{ "$ ".number_format($invoice->total_usd, 0, ",", ".") }}</b></div>
                                                                <div class="ptext-title"><b>Total TWD</b></div>
                                                                <div class="ptext-value"><b>{{ "NT$ ".number_format($invoice->total_twd, 0, ",", ".") }}</b></div>
                                                            @elseif ($invoice->currency->name == 'CNY')
                                                                <div class="ptext-title"><b>Total USD</b></div>
                                                                <div class="ptext-value"><b>{{ "$ ".number_format($invoice->total_usd, 0, ",", ".") }}</b></div>
                                                                <div class="ptext-title"><b>Total CNY</b></div>
                                                                <div class="ptext-value"><b>{{ "¥ ".number_format($invoice->total_cny, 0, ",", ".") }}</b></div>
                                                            @elseif ($invoice->currency->name == 'IDR')
                                                                <div class="ptext-title"><b>Total USD</b></div>
                                                                <div class="ptext-value"><b>{{ "$ ".number_format($invoice->total_usd, 0, ",", ".") }}</b></div>
                                                                <div class="ptext-title"><b>Total IDR</b></div>
                                                                <div class="ptext-value"><b>{{ "Rp ".number_format($invoice->total_idr, 0, ",", ".") }}</b></div>
                                                            @endif
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
        </div>
    @endcan
@endsection

