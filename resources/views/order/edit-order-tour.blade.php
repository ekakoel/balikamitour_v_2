<div class="col-md-8">
    <div class="card-box">
        <div class="card-box-title">
            <div class="subtitle"><i class="fa fa-pencil"></i> @lang('messages.Edit Order')</div>
        </div>
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="order-bil text-left">
                    <img src="{{ config('app.logo_dark') }}" alt="{{ config('app.alt_logo') }}">
                </div>
            </div>
            <div class="col-6 col-md-6 flex-end">
                <div class="label-title">@lang('messages.Order')</div>
            </div>
            <div class="col-md-12 text-right">
                <div class="label-date float-right" style="width: 100%">
                    {{ dateFormat($order->created_at) }}
                </div>
            </div>
        </div>
        <div class="business-name">{{ $business->name }}</div>
        <div class="bussines-sub">{{ $business->caption }}</div>
        <hr class="form-hr">
        <div class="row">
            <div class="col-md-6">
                <table class="table tb-list">
                    <tr>
                        <td class="htd-1">
                            @lang('messages.Order No')
                        </td>
                        <td class="htd-2">
                            <b>{{ $order->orderno }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="htd-1">
                            @lang('messages.Order Date')
                        </td>
                        <td class="htd-2">
                            {{ dateFormat($order->created_at) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="htd-1">
                            @lang('messages.Service')
                        </td>
                        <td class="htd-2">
                            : @lang('messages.'.$order->service)
                        </td>
                    </tr>
                    <tr>
                        <td class="htd-1">
                            @lang('messages.Location')
                        </td>
                        <td class="htd-2">
                            : {{ $order->location }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                @if ($order->status == "Active")
                    <div class="page-status" style="color: rgb(0, 156, 21)"> @lang('messages.Confirmed') <span>@lang('messages.Status'):</span></div>
                @elseif ($order->status == "Pending")
                    <div class="page-status" style="color: #dd9e00">@lang('messages.'.$order->status) <span>@lang('messages.Status'):</span></div>
                @elseif ($order->status == "Rejected")
                    <div class="page-status" style="color: rgb(160, 0, 0)">@lang('messages.'.$order->status) <span>@lang('messages.Status'):</span></div>
                @else
                    <div class="page-status" style="color: rgb(48, 48, 48)">@lang('messages.'.$order->status) <span>@lang('messages.Status'):</span></div>
                @endif
            </div>
        </div>
        {{-- ORDER  --}}
        <div class="page-subtitle">@lang('messages.Order')</div>
        <div class="row">
            <div class="col-md-6">
                <table class="table tb-list">
                    <tr>
                        <td class="htd-1">
                            @lang('messages.Tour Package')
                        </td>
                        <td class="htd-2">
                            {{ $order->subservice }}
                        </td>
                    </tr>
                    <tr>
                        <td class="htd-1">
                            @lang('messages.Quantity')
                        </td>
                        <td class="htd-2">
                            @if (isset($tour_price->max_qty))
                                {{ $tour_price->max_qty." pax" }}
                            @else
                                : -
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table tb-list">
                    <tr>
                        <td class="htd-1">
                            @lang('messages.Duration')
                        </td>
                        <td class="htd-2">
                            {{ $order->duration." " }}@lang('messages.Night')
                        </td>
                    </tr>
                    <tr>
                        <td class="htd-1">
                            @lang('messages.Tour Start')
                        </td>
                        <td class="htd-2">
                            {{ dateFormat($order->checkin) }}
                    </tr>
                    <tr>
                        <td class="htd-1">
                            @lang('messages.Tour End')
                        </td>
                        <td class="htd-2">
                            {{ dateFormat($order->checkout) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @if ($order->destinations != "")
            <div class="page-text">
                <hr class="form-hr">
                <b>@lang('messages.Destinations') :</b> <br>
                {!! $order->destinations !!}
            </div>
        @endif
        @if ($order->itinerary != "")
            <div class="page-text">
                <hr class="form-hr">
                <b>@lang('messages.Itinerary') :</b> <br>
                {!! $order->itinerary !!}
            </div>
        @endif
        @if ($order->include != "")
            <div class="page-text">
                <hr class="form-hr">
                <b>@lang('messages.Include') :</b> <br>
                {!! $order->include !!}
            </div>
        @endif
        @if ($order->additional_info != "")
            <div class="page-text">
                <hr class="form-hr">
                <b>@lang('messages.Additional Information') :</b> <br>
                {!! $order->additional_info !!}
            </div>
        @endif
        @if ($order->cancellation_policy != "")
            <div class="page-text">
                <hr class="form-hr">
                <b>@lang('messages.Cancelation Policy') :</b>
                <p>{!! $order->cancellation_policy !!}</p>
            </div>
        @endif
        <form id="edit-order" action="/fupdate-order/{{ $order->id }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            {{-- GUESTS  --}}
            <div class="page-subtitle">@lang('messages.Guests')</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="travel_date"> @lang('messages.Travel Date')</label>
                        <input id="travel-date" name="travel_date" min="{{ dateFormat($now) }}" type="text" class="form-control datetimepicker  @error('travel_date') is-invalid @enderror" value="{{ dateTimeFormat($order->travel_date) }}" required>
                        @error('travel_date')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="number_of_guests">@lang('messages.Number of guest') </label>
                        <select type="number" id="nog" onchange="calculate()"  min="2" max="100" name="number_of_guests" class="form-control @error('number_of_guests') is-invalid @enderror" placeholder="@lang('messages.Number of Guest')"  required> 
                            <option selected value="{{ $order->number_of_guests }}">{{ $order->number_of_guests." pax" }}</option>
                            @php
                                $maxq = 2;
                            @endphp
                            @for ($mq = 0; $mq < $qty; $mq++)
                                @if ($mq > 1)
                                    <option value="{{ $mq+1 }}">{{ $mq+1 }} pax</option>
                                @endif
                            @endfor
                        </select>
                        @error('number_of_guests')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pickup_location">@lang('messages.Pick up location') </label>
                        <input type="text" id="pickup_location" name="pickup_location" class="form-control @error('pickup_location') is-invalid @enderror" placeholder="@lang('messages.ex'): @lang('messages.Hotel Name')/@lang('messages.Airport')" value="{{ $order->pickup_location }}" required>
                        @error('pickup_location')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dropoff_location">@lang('messages.Drop off location') </label>
                        <input type="text" id="dropoff_location" name="dropoff_location" class="form-control @error('dropoff_location') is-invalid @enderror" placeholder="@lang('messages.ex'): @lang('messages.Hotel Name')/@lang('messages.Airport')" value="{{ $order->dropoff_location }}" required>
                        @error('dropoff_location')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="guest_detail"> @lang('messages.Guest Detail')</label>
                        <textarea id="guest_detail" name="guest_detail" class="ckeditor form-control  @error('guest_detail') is-invalid @enderror" placeholder="@lang('messages.Insert the names of all guests')" required>{!! $order->guest_detail !!}</textarea>
                        @error('guest_detail')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="page-subtitle">@lang('messages.Note')</div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <textarea id="note" name="note" placeholder="@lang('messages.Optional')" class="ckeditor form-control border-radius-0">{{ $order->note }}</textarea>
                        @error('note')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="page-subtitle">@lang('messages.Price')</div>
            <div class="row">
                <div class="col-md-12 m-b-8">
                    <div class="box-price-kicked">
                        <div class="row">
                            <div class="col-6 col-md-6">
                                <div class="promo-text">@lang('messages.Price')/@lang('messages.pax')</div>
                                @if ($order->bookingcode_disc > 0 or $order->discounts > 0 or $order->kick_back > 0 or $order->promotion_disc > 0)
                                    <div class="promo-text">@lang('messages.Normal Price')</div>
                                    <hr class="form-hr">
                                    @if ($order->kick_back > 0)
                                        <div class="kick-back">@lang('messages.Kick Back')</div>
                                    @endif
                                    @if ($order->bookingcode_disc > 0)
                                        <div class="promo-text">@lang('messages.Booking Code')</div>
                                    @endif
                                    @if ($order->discounts > 0)
                                        <div class="promo-text">@lang('messages.Discounts')</div>
                                    @endif
                                    @php
                                        $tpro = json_decode($order->promotion_disc);
                                        $pro_name = json_decode($order->promotion_name);
                                        if (isset($pro_name)) {
                                            $cpro = count($pro_name);
                                        }else {
                                            $cpro = 0;
                                        }
                                        if (isset($tpro)) {
                                            $total_promotion = array_sum($tpro);
                                        }else {
                                            $total_promotion = 0;
                                        }
                                        $promotion_name = "";
                                        for ($i=0; $i < $cpro ; $i++) { 
                                            $promotion_name = $promotion_name.$pro_name[$i];
                                        }
                                    @endphp
                                    @if ($total_promotion > 0)
                                        <div class="promo-text">@lang('messages.Promotion')</div>
                                    @endif
                                    @if ($order->kick_back > 0 or $order->bookingcode_disc > 0 or $order->discounts > 0 or $total_promotion > 0)
                                        <hr class="form-hr">
                                    @endif
                                @endif
                                <div class="price-name">@lang('messages.Total Price')</div>
                            </div>
                            <div class="col-6 col-md-6 text-right">
                                <div class="normal-text-07"><span id="tour_price_per_pax" style="color: black">{{ $order->price_pax }}</span></div>
                                @if ($order->bookingcode_disc > 0 or $order->discounts > 0 or $order->kick_back > 0 or $order->promotion_disc > 0)
                                    <div class="promo-text"><span id="tour-normal-price">{{ number_format($order->normal_price, 0, ",", ".") }}</span></div>
                                    <hr class="form-hr">
                                    @if ($order->kick_back > 0)
                                        <div class="kick-back">{{ "- $ ".number_format($order->kick_back, 0, ",", ".") }}</div>
                                    @endif
                                    @if ($order->bookingcode_disc > 0)
                                        <div class="kick-back">{{ "- $ ".number_format($order->bookingcode_disc, 0, ",", ".") }}</div>
                                    @endif

                                    @if ($order->discounts > 0)
                                        <div class="kick-back">{{ "- $ ".number_format($order->discounts, 0, ",", ".") }}</div>
                                    @endif
                                    @if ($total_promotion > 0)
                                        <div class="kick-back">{{ "- $ ".number_format($total_promotion, 0, ",", ".") }}</div>
                                    @endif
                                
                                    @if ($order->kick_back > 0 or $order->bookingcode_disc > 0 or $order->discounts > 0 or $total_promotion > 0)
                                        <hr class="form-hr">
                                    @endif
                                @endif
                                <div class="price-tag"><span id="tour_final_price">{{ number_format($order->final_price, 0, ",", ".") }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="notif-modal text-left">
                        @if ($order->status == "Draft")
                            @if (Auth::user()->email == "" or Auth::user()->phone == "" or Auth::user()->office == "" or Auth::user()->address == "" or Auth::user()->country == "")
                                @lang('messages.Please complete your profile data first to be able to submit orders, by clicking this link') -> <a href="/profile">@lang('messages.Edit Profile')</a>
                            @else
                                @if ($order->status == "Invalid")
                                    @lang('messages.This order is invalid, please make sure all data is correct!')
                                @else
                                    @lang('messages.Please make sure all the data is correct before you submit the order!')
                                @endif
                            @endif
                        @elseif ($order->status == "Pending")
                            @lang('messages.We have received your order, we will contact you as soon as possible to validate the order!')
                        @elseif ($order->status == "Rejected")
                            {{ $order->msg }}
                        @elseif ($order->status == "Invalid")
                            {{ $order->msg }}
                        @endif
                    </div>
                </div>
            </div>
            <input type="hidden" name="service" value="Tour Package">
            <input type="hidden" name="status" value="Pending">
            <input type="hidden" name="page" value="submit-tour-order">
            <input type="hidden" name="action" value="Submit Order">
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" name="orderno" value="{{ $order->orderno }}">
            <input type="hidden" name="author" value="{{ Auth::user()->id }}">
            <input type="hidden" name="name" value="{{ Auth::user()->name }}">
            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
            <input type="hidden" id="bk_disc" name="bk_disc" value="{{ $order->bookingcode_disc }}">
            <input type="hidden" id="promo_disc" name="promotion_disc" value="{{ $order->promotion_disc }}">
            <input type="hidden" id="price_per_pax" name="price_pax" value="{{ $order->price_pax }}">
            <input type="hidden" id="val-tour-normal-price" name="normal_price" value="{{ $order->normal_price }}">
            <input type="hidden" id="hprice_total" name="price_total" value="{{ $order->price_total }}">
            <input type="hidden" id="htour_final_price" name="final_price" value="{{ $order->final_price }}">
            @foreach ($tour_prices as $tppp=>$tour_price_var)
                @php
                    $price_max_qty = $tour_price_var->max_qty;
                    $cr_pax = ceil($tour_price_var->contract_rate/$usdrates->rate)+$tour_price_var->markup;
                    $cr_pax_tax = ceil($cr_pax * ($taxes->tax/100));
                    $tour_price_pax = $cr_pax + $cr_pax_tax;
                @endphp
                <input type="hidden" id="qty_{{ $tppp }}" name="qty" value="{{ $tour_price_var->max_qty }}">
                <input type="hidden" id="price_max_qty_{{ $tppp }}" name="price_pax_qty" value="{{ $price_max_qty }}">
                <input type="hidden" id="tour_price_pax_{{ $tppp }}" name="tour_price_pax" value="{{ $tour_price_pax }}">
            @endforeach

        </Form>
        <div class="card-box-footer">
            @if ($order->status == "Draft")
                <div class="form-group">
                    @if ($order->status != "Invalid")
                        @if (Auth::user()->email == "" or Auth::user()->phone == "" or Auth::user()->office == "" or Auth::user()->address == "" or Auth::user()->country == "")
                            <button type="button" class="btn btn-light"><i class="icon-copy fa fa-info" aria-hidden="true"> </i> @lang('messages.You cannot submit this order')</button>
                        @else
                            <button type="submit" form="edit-order" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> @lang('messages.Submit')</button>
                        @endif
                    @endif
                    <a href="/orders">
                        <button class="btn btn-danger"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Cancel')</button>
                    </a>
                </div>
            @elseif ($order->status == "Rejected")
                <form id="removeOrder" class="hidden" action="/fremove-order/{{ $order->id }}"method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <input type="hidden" name="status" value="Removed">
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                </form>
                <button type="submit" form="removeOrder" class="btn btn-danger"><i class="icon-copy fa fa-trash-o" aria-hidden="true"></i> @lang('messages.Delete')</button>
            @else
                <div class="form-group">
                    <a href="/orders">
                        <button type="button" class="btn btn-danger"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
                    </a>
                </div>
            @endif
        </div>
    </div>
    <div class="loading-icon hidden pre-loader">
        <div class="pre-loader-box">
            <div class="sys-loader-logo w3-center"> <img class="w3-spin" src="{{ asset('storage/icon/spinner.png') }}" alt="Bali Kami Tour Logo"></div>
            <div class="loading-text">
                Submitting an Order...
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
      $("#edit-order").submit(function() {
        $(".result").text("");
        $(".loading-icon").removeClass("hidden");
        $(".submit").attr("disabled", true);
        $(".btn-txt").text("Processing ...");
      });
    });
</script>
<script>
    function calculate(){
        var nog = document.getElementById('nog').value;
        var bookingcode_disc = document.getElementById('bk_disc').value;
        var promotion_disc = document.getElementById('promo_disc').value;
        var price_pax_0 = document.getElementById('tour_price_pax_0').value;
        var price_pax_1 = document.getElementById('tour_price_pax_1').value;
        var price_pax_2 = document.getElementById('tour_price_pax_2').value;
        var qty_0 = document.getElementById('qty_0').value;
        var qty_1 = document.getElementById('qty_1').value;
        var qty_2 = document.getElementById('qty_2').value;
        var tp = 0;
        var ppp = 0;
        var t_price = 0;
        var normal_price = 0;
        if (nog < 5 && qty_0 < 5) {
            tp = (nog * price_pax_0);
            ppp = price_pax_0;
        } else if(nog < 10 && qty_0 < 10) {
            tp = (nog * price_pax_1);
            ppp = price_pax_1;
        } else if(nog < 17 && qty_0 < 17) {
            tp = (nog * price_pax_2);
            ppp = price_pax_2;
        } else{
            tp = (nog * price_pax_2);
            ppp = price_pax_2;
        } 

        if (bookingcode_disc > 0) {
            t_price = tp - bookingcode_disc;
            normal_price = tp;
        }else{
            t_price = tp;
            normal_price = tp;
        }

        if (promotion_disc > 0) {
            tot_price = t_price - promotion_disc;
            normal_price = tp;
        }else{
            tot_price = t_price;
            normal_price = tp;
        }

        var normalprice = normal_price.toLocaleString('en-US');
        var total_price = tot_price.toLocaleString('en-US');
       
        document.getElementById("tour_price_per_pax").innerHTML = ppp;
        document.getElementById("price_per_pax").value = ppp;
        document.getElementById("tour_final_price").innerHTML = total_price;
        document.getElementById("htour_final_price").value = total_price;
        document.getElementById("tour-normal-price").innerHTML = normalprice;
        document.getElementById("val-tour-normal-price").value = normalprice;
    }
</script>