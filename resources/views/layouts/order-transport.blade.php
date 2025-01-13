<div class="col-md-12">
    <div class="card-box">
        <div class="card-box-title">
            <div class="subtitle"><i class="fa fa-car"></i>@lang('messages.Transport Orders')</div>
        </div>
        <div class="input-container">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
                <input id="searchTransportOrderNo" type="text" onkeyup="searchTable('searchTransportOrderNo','tbTransport',1)" class="form-control" name="search-active-order-byagn" placeholder="Order number">
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
                <input id="searchTransportByGuestName" type="text" onkeyup="searchTable('searchTransportByGuestName','tbTransport',2)" class="form-control" name="search-active-order-type" placeholder="Guest name">
            </div>
        </div>
        <table id="tbTransport" class="data-table table m-b-30">
            <thead>
                <tr>
                    <th style="width: 1%;">@lang('messages.No')</th>
                    <th style="width: 10%;">@lang('messages.Transport')</th>
                    <th style="width: 10%;">@lang('messages.Guest Name')</th>
                    <th style="width: 10%;">@lang('messages.Service')</th>
                    <th style="width: 10%;">@lang('messages.Price')</th>
                    <th style="width: 5%;">@lang('messages.Action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transportorders as $no => $transportorder)
                    @php
                        $rsv_trans = $reservations->where('id',$transportorder->rsv_id)->first();
                    @endphp
                    @if ($transportorder->promotion_name != "")
                        @php
                            $promotion_name = json_decode($transportorder->promotion_name);
                            $promotion_disc = json_decode($transportorder->promotion_disc);
                            $total_promotion_disc = array_sum($promotion_disc);
                            $cpn = count($promotion_name);
                        @endphp
                    @else
                        @php
                            $total_promotion_disc = 0;
                        @endphp
                    @endif
                    @if ($transportorder->status == "Rejected")
                        <tr style="background-color: #ffd4d4;">
                    @elseif($transportorder->status == "Confirmed" and $rsv_trans->send == "yes")
                        <tr data-toggle="tooltip" data-placement="top" title="@lang('messages.This order requires your approval')" style="background-color: #fbffa9;">
                    @else
                        <tr>
                    @endif
                        <td>{{ ++$no }}</td>
                        <td>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="p-0 m-0"><b>{{ $transportorder->orderno }}</b></p>
                                    <p class="p-0 m-0">{{ $transportorder->service_type }}</p>
                                    <p class="p-0 m-0">{{ $transportorder->servicename }}</p>
                                    @if ($transportorder->status == "Rejected")
                                        <div class="status-rejected inline-left p-r-8"></div>
                                    @elseif ($transportorder->status == "Paid")
                                        <div class="status-paid inline-left p-r-8"><i class="icon-copy ion-android-checkmark-circle"></i></div>
                                    @elseif ($transportorder->status == "Approved")
                                        <div class="status-approved inline-left p-r-8"><i class="icon-copy fa fa-check-circle-o" aria-hidden="true"></i></div>
                                        <p><i class="color-blue">@lang('messages.Awaiting Payment')</i></p>
                                    @elseif ($transportorder->status == "Confirmed")
                                        <div class="status-confirmed inline-left p-r-8"><i class="icon-copy fa fa-check-circle" aria-hidden="true"></i></div>
                                    @elseif ($transportorder->status == "Invalid")
                                        <div class="status-invalid inline-left p-r-8"><i class="icon-copy fa fa-close" aria-hidden="true"></i></div>
                                    @elseif ($transportorder->status == "Active")
                                        <div class="status-progress inline-left p-r-8"><i class="icon-copy fa fa-clock-o" aria-hidden="true"></i></div>
                                    @elseif ($transportorder->status == "Pending")
                                        <div class="status-waiting inline-left p-r-8"><span class="icon-copy ti-alarm-clock"></span></div>
                                    @elseif ($transportorder->status == "Draft")
                                        <div class="status-draft inline-left p-r-8"><span class="icon-copy fa fa-pencil"></span></div>
                                    @endif
                                    @if ($transportorder->discounts > 0)
                                        <div class="status-discounts inline-left p-r-8" data-toggle="tooltip" data-placement="top" title="@lang('messages.Discount') ${{ $transportorder->discounts }}"></div>
                                    @endif
                                    @if ($transportorder->bookingcode_disc > 0)
                                        <div class="status-bcode inline-left p-r-8" data-toggle="tooltip" data-placement="top" title="{{ $transportorder->bookingcode." ($".$transportorder->bookingcode_disc.")" }}"></div>
                                    @endif
                                        @if ($total_promotion_disc > 0)
                                        <div class="status-promotion inline-left p-r-8" data-toggle="tooltip" data-placement="top" title="@lang('messages.Promotion'){{ ' $'.$total_promotion_disc }}"></div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="p-0 m-0">{!! $transportorder->guest_detail !!}</p>
                        </td>
                        <td>
                            <p class="p-0 m-0">{{ $transportorder->number_of_guests." " }}@lang('messages.Guests')</p>
                            @if ($transportorder->service_type == "Daily Rent")
                                @if ($transportorder->duration > 1)
                                    {{ $transportorder->duration." " }}@lang('messages.Days')
                                    <?php $transport_end=date('Y-m-d H.i', strtotime("+".$transportorder->duration." day", strtotime($transportorder->travel_date))); ?>
                                    <p class="p-0 m-0">{{ "In :". dateFormat($transportorder->travel_date) }}</p>
                                    <p class="p-0 m-0">{{ "Out :". dateFormat($transport_end) }}</p>
                                @else
                                    {{ $transportorder->duration." Day" }}
                                @endif
                            @else
                                <p class="p-0 m-0">{{ dateFormat($transportorder->travel_date) }}</p>
                                @if ($transportorder->src != "")
                                    <p class="p-0 m-0">{{ "Src : ". $transportorder->src }}</p>
                                @endif
                                @if ($transportorder->dst != "")
                                    <p class="p-0 m-0">{{ "Dst : ". $transportorder->dst }}</p>
                                @endif
                            @endif
                            @php
                                $optional_services = $optionalrates->where('service_id','=',$transportorder->service_id);
                                $optional_rate = $optional_rate_order->where('order_id','=',$transportorder->id);
                                $price_unit = $optional_rate_order->where('order_id','=',$transportorder->id)->sum('price_unit');
                                $total_price = $price_unit + $transportorder->price_total; 
                            @endphp
                        </td>
                        <td>
                            @if ($transportorder->discounts > 0 or $transportorder->bookingcode_disc > 0 or  $total_promotion_disc > 0)
                                <p class="normal-price">{{ '$ ' . number_format(($transportorder->price_total), 0, ',', '.') }}</p>
                            @endif
                            <p>{{ '$ ' . number_format($transportorder->final_price, 0, ',', '.') }}</p>
                        </td>
                        <td class="text-right">
                            <div class="table-action">
                                @if ($transportorder->status == "Draft")
                                    <a href="/edit-order-{{ $transportorder->id }}">
                                        <button class="btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-copy fa fa-pencil"></i></button>
                                    </a>
                                    <form class="display-content" action="/delete-order/{{ $transportorder->id }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" name="author" value="{{ Auth::user()->id }}">
                                        <button class="btn-delete" onclick="return confirm('@lang('messages.Are you sure?')');" type="submit" data-toggle="tooltip" data-placement="top" title="@lang('messages.Delete')"><i class="icon-copy fa fa-trash"></i></button>
                                    </form>
                                @elseif ($transportorder->status == "Rejected")
                                    <a href="/detail-order-{{ $transportorder->id }}">
                                        <button class="btn-view" data-toggle="tooltip" data-placement="top" title="Detail"><i class="dw dw-eye"></i></button>
                                    </a>
                                    <form class="display-content" action="/delete-order/{{ $transportorder->id }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" name="author" value="{{ Auth::user()->id }}">
                                        <button class="btn-delete" onclick="return confirm('@lang('messages.Are you sure?')');" type="submit" data-toggle="tooltip" data-placement="top" title="@lang('messages.Delete')"><i class="icon-copy fa fa-trash"></i></button>
                                    </form>
                                @elseif ($transportorder->status == "Confirmed" and $rsv_trans->send == "yes")
                                    <a href="/detail-order-{{ $transportorder->id }}">
                                        <button class="btn-view" data-toggle="tooltip" data-placement="top" title="Detail"><i class="dw dw-eye"></i></button>
                                    </a>
                                    <form id="approveOrder" class="hidden" action="/fapprove-order-{{ $transportorder->id }}"method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                    </form>
                                    <button type="submit" form="approveOrder" class="btn-approve" data-toggle="tooltip" data-placement="top" title="@lang('messages.Approve Order')"><i class="icon-copy fa fa-check-circle" aria-hidden="true"></i></button>
                                @else
                                    <a href="/detail-order-{{ $transportorder->id }}">
                                        <button class="btn-view" data-toggle="tooltip" data-placement="top" title="Detail"><i class="dw dw-eye"></i></button>
                                    </a>
                                @endif
                                
                            </div>
                        </td>
                    </tr>
                   
                @endforeach
            </tbody>
        </table>
    </div>
</div>