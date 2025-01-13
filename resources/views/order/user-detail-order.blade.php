
@section('content')
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        @if ($order->status == "Draft")
                            <div class="title"><i class="icon-copy fa fa-tags"></i>&nbsp; @lang('messages.Order')</div>
                        @else
                            <div class="title"><i class="icon-copy fa fa-tags"></i>&nbsp; @lang('messages.Detail Order')</div>
                        @endif
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard">@lang('messages.Dashboard')</a></li>
                                <li class="breadcrumb-item"><a href="orders">@lang('messages.Order')</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $order->orderno }}</a></li>
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
                @if (count($attentions)>0)
                    <div class="col-md-4 mobile">
                        <div class="row">
                            @include('layouts.attentions')
                        </div>
                    </div>
                @endif
                @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package")
                    @include('order.detail-order-hotel')
                @elseif($order->service == "Tour Package")
                    @include('order.detail-order-tour')
                @elseif($order->service == "Activity")
                    @include('order.detail-order-activity')
                @elseif($order->service == "Transport")
                    @include('order.detail-order-transport')
                @elseif($order->service == "Wedding Package")
                    @include('order.detail-order-wedding')
                @endif
                @if (count($attentions)>0 or $order->status == "Rejected")
                    <div class="col-md-4 desktop">
                        <div class="row">
                            @include('layouts.attentions')
                            @if ($order->status == "Rejected")
                                <div class="col-md-12">
                                    <div class="card-box">
                                        <div class="card-box-title">
                                            <div class="subtitle">@lang('messages.Order Information')</div>
                                        </div>
                                        <div class="banner-right">
                                            <ul class="attention">
                                                <p>We regret to inform you that your order has been rejected for various reasons.</p><br>
                                                {!! $order->msg !!}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            @include('layouts.footer')
        </div>
    </div>
@endsection