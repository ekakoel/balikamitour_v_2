<div id="pending-orders" class="card-box m-b-18">
    <div class="card-box-title">
        <div class="subtitle"><i class="icon-copy fa fa-tags" aria-hidden="true"></i> Pending Orders</div>
    </div>
    <div class="input-container">
        <div class="input-group">
            <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
            <input id="searchWaitingOrdersByAgn" type="text" onkeyup="searchWaitingOrdersByAgn()" class="form-control" name="search-waiting-order-byagn" placeholder="Search by agent...">
        </div>
        <div class="input-group">
            <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
            <input id="searchWaitingOrderByType" type="text" onkeyup="searchWaitingOrderByType()" class="form-control" name="search-waiting-order-type" placeholder="Search by type...">
        </div>
    </div>
    @if (count($waitingorders) > 0)
        <table id="tbWaitingOrders" class="data-table table table-hover nowrap table-condensed">
            <thead>
                <tr>
                    <th style="width: 10%;">Agent</th>
                    <th style="width: 20%;">Orders</th>
                    <th style="width: 20%;">Duration</th>
                    <th style="width: 30%;">Guests</th>
                    <th style="width: 10%;">Price</th>
                    <th style="width: 10%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($waitingorders as $pendingorder)
                    @php
                        $agent_pending = $users->where('id',$pendingorder->user_id)->first()
                    @endphp
                    @if ($pendingorder->promotion != "")
                        @php
                            $promotion_name = json_decode($pendingorder->promotion);
                            $promotion_disc = json_decode($pendingorder->promotion_disc);
                            $total_promotion_disc = array_sum($promotion_disc);
                            $cpn = count($promotion_name);
                        @endphp
                    @else
                        @php
                            $total_promotion_disc = 0;
                        @endphp
                    @endif
                    <tr>
                        <td>
                            <b>{{ date("m/d/y",strtotime($pendingorder->created_at)) }}</b>
                            <p>{{ $agent_pending->name }}</p>
                            <p>{{ "@".$agent_pending->office }}</p>
                        </td>
                        <td>
                            <b>{{ $pendingorder->orderno }}</b>
                            <p>{{ $pendingorder->service }}</p>
                            <div class="status-waiting inline-left p-r-8 m-t-8"><i class="icon-copy fa fa-clock-o" aria-hidden="true"></i></div>
                            @if ($pendingorder->kick_back > 0)
                                <div class="status-kick-back" data-toggle="tooltip" data-placement="top" title="Kick Back ${{ $pendingorder->kick_back }}"><i class="icon-copy fa fa-usd" aria-hidden="true"></i></div>
                            @endif
                            @if ($pendingorder->discounts > 0)
                                <div class="status-discounts" data-toggle="tooltip" data-placement="top" title="Discounts ${{ $pendingorder->discounts }}"><i class="icon-copy fa fa-percent" aria-hidden="true"></i></div>
                            @endif
                            @if ($pendingorder->bookingcode_disc > 0)
                                <div class="status-bcode" data-toggle="tooltip" data-placement="top" title="{{ $pendingorder->bookingcode." ($".$pendingorder->bookingcode_disc.")" }}"><i class="icon-copy fa fa-qrcode" aria-hidden="true"></i></div>
                            @endif
                            @if ($total_promotion_disc > 0)
                                <div class="status-promotion" data-toggle="tooltip" data-placement="top" title="@lang('messages.Promotion'){{ '$'.$total_promotion_disc }}"><i class="icon-copy fa fa-tag" aria-hidden="true"></i></div>
                            @endif
                        </td>
                        <td>
                            {{-- Hotel ============================================================================================================ --}}
                            @if ($pendingorder->service == 'Hotel')
                                {{ $pendingorder->duration }} Night
                                <p>In:
                                    {{ date("m/d/y", strtotime($pendingorder->checkin)) }}</p>
                                <p>Out:
                                    {{ date("m/d/y", strtotime($pendingorder->checkout)) }}</p>
                            @elseif ($pendingorder->service == 'Hotel Package')
                                {{ $pendingorder->duration }} Night
                                <p>In:
                                    {{ date("m/d/y", strtotime($pendingorder->checkin)) }}</p>
                                <p>Out:
                                    {{ date("m/d/y", strtotime($pendingorder->checkout)) }}</p>
                            @elseif ($pendingorder->service == 'Hotel Promo')
                                {{ $pendingorder->duration }} Night
                                <p>In:
                                    {{ date("m/d/y", strtotime($pendingorder->checkin)) }}</p>
                                <p>Out:
                                    {{ date("m/d/y", strtotime($pendingorder->checkout)) }}</p>
                            {{-- Tour ============================================================================================================ --}}
                            @elseif ($pendingorder->service == 'Tour Package')
                                @if ($pendingorder->duration == "1")
                                    1D
                                @elseif ($pendingorder->duration == "2")
                                    2D/1N
                                @elseif ($pendingorder->duration == "3")
                                    3D/2N
                                @elseif ($pendingorder->duration == "4")
                                    4D/3N
                                @elseif ($pendingorder->duration == "5")
                                    5D/4N
                                @elseif ($pendingorder->duration == "6")
                                    6D/5N
                                @else
                                    7D/6N
                                @endif
                                <p>Start:
                                    {{ dateTimeFormat($pendingorder->travel_date) }}</p>
                                <p>Ends:
                                    @if ($pendingorder->duration == "1")
                                        <?php $pendingorder_end=date('Y-m-d H.i', strtotime("+10 hours", strtotime($pendingorder->travel_date))); ?>
                                        {{ dateTimeFormat($pendingorder_end) }}
                                    @elseif ($pendingorder->duration == "2")
                                        <?php $pendingorder_end=date('Y-m-d H.i', strtotime("+34 hours", strtotime($pendingorder->travel_date))); ?>
                                        {{ dateTimeFormat($pendingorder_end) }}
                                    @elseif ($pendingorder->duration == "3")
                                        <?php $pendingorder_end=date('Y-m-d H.i', strtotime("+58 hours", strtotime($pendingorder->travel_date))); ?>
                                        {{ dateTimeFormat($pendingorder_end) }}
                                    @elseif ($pendingorder->duration == "4")
                                        <?php $pendingorder_end=date('Y-m-d H.i', strtotime("+82 hours", strtotime($pendingorder->travel_date))); ?>
                                        {{ dateTimeFormat($pendingorder_end) }}
                                    @elseif ($pendingorder->duration == "5")
                                        <?php $pendingorder_end=date('Y-m-d H.i', strtotime("+106 hours", strtotime($pendingorder->travel_date))); ?>
                                        {{ dateTimeFormat($pendingorder_end) }}
                                    @elseif ($pendingorder->duration == "6")
                                        <?php $pendingorder_end=date('Y-m-d H.i', strtotime("+130 hours", strtotime($pendingorder->travel_date))); ?>
                                        {{ dateTimeFormat($pendingorder_end) }}
                                    @else
                                        <?php $pendingorder_end=date('Y-m-d H.i', strtotime("+154 hours", strtotime($pendingorder->travel_date))); ?>
                                        {{ dateTimeFormat($pendingorder_end) }}
                                    @endif
                                </p>
                            {{-- Activity ============================================================================================================ --}}
                            @elseif ($pendingorder->service == 'Activity')
                                {{ $pendingorder->duration }} Hours
                                <p>Start:
                                    {{ dateTimeFormat($pendingorder->travel_date) }}</p>
                                <p>End:
                                    <?php
                                        $activity_duration = $pendingorder->duration;
                                        $activity_end=date('Y-m-d H.i', strtotime('+'.$activity_duration.'hours', strtotime($pendingorder->travel_date))); 
                                    ?>
                                    {{ dateTimeFormat($activity_end) }}
                            {{-- Transport ============================================================================================================ --}}        
                            @elseif ($pendingorder->service == 'Transport')
                                @if ($pendingorder->duration == "1")
                                    {{ $pendingorder->duration }} Hour
                                @else
                                    {{ $pendingorder->duration }} Hours
                                @endif
                                <p>Pickup Date:{{ dateTimeFormat($pendingorder->travel_date) }}</p>
                                <p>Return Date:
                                    <?php
                                        $transport_duration = $pendingorder->duration;
                                        $return_date=date('Y-m-d H.i', strtotime('+'.$transport_duration.'hours', strtotime($pendingorder->travel_date))); 
                                    ?>
                                    {{ dateTimeFormat($return_date) }}</p>
                            @elseif ($pendingorder->service == "Wedding Package")
                                {{ $pendingorder->duration }} Night
                                <p>In:
                                    {{ date("m/d/y", strtotime($pendingorder->checkin)) }}</p>
                                <p>Out:
                                    {{ date("m/d/y", strtotime($pendingorder->checkout)) }}</p>
                            @else
                                Not Listed
                            @endif
                        </td>
                        <td class="wrap-balance">
                            @if ($pendingorder->request_quotation == "Yes")
                                <p class="m-t-8 m-b-18" style="color:blue;">Requesting a quote for bookings of more than 8 rooms</p>
                            @else
                                @if ($pendingorder->service == "Wedding Package")
                                    {{ $pendingorder->groom_name." & ".$pendingorder->bride_name }}<br>
                                    {{ $pendingorder->number_of_guests." Invitations" }}
                                @else
                                    @php
                                        $gst = json_decode($pendingorder->guest_detail);
                                        $ar = is_array($gst);
                                    @endphp
                                    @if ($ar == 1)
                                        @php
                                            $guests = implode(', ',$gst);
                                        @endphp
                                        {!! $guests !!}
                                    @else
                                        {!! $pendingorder->guest_detail !!}
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td>
                            @if ($pendingorder->request_quotation == "Yes")
                                <p class="m-t-8 m-b-18" style="color:blue;">-</p>
                            @else
                                @if ($pendingorder->kick_back > 0 or $pendingorder->discounts > 0 or $pendingorder->bookingcode_disc > 0 or  $total_promotion_disc > 0)
                                    @if (isset($pendingorder->kick_back))
                                        @if ($pendingorder->optional_price != "")
                                            <p class="normal-price">{{ '$ ' . number_format(($pendingorder->price_total + $pendingorder->kick_back + $pendingorder->optional_price), 0, ',', '.') }}</p>
                                        @else
                                            <p class="normal-price">{{ '$ ' . number_format(($pendingorder->price_total + $pendingorder->kick_back), 0, ',', '.') }}</p>
                                        @endif
                                    @else
                                        <p class="normal-price">{{ '$ ' . number_format(($pendingorder->final_price + $pendingorder->discounts + $pendingorder->bookingcode_disc + $total_promotion_disc), 0, ',', '.') }}</p>
                                    @endif
                                @endif
                                {{ '$ ' . number_format($pendingorder->final_price, 0, ',', '.') }}
                            @endif
                        </td>

                        <td class="text-right">
                            <div class="table-action">
                                <a href="/orders-admin-{{ $pendingorder->id }}"  data-toggle="tooltip" data-placement="top" title="Detail Order">
                                    <button class="btn-view"><i class="icon-copy fa fa-eye" aria-hidden="true"></i></button>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-data text-center"><i>No orders are currently pending!</i></div>
    @endif
</div>