<div id="approvedorders" class="card-box m-b-18">
    <div class="card-box-title">
        <div class="subtitle"><i class="icon-copy fa fa-tags" aria-hidden="true"></i>Approved Orders</div>
    </div>
    <div class="input-container">
        <div class="input-group">
            <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
            <input id="searchApprovedOrderByAgn" type="text" onkeyup="searchApprovedOrderByAgn()" class="form-control" name="search-active-order-byagn" placeholder="Search by agent">
        </div>
        <div class="input-group">
            <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
            <input id="searchApprovedOrderByTyp" type="text" onkeyup="searchApprovedOrderByTyp()" class="form-control" name="search-active-order-type" placeholder="Search by order no">
        </div>
    </div>
    @if (count($approvedorders) > 0)
        <table id="tbApprovedOrders" class="data-table table table-hover table-condensed">
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
                @foreach ($approvedorders as $activeorder)
                    @if ($activeorder->promotion_name != "")
                        @php
                            $promotion_name = json_decode($activeorder->promotion_name);
                            $promotion_disc = json_decode($activeorder->promotion_disc);
                            $total_promotion_disc = array_sum($promotion_disc);
                            $cpn = count($promotion_name);
                        @endphp
                    @else
                        @php
                            $total_promotion_disc = 0;
                        @endphp
                    @endif
                    @php
                        $agent_active = $users->where('id',$activeorder->user_id)->first();
                    @endphp
                    <tr>
                        <td>
                            <b>{{ date("m/d/y",strtotime($activeorder->created_at)) }}</b>
                            <p>{{ $agent_active->name }}</p>
                            <p>{{ "@".$agent_active->office }}</p>
                        </td>
                        <td>
                            <b>{{ $activeorder->orderno }}</b>
                            <p>{{ $activeorder->service }}</p>
                            <div class="status-approved inline-left p-r-8 m-t-8"><i class="icon-copy fa fa-check-circle-o" aria-hidden="true"></i></div>
                            
                            @if ($activeorder->kick_back > 0)
                                <div class="status-kick-back" data-toggle="tooltip" data-placement="top" title="Kick Back ${{ $activeorder->kick_back }}"><i class="icon-copy fa fa-usd" aria-hidden="true"></i></div>
                            @endif
                            @if ($activeorder->discounts > 0)
                                <div class="status-discounts" data-toggle="tooltip" data-placement="top" title="Discounts ${{ $activeorder->discounts }}"><i class="icon-copy fa fa-percent" aria-hidden="true"></i></div>
                            @endif
                            @if ($activeorder->bookingcode_disc > 0)
                                <div class="status-bcode" data-toggle="tooltip" data-placement="top" title="{{ $activeorder->bookingcode." ($".$activeorder->bookingcode_disc.")" }}"><i class="icon-copy fa fa-qrcode" aria-hidden="true"></i></div>
                            @endif
                            @if ($total_promotion_disc > 0)
                                <div class="status-promotion" data-toggle="tooltip" data-placement="top" title="@lang('messages.Promotion'){{ '$'.$total_promotion_disc }}"><i class="icon-copy fa fa-tag" aria-hidden="true"></i></div>
                            @endif
                        </td>
                        <td>
                            {{-- Hotel ============================================================================================================ --}}
                            @if ($activeorder->service == 'Hotel')
                                {{ $activeorder->duration }} Night
                                <p>In:
                                    {{ date("m/d/y", strtotime($activeorder->checkin)) }}</p>
                                <p>Out:
                                    {{ date("m/d/y", strtotime($activeorder->checkout)) }}</p>
                            @elseif ($activeorder->service == 'Hotel Package')
                                {{ $activeorder->duration }} Night
                                <p>In:
                                    {{ date("m/d/y", strtotime($activeorder->checkin)) }}</p>
                                <p>Out:
                                    {{ date("m/d/y", strtotime($activeorder->checkout)) }}</p>
                            @elseif ($activeorder->service == 'Hotel Promo')
                                {{ $activeorder->duration }} Night
                                <p>In:
                                    {{ date("m/d/y", strtotime($activeorder->checkin)) }}</p>
                                <p>Out:
                                    {{ date("m/d/y", strtotime($activeorder->checkout)) }}</p>
                            {{-- Tour ============================================================================================================ --}}
                            @elseif ($activeorder->service == 'Tour Package')
                                @if ($activeorder->duration == "1")
                                    1D
                                @elseif ($activeorder->duration == "2")
                                    2D/1N
                                @elseif ($activeorder->duration == "3")
                                    3D/2N
                                @elseif ($activeorder->duration == "4")
                                    4D/3N
                                @elseif ($activeorder->duration == "5")
                                    5D/4N
                                @elseif ($activeorder->duration == "6")
                                    6D/5N
                                @else
                                    7D/6N
                                @endif
                                <p>Start:
                                    {{ dateTimeFormat($activeorder->travel_date) }}</p>
                                <p>Ends:
                                    @if ($activeorder->duration == "1")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+10 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateTimeFormat($activeorder_end) }}
                                    @elseif ($activeorder->duration == "2")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+34 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateTimeFormat($activeorder_end) }}
                                    @elseif ($activeorder->duration == "3")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+58 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateTimeFormat($activeorder_end) }}
                                    @elseif ($activeorder->duration == "4")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+82 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateTimeFormat($activeorder_end) }}
                                    @elseif ($activeorder->duration == "5")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+106 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateTimeFormat($activeorder_end) }}
                                    @elseif ($activeorder->duration == "6")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+130 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateTimeFormat($activeorder_end) }}
                                    @else
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+154 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateTimeFormat($activeorder_end) }}
                                    @endif
                                </p>
                            {{-- Activity ============================================================================================================ --}}
                            @elseif ($activeorder->service == 'Activity')
                                {{ $activeorder->duration }} Hours
                                <p>Start:
                                    {{ dateTimeFormat($activeorder->travel_date) }}</p>
                                <p>End:
                                    <?php
                                        $activity_duration = $activeorder->duration;
                                        $activity_end=date('Y-m-d H.i', strtotime('+'.$activity_duration.'hours', strtotime($activeorder->travel_date))); 
                                    ?>
                                    {{ dateTimeFormat($activity_end) }}
                            {{-- Transport ============================================================================================================ --}}        
                            @elseif ($activeorder->service == 'Transport')
                                @if ($activeorder->duration == "1")
                                    {{ $activeorder->duration }} Hour
                                @else
                                    {{ $activeorder->duration }} Hours
                                @endif
                                <p>Pickup Date:{{ dateTimeFormat($activeorder->travel_date) }}</p>
                                <p>Return Date:
                                    <?php
                                        $transport_duration = $activeorder->duration;
                                        $return_date=date('Y-m-d H.i', strtotime('+'.$transport_duration.'hours', strtotime($activeorder->travel_date))); 
                                    ?>
                                    {{ dateTimeFormat($return_date) }}</p>
                            @elseif ($activeorder->service == "Wedding Package")
                                {{ $activeorder->duration }} Night
                                <p>In:
                                    {{ date("m/d/y", strtotime($activeorder->checkin)) }}</p>
                                <p>Out:
                                    {{ date("m/d/y", strtotime($activeorder->checkout)) }}</p>
                            @else
                                Not Listed
                            @endif
                        </td>
                        <td>
                            @if ($activeorder->request_quotation == "Yes")
                                <p class="m-t-8 m-b-18" style="color:blue;">Requesting a quote for bookings of more than 8 rooms</p>
                            @else
                                @if ($activeorder->service == "Wedding Package")
                                    {{ $activeorder->groom_name." & ".$activeorder->bride_name }}<br>
                                    {{ $activeorder->number_of_guests." Invitations" }}
                                @else
                                    @php
                                        $gst = json_decode($activeorder->guest_detail);
                                        $ar = is_array($gst);
                                    @endphp
                                    @if ($ar == 1)
                                        @php
                                            $guests = implode(', ',$gst);
                                        @endphp
                                        {!! $guests !!}
                                    @else
                                        {!! $activeorder->guest_detail !!}
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td>
                            
                            @if ($activeorder->request_quotation == "Yes")
                                <p class="m-t-8 m-b-18" style="color:blue;">-</p>
                            @else
                                @if ($activeorder->kick_back > 0 or $activeorder->discounts > 0 or $activeorder->bookingcode_disc > 0 or  $total_promotion_disc > 0)
                                    @if (isset($activeorder->kick_back))
                                        @if ($activeorder->optional_price != "")
                                            <p class="normal-price">{{ '$ ' . number_format(($activeorder->price_total + $activeorder->kick_back + $activeorder->optional_price), 0, ',', '.') }}</p>
                                        @else
                                            <p class="normal-price">{{ '$ ' . number_format(($activeorder->price_total + $activeorder->kick_back), 0, ',', '.') }}</p>
                                        @endif
                                    @else
                                        <p class="normal-price">{{ '$ ' . number_format(($activeorder->final_price + $activeorder->discounts + $activeorder->bookingcode_disc + $total_promotion_disc), 0, ',', '.') }}</p>
                                    @endif
                                @endif
                                {{ '$ ' . number_format($activeorder->final_price, 0, ',', '.') }}
                            @endif
                        </td>
                       
                        {{-- End Status =========================================================================================================== --}}
                        <td class="text-right">
                            <div class="table-action">
                                @if (isset($activeorder->reservations->invoice->payment))
                                    @php
                                        $payment = $activeorder->reservations->invoice->payment;
                                    @endphp
                                    <a href="/orders-admin-{{ $activeorder->id }}"  data-toggle="tooltip" data-placement="top" title="Payment Receipt"> 
                                        <button class="btn-view"><i class="icon-copy fa fa-file-pdf-o" aria-hidden="true"></i></button>
                                    </a>
                                @endif
                                <a href="/orders-admin-{{ $activeorder->id }}"  data-toggle="tooltip" data-placement="top" title="Detail Order"> 
                                    <button class="btn-view"><i class="icon-copy fa fa-eye" aria-hidden="true"></i></button>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-data text-center"><i>No orders are currently active!</i></div>
    @endif
    
</div>