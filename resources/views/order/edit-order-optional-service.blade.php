@section('title',__('Edit Additional Charge'))
@extends('layouts.head')
@section('content')
    <div class="mobile-menu-overlay"></div>
    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="title"><i class="icon-copy fa fa-tags"></i>&nbsp; @lang('messages.Edit Additional Charge')</div>
                            @include('partials.breadcrumbs', [
                                'breadcrumbs' => [
                                    ['url' => route('dashboard.index'), 'label' => __('messages.Dashboard')],
                                    ['url' => route('orders.index'), 'label' => __('messages.Order')],
                                    ['url' => route('edit.order',$order->id), 'label' => $order->orderno],
                                    ['label' => __('messages.Edit Additional Charge')],
                                ]
                            ])
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mobile">
                        <div class="row">
                            @include('layouts.attentions')
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card-box">
                            <div class="card-box-title">
                                <div class="subtitle"><i class="fa fa-asterisk"></i>@lang('messages.Additional Services')</div>
                            </div>
                            <table class="data-table table nowrap">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">@lang('messages.No')</th>
                                        <th style="width: 10%;">@lang('messages.Type')</th>
                                        <th style="width: 5%;">@lang('messages.Service')</th>
                                        <th style="width: 15%;">@lang('messages.Description')</th>
                                        <th style="width: 10%;">@lang('messages.Price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($optional_services as $optional_service)
                                        <tr>
                                            <td>
                                                <div class="table-service-name">{{ $loop->iteration }}</div>
                                            </td>
                                            <td>
                                                <div class="table-service-name">{{ $optional_service->type }}</div>
                                            </td>
                                            <td>
                                                <div class="table-service-name">{{ $optional_service->name }}</div>
                                            </td>
                                            <td>
                                                <div class="table-service-name">{!! $optional_service->description !!}</div>
                                            </td>
                                            <td>
                                                <div class="table-service-name">{{ "$ ". number_format($optional_service->calculatePrice($usdrates, $tax), 0, ".", ",")." /" }}@lang('messages.pax')</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4 desktop">
                        <div class="row">
                            @include('layouts.attentions')
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card-box">
                            <div class="card-box-title">
                                <div class="subtitle"><i class="fa fa-pencil"></i>@lang('messages.Edit Additional Charge')</div>
                            </div>
                            @if (!$optional_rate_orders)
                                <p class="p-b-18"><i>@lang('messages.Your order will be displayed here!')</i></p>
                            @endif
                            <form id="edit-additional-charge" action="{{ $formAction }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @if($optional_rate_orders) 
                                    @method('put')
                                @endif
                    
                                <div class="row">
                                    <div class="col-md-12">
                                        <ol>
                                            @if($optional_rate_id)
                                                @foreach ($optional_rate_id as $index => $orid)
                                                    @php
                                                        $optional_service_name = $optional_services->where('id',$orid)->first();
                                                    @endphp
                                                    <li class="m-b-8">
                                                        <div class="control-group">
                                                            <div class="room-container ">
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="service_date[]">@lang('messages.Date')</label>
                                                                            <select name="service_date[]" class="form-control" required>
                                                                                <option selected value="{{ $service_date[$index] }}">{{ dateFormat($service_date[$index]) }}</option>
                                                                                @foreach ($date_stay as $datestay)
                                                                                    <option value="{{ date('Y-m-d',strtotime($datestay)) }}">{{ dateFormat($datestay) }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="optional_rate_id[]">@lang('messages.Services')</label>
                                                                            <select name="optional_rate_id[]" class="form-control" required>
                                                                                <option selected value="{{ $optional_service_name->id }}">{{ $optional_service_name->name }}</option>
                                                                                @foreach ($optional_services as $optional_service)
                                                                                    <option value="{{ $optional_service->id }}">{{ $optional_service->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group">
                                                                            <label for="number_of_guest[]">@lang('messages.Number of Guest')</label>
                                                                            <input type="number" min="1" max="{{ $order->number_of_guests }}" name="number_of_guest[]" class="form-control m-0 @error('number_of_guest[]') is-invalid @enderror" placeholder="Max {{ $order->number_of_guests }}" value="{{ $oro_nog[$index] }}" required>
                                                                            @error('number_of_guest[]')
                                                                                <div class="alert alert-danger">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-1">
                                                                        <button class="btn btn-remove remove" type="button"><i class="icon-copy fa fa-close" aria-hidden="true"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @endif
                                            <div class="after-add-more"></div>
                                        </ol>
                                    </div>
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <input type="hidden" name="status" value="Draft">
                                </div>
                            </form>
                    
                            <div class="card-box-footer">
                                <button type="button" class="btn btn-primary add-more"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> @lang('messages.Add')</button>
                                <button type="submit" form="edit-additional-charge" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> @lang('messages.Save')</button>
                                <a href="/edit-order-{{ $order->id }}#optional_service">
                                    <button type="button" class="btn btn-danger"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Cancel')</button>
                                </a>
                            </div>
                    
                            <div class="copy hide">
                                <li class="m-b-8">
                                    <div class="control-group">
                                        <div class="room-container ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="service_date[]">@lang('messages.Date')</label>
                                                        <select name="service_date[]" class="form-control" required>
                                                            <option selected value="">@lang('messages.Select date')</option>
                                                            @foreach ($date_stay as $datestay)
                                                                <option value="{{ date('Y-m-d',strtotime($datestay)) }}">{{ dateFormat($datestay) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="optional_rate_id[]">@lang('messages.Services')</label>
                                                        <select name="optional_rate_id[]" class="form-control" required>
                                                            <option selected value="">@lang('messages.Select service')</option>
                                                            @foreach ($optional_services as $optional_service)
                                                                <option value="{{ $optional_service->id }}">{{ $optional_service->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="number_of_guest[]">@lang('messages.Number of Guest')</label>
                                                        <input type="number" min="1" max="{{ $order->number_of_guests }}" name="number_of_guest[]" class="form-control m-0 @error('number_of_guest[]') is-invalid @enderror" placeholder="Max {{ $order->number_of_guests }}" value="" required>
                                                        @error('number_of_guest[]')
                                                            <div class="alert alert-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <button class="btn btn-remove remove" type="button"><i class="icon-copy fa fa-close" aria-hidden="true"></i></button>
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
                @include('layouts.footer')
            </div>
        </div>
    </div>
@endsection
