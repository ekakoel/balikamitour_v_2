<div id="activeorders" class="card-box m-b-18">
    <div class="card-box-title">
        <div class="subtitle"><i class="icon-copy fa fa-tags" aria-hidden="true"></i>Active Orders</div>
    </div>
    <div class="input-container">
        <div class="input-group">
            <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
            <input id="searchActiveOrderByAgn" type="text" onkeyup="searchActiveOrderByAgn()" class="form-control" name="search-active-order-byagn" placeholder="Search by agent">
        </div>
        <div class="input-group">
            <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
            <input id="searchActiveOrderByType" type="text" onkeyup="searchActiveOrderByType()" class="form-control" name="search-active-order-type" placeholder="Search by order no">
        </div>
    </div>
    @if (count($activeorders) > 0)
        <table id="tbActiveOrders" class="data-table table table-hover table-condensed">
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
                @foreach ($activeorders as $activeorder)
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
                            <p>{{ $agent_active->name }}</p>
                            <p>{{ "@".$agent_active->office }}</p>
                        </td>
                        <td>
                            <b>{{ $activeorder->orderno }}</b>
                            <p>{{ $activeorder->service }}</p>
                            
                            <div class="status-active inline-left p-r-8 m-t-8"><i class="icon-copy fa fa-lightbulb-o" aria-hidden="true"></i></div>
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
                                    {{ dateFormat($activeorder->checkin) }}</p>
                                <p>Out:
                                    {{ dateFormat($activeorder->checkout) }}</p>
                            @elseif ($activeorder->service == 'Hotel Package')
                                {{ $activeorder->duration }} Night
                                <p>In:
                                    {{ dateFormat($activeorder->checkin) }}</p>
                                <p>Out:
                                    {{ dateFormat($activeorder->checkout) }}</p>
                            @elseif ($activeorder->service == 'Hotel Promo')
                                {{ $activeorder->duration }} Night
                                <p>In:
                                    {{ dateFormat($activeorder->checkin) }}</p>
                                <p>Out:
                                    {{ dateFormat($activeorder->checkout) }}</p>
                            {{-- Tour ============================================================================================================ --}}
                            @elseif ($activeorder->service == 'Tour Package')
                                {{ $activeorder->duration }}
                                <p>Start:
                                    {{ dateFormat($activeorder->travel_date) }}</p>
                                <p>Ends:
                                    @if ($activeorder->duration == "1D")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+10 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateFormat($activeorder_end) }}
                                    @elseif ($activeorder->duration == "2D/1N")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+34 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateFormat($activeorder_end) }}
                                    @elseif ($activeorder->duration == "3D/2N")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+58 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateFormat($activeorder_end) }}
                                    @elseif ($activeorder->duration == "4D/3N")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+82 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateFormat($activeorder_end) }}
                                    @elseif ($activeorder->duration == "5D/4N")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+106 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateFormat($activeorder_end) }}
                                    @elseif ($activeorder->duration == "6D/5N")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+130 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateFormat($activeorder_end) }}
                                    @elseif ($activeorder->duration == "7D/6N")
                                        <?php $activeorder_end=date('Y-m-d H.i', strtotime("+154 hours", strtotime($activeorder->travel_date))); ?>
                                        {{ dateFormat($activeorder_end) }}
                                    @else
                                    @endif
                                </p>
                            {{-- Activity ============================================================================================================ --}}
                            @elseif ($activeorder->service == 'Activity')
                                {{ $activeorder->duration }} Hours
                                <p>Start:
                                    {{ dateFormat($activeorder->travel_date) }}</p>
                                <p>End:
                                    <?php
                                        $activity_duration = $activeorder->duration;
                                        $activity_end=date('Y-m-d H.i', strtotime('+'.$activity_duration.'hours', strtotime($activeorder->travel_date))); 
                                    ?>
                                    {{ dateFormat($activity_end) }}
                            {{-- Transport ============================================================================================================ --}}        
                            @elseif ($activeorder->service == 'Transport')
                                @if ($activeorder->duration == "1")
                                    {{ $activeorder->duration }} Day
                                @else
                                    {{ $activeorder->duration }} Days
                                @endif
                                <p>Pickup Date:{{ dateFormat($activeorder->travel_date) }}</p>
                                <p>Return Date:
                                    <?php
                                        $transport_duration = $activeorder->duration;
                                        $return_date=date('Y-m-d H.i', strtotime('+'.$transport_duration.'days', strtotime($activeorder->travel_date))); 
                                    ?>
                                    {{ dateFormat($return_date) }}</p>
                            @else
                                Not Listed
                            @endif
                        </td>
                        <td>
                            @php
                                $g_det = json_decode($activeorder->guest_detail);
                                $ar = is_array($g_det);
                                // $fruits = explode(",", json_encode($g_det));
                            @endphp
                           @if ($ar == 1)
                          
                               @foreach ($g_det as $guest_detail)
                                   {{ $guest_detail }}
                               @endforeach
                            @else
                                {{ $activeorder->guest_detail }}
                           @endif
                        </td>
                        <td>
                            @if ($activeorder->kick_back > 0 or $activeorder->discounts > 0 or $activeorder->bookingcode_disc > 0 or  $total_promotion_disc > 0)
                                @if (isset($activeorder->kick_back))
                                    @if ($activeorder->optional_price != "")
                                        <p class="normal-price">{{ '$ ' . number_format(($activeorder->price_total + $activeorder->kick_back + $activeorder->optional_price), 0, ',', '.') }}</p>
                                    @else
                                        <p class="normal-price">{{ '$ ' . number_format(($activeorder->price_total + $activeorder->kick_back), 0, ',', '.') }}</p>
                                    @endif

                                @else
                                    <p class="normal-price">{{ '$ ' . number_format(($activeorder->price_total), 0, ',', '.') }}</p>
                                @endif
                                
                            
                                
                            @endif
                            {{ '$ ' . number_format($activeorder->final_price, 0, ',', '.') }}
                        </td>
                       
                        {{-- End Status =========================================================================================================== --}}
                        <td class="text-right">
                            <div class="table-action">
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