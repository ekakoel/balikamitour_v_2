@section('title',__('Edit Order'))
@extends('layouts.head')
@section('content')
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20">
        <div class="page-header">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="title">
                        <i class="icon-copy fa fa-tags"></i>&nbsp; 
                        @lang($order->status == "Draft" ? 'messages.Order' : 'messages.Detail Order')
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        @include('partials.breadcrumbs', [
                            'breadcrumbs' => [
                                ['url' => route('dashboard.index'), 'label' => __('messages.Dashboard')],
                                ['url' => route('orders.index'), 'label' => __('messages.Order')],
                                ['label' => $order->orderno],
                            ]
                        ])
                    </nav>
                </div>
            </div>
        </div>
        @include('partials.alerts')
        <div class="row">
            @if (count($attentions)>0)
                <div class="col-md-4 mobile">
                    <div class="row">
                        @include('layouts.attentions')
                    </div>
                </div>
            @endif
            @include($services[$order->service] ?? 'default-view')
            @if (count($attentions)>0)
                <div class="col-md-4 desktop">
                    <div class="row">
                        @include('layouts.attentions')
                    </div>
                </div>
            @endif
        </div>
        @include('layouts.footer')
    </div>
</div>
@endsection
