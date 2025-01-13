@section('title','Hotel Details')
@section('content')
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="title"><i class="icon-copy fa fa-building-o"></i>&nbsp; @lang('messages.Hotel')</div>
                    @include('partials.breadcrumbs', [
                        'breadcrumbs' => [
                            ['url' => route('dashboard.index'), 'label' => __('messages.Dashboard')],
                            ['url' => route('hotels.index'), 'label' => __('messages.Hotels')],
                            ['label' => $hotel->name],
                        ]
                    ])
                </div>
                @include('partials.alerts')
                
                <div class="row">
                    @if (session('bookingcode') or $promotions->count() > 0)
                        <div class="col-md-12 promotion-bookingcode">
                            @if (session('bookingcode'))
                                <div class="bookingcode-card">
                                    <div class="icon-card bookingcode">
                                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                                    </div>
                                    <div class="content-card">
                                        <div class="code">{{ session('bookingcode.code')}}</div>
                                        <div class="text-card">@lang('messages.Booking Code') @lang('messages.Aplied')</div>
                                        <div class="text-card">@lang('messages.Expired') {{ dateFormat(session('bookingcode.expired_date')) }}</div>
                                    </div>
                                    <div class="content-card-price">
                                        <div class="price"><span>$</span>{{ session('bookingcode.discounts') }}</div>
                                        <form id="removeBookingCode" action="{{ route('bookingcode.remove') }}" method="POST" style="display: inline;">
                                            @csrf
                                        </form>
                                        <button type="submit" form="removeBookingCode" class="btn-remove-code"><i class="fa fa-close" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            @endif
                            @if ($promotions->count() > 0)
                                @foreach ($promotions as $promotion)
                                    @include('partials.promotion-card', compact('promotion'))
                                @endforeach
                            @endif
                        </div>
                    @endif
                    <div class="col-md-4 mobile">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-box m-b-18 {{ session('booking_dates.duration') < $hotel->min_stay?"form-alert":""; }}">
                                    <div class="card-box-title">
                                        <div class="subtitle"><i class="icon-copy fa fa-search" aria-hidden="true"></i>@lang('messages.Check Price')</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form action="{{ route('hotels.prices',$hotel->code) }}" method="POST" role="search">
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <input readonly id="checkincout" name="checkincout" class="form-control @error('checkincout') is-invalid @enderror" type="text" value="{{ date('m/d/Y',strtotime(session('booking_dates.checkin')))." - ".date('m/d/Y',strtotime(session('booking_dates.checkout'))) }}" placeholder="@lang('messages.Check In') - @lang('messages.Check Out')" required>
                                                        @error('checkincout')
                                                            <span class="invalid-feedback">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                                                    <input type="hidden" name="hotelcode" value="{{ $hotel->code }}">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            @if (session('booking_dates.duration') < $hotel->min_stay)
                                                                <p class="error-notification"> @lang('messages.Minimum stay') {{ $hotel->min_stay }} @lang('messages.nights')</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="submit" class="btn btn-primary" style="float: right;"><i class='icon-copy fa fa-search' aria-hidden='true'></i> @lang('messages.Check Price')</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   </div>
                    <div class="col-md-8">
                        @if (isset($error) && is_array($error) && count($error) > 0 || isset($success) && is_array($success) && count($success) > 0)
                            @include('partials.msg')
                        @endif
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
                            @if (\Session::has('warning'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{!! \Session::get('warning') !!}</li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="card-box m-b-18">
                            <div class="card-box-title">
                                <div class="subtitle"><i class="fa fa-building"></i> {{ $hotel->name }}</div>
                            </div>
                            <div class="page-card">
                                <div class="card-banner">
                                    <img src="{{ asset('storage/hotels/hotels-cover/' . $hotel->cover) }}" alt="{{ $hotel->name }}" loading="lazy">
                                </div>
                                <div class="card-content">
                                    <div class="tab">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#detail" role="tab">@lang('messages.Detail')</a>
                                            </li>
                                            @if ($hotel->facility)
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#facility-tab" role="tab">@lang('messages.Facility')</a>
                                                </li>
                                            @endif
                                            @if ($hotel->benefits)
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#benefits-tab" role="tab">@lang('messages.Benefits')</a>
                                                </li>
                                            @endif
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane fade active show" id="detail" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p>{!! $hotel->description !!}</p>
                                                    </div>
                                                    <div class="col-6 col-md-6">
                                                        <div class="card-subtitle">@lang('messages.Location'):</div>
                                                        <a target="__blank" href="{{ $hotel->map }}">{{ $hotel->region }}</a>
                                                    </div>
                                                    <div class="col-6 col-md-6">
                                                        <div class="card-subtitle">@lang('messages.Address'):</div>
                                                        <p>{{ $hotel->address }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($hotel->facility)
                                                <div class="tab-pane fade" id="facility-tab" role="tabpanel">
                                                    {!! $hotel->facility !!}
                                                </div>
                                            @endif
                                            @if ($hotel->benefits)
                                                <div class="tab-pane fade" id="benefits-tab" role="tabpanel">
                                                    {!! $hotel->benefits !!}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-box-subtitle">
                                <div class="subtitle"><i class="icon-copy fa fa-hotel" aria-hidden="true"></i> @lang('messages.Suites and Villas')</div>
                            </div>
                            <div class="card-box-content">
                                @foreach ($hotel->rooms as $room)
                                    @php
                                        $show_promo = $room->promos->where('book_periode_end','>',$now)->first();
                                    @endphp
                                    @include('partials.hotel-room-card', compact('room','hotel','show_promo'))
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 desktop">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-box m-b-18 {{ session('booking_dates.duration') < $hotel->min_stay?"form-alert":""; }}">
                                    <div class="card-box-title">
                                        <div class="subtitle"><i class="icon-copy fa fa-search" aria-hidden="true"></i>@lang('messages.Check Price')</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form action="{{ route('hotels.prices',$hotel->code) }}" method="POST" role="search">
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <input readonly id="checkincout" name="checkincout" class="form-control @error('checkincout') is-invalid @enderror" type="text" value="{{ date('m/d/Y',strtotime(session('booking_dates.checkin')))." - ".date('m/d/Y',strtotime(session('booking_dates.checkout'))) }}" placeholder="@lang('messages.Check In') - @lang('messages.Check Out')" required>
                                                        @error('checkincout')
                                                            <span class="invalid-feedback">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                                                    <input type="hidden" name="hotelcode" value="{{ $hotel->code }}">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            @if (session('booking_dates.duration') < $hotel->min_stay)
                                                                <p class="error-notification"> @lang('messages.Minimum stay') {{ $hotel->min_stay }} @lang('messages.nights')</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="submit" class="btn btn-primary" style="float: right;"><i class='icon-copy fa fa-search' aria-hidden='true'></i> @lang('messages.Check Price')</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (count($nearhotels)>0)
                        @include('partials.near-hotel', compact('nearhotels'))
                    @endif
                </div>
            </div>

            @include('layouts.footer')
        </div>
    </div>
@endsection