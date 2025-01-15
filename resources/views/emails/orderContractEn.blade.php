@php
    $r=1;
    $i=1;
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation {{ $order->orderno }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
        body{
            padding:0;
            margin: 0;
            /* font-family: sans-serif; */
            font-family: "notosans" !important;
            letter-spacing: 1px;
            line-height: 1 !important;
            /* font-family: "chinese-font"; */
        }
        p{
            font-size: 0.8rem !important;
        }
        .m-b-18{
            margin-bottom: 18px;
        }
        .m-t-18{
            margin-top: 18px;
        }
        td{
            vertical-align: text-top !important;
        }
        .card-box{
            padding: 18px;
        }
        .heading{
            width: 100%;
            display: inline-flex;
        }
        .heading .heading-right .contract-date{
            background-color: #bb0000;
            text-align-last: right;
            font-size: 1rem;
            padding: 8px 18px;
        }
        .heading .heading-left,.heading-right{
            align-self: self-end;
        }
        .contract-date{
            background-color: #bb0000;
            text-align-last: right;
            font-size: 1rem;
            font-weight: 600;
            padding: 8px 18px;
            color: white;
        }
        .card-box .heading .title{
            width: 100%;
            display: grid;
            text-align: end;
            font-size: 2.7rem;
            font-family: "notosans" !important;
            font-weight: 700;
            text-transform: capitalize;
            place-content: flex-end;
            color: #0033cc;
        }
        .bank-account{
            border: 1px solid #858585;
            padding: 8px 8px 0 8px;
            border-radius: 4px;
            font-size: 0.7rem;
        }
        .business-name{
            font-size: 1.8rem;
            font-weight: 600;
        }
        .business-sub{
            font-style: italic;
            font-size: 1rem;
        }
        .table-container{
            display: flex;
        }
        table {
            margin: 0 0 8px 0;
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .tb-container{
            display: -webkit-inline-box;
            width: 100%
        }
        .tb-list{
            width: 100%;
            height: fit-content;
        }
        table .tb-heading{
            background-color: #bb0000;
            font-size: 1rem;
            font-weight: 600;
            padding: 8px 18px;
            color: white;
        }
        .tb-heading td{
            padding: 8px 18px;
        }
        .tb-head tr{
            line-height: 0.9;
        }
        
        .table-list td{
            padding: 1px 8px;
            border: none;
            vertical-align: text-top;
        }

        .table-order th {
            border:1px solid #4d4d4d;
            background-color: #696969;
            text-align: left;
            padding: 4px 8px;
            color: white;
        }
        .table-order p{
            margin: 0 !important;
            font-size: 0.7rem !important;
        }
        .table-order td {
            padding: 2px 8px;
            border: 1px solid #4d4d4d;
            text-align: left;
            vertical-align: text-top;
        }

        .table-order tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn-print{
            position: fixed;
            right: 0;
            bottom: 0;
            background-color: white;
            width: 100%;
            padding: 18px;
            text-align: right;
        }
        .btn-print button{
            padding: 8px 47px;
            border-radius: 8px;
            background-color: #818181;
            color: #020202;
            font-size: 1.2rem;
            box-shadow: 2px 6px 5px 0px #414040;
            
            cursor: pointer;
        }
        .btn-print button:hover{
            padding: 8px 47px;
            border-radius: 8px;
            background-color: #0000c1;
            color: white;
            font-size: 1.2rem;
            box-shadow: 1px 0px 0px 0px #414040;
        }
        .content{
            font-size: 0.7rem;
            padding: 0 0 8px 0;
        }
        .content .notification-text{
            font-style: italic;
        }
        .subtitle{
            font-size: 0.9rem;
            font-family: "notosans" !important;
            padding-bottom: 8px;
            /* padding-top: 15px; */
        }
        .final-price{
            font-family: "notosans" !important;
            font-size: 0.9rem;
        }
        .additional-info p{
            line-height: 1.3;
        }
        .guest-container{
            display: flex;
            gap: 27px;
            flex-wrap: wrap;
        }

        .guest-list span{
            padding-right: 20px;
        }
        
        @media print {
            .hide-print {
                display: none;
            }
        }
        @media (max-width: 579px) {
            .guest-container{
                gap: 8px
            }
        }
    </style>
</head>
    <body>
        {{-- CONTRACT EN --}}
        <div class="card-box" style="page-break-after: always;">
            <div class="heading">
                <table class="table">
                    <tr>
                        <td> 
                            <div class="heading-left">
                                <div class="business-name">{{ $business->name }}</div>
                                <div class="business-sub">{{ $business->caption }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="heading-right" style="text-align: right;">
                                <div class="title">CONTRACT</div>
                            </div>
                        </td>
                    </tr>
                    <tr class="tb-heading" style="width: 100%">
                        <td>
                        </td>
                        <td>
                            <div class="order-date" style="text-align: right">{{ dateFormat($order->updated_at) }}</div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="content">
                <table class="table tb-head">
                    <tr>
                        <td style="width: 20%;">
                            Reservation No.
                        </td>
                        <td style="width: 30%;">
                            {{ $reservation->rsv_no }}
                        </td>
                        @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package")
                            <td style="width: 20%;">
                                Arrival Flight
                            </td>
                            <td style="width: 30%;">
                                @if (isset($order->arrival_flight))
                                    {{ $order->arrival_flight }}
                                @else
                                    ..........................
                                @endif
                            </td>
                        @else
                            <td style="width: 20%;">
                                Pick Up Date
                            </td>
                            <td style="width: 30%;">
                                @if (isset($order->pickup_date))
                                {{ dateTimeFormat($order->pickup_date) }}
                                @else
                                    ..........................
                                @endif
                            </td>
                        @endif
                    </tr>
                    <tr>
                        <td style="width: 20%;">
                            Reservation Date
                        </td>
                        <td style="width: 30%;">
                            {{ dateFormat($reservation->created_at) }}
                        </td>
                        @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package")
                            <td style="width: 20%;">
                                Arrival Time
                            </td>
                            <td style="width: 30%;">
                                @if ($order->arrival_time)
                                    {{ dateFormat($order->arrival_time) }}
                                @else
                                    ..........................
                                @endif
                            </td>
                        @else
                            <td style="width: 20%;">
                            Pick Up Location
                            </td>
                            <td style="width: 30%;">
                                @if (isset($order->pickup_location))
                                    {{ $order->pickup_location }}
                                @else
                                    ..........................
                                @endif
                            </td>
                        @endif
                    </tr>
                    <tr>
                        <td style="width: 20%;">
                            Pickup Name
                        </td>
                        <td style="width: 30%;">
                            @if (isset($pickup_people))
                                {{ $pickup_people->name }}
                            @else
                                ..........................
                            @endif
                        </td>
                        @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package")
                            <td style="width: 20%;">
                                Departure Flight
                            </td>
                            <td style="width: 30%;">
                                @if (isset($order->departure_flight))
                                    {{ $order->departure_flight }}
                                @else
                                    ..........................
                                @endif
                            </td>
                        @else
                            <td style="width: 20%;">
                                Drop Off Date
                            </td>
                            <td style="width: 30%;">
                                @if (isset($order->dropoff_date))
                                {{ dateTimeFormat($order->dropoff_date) }}
                                @else
                                    ..........................
                                @endif
                            </td>
                        @endif
                    </tr>
                    <tr>
                        <td style="width: 20%;">
                            Pickup Phone
                        </td>
                        <td style="width: 30%;">
                            @if (isset($pickup_people))
                                {{ $pickup_people->phone }}
                            @else
                                ..........................
                            @endif
                        </td>
                        @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package")
                            <td style="width: 20%;">
                                Departure Time
                            </td>
                            <td style="width: 30%;">
                                @if ($order->departure_time)
                                    {{ dateFormat($order->departure_time) }}
                                @else
                                    ..........................
                                @endif
                            </td>
                        @else
                            <td style="width: 20%;">
                                Drop Off Location
                            </td>
                            <td style="width: 30%;">
                                @if (isset($order->dropoff_location))
                                    {{ $order->dropoff_location }}
                                @else
                                    ..........................
                                @endif
                            </td>
                        @endif
                    </tr>
                    <tr>
                        <td style="width: 20%;">
                            Sales Agent
                        </td>
                        <td style="width: 30%;">
                            {{ $agent->name }}
                        </td>
                        @if (isset($guide->name))
                            <td style="width: 20%;">
                                Guide Name
                            </td>
                            <td style="width: 30%;">
                                {{ $guide->name }}
                            </td>
                        @endif
                        
                    </tr>
                    <tr>
                        <td style="width: 20%;">
                            Office
                        </td>
                        <td style="width: 30%;">
                            {{ $agent->office }}
                        </td>
                        @if (isset($guide->phone))
                            <td style="width: 20%;">
                                Guide Phone
                            </td>
                            <td style="width: 30%;">
                                {{ $guide->phone }}
                            </td>
                        @endif
                    </tr>
                    @if (isset($driver->name))
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="width: 20%;">
                                Driver Name
                            </td>
                            <td style="width: 30%;">
                                {{ $driver->name }}
                            </td>
                        </tr>
                    @endif
                    @if (isset($driver->phone))
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="width: 20%;">
                                Driver Phone
                            </td>
                            <td style="width: 30%;">
                                {{ $driver->phone }}
                            </td>
                        </tr>
                    @endif
                </table>
                <hr class="hr-line">
                {{-- GUEST --}}
                <div class="subtitle"><b>Guest Detail</b></div>
                @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package")
                    <div class="guest-container">
                        @if (count($guest_name)>0)
                            @php
                                $x = count($guest_name);
                            @endphp
                            <div class="guest-list">
                                @for ($k = 0; $k < $x; $k++)
                                    @php
                                        $nr = $k + 1;
                                    @endphp
                                    <span>{!! $nr.". ". $guest_name[$k]->name." ". $guest_name[$k]->name_mandarin !!} </span>
                                @endfor
                            </div>
                        @else
                            @php
                                $guest_detail = json_decode($order->guest_detail);
                                if (isset($guest_detail)) {
                                    $cgdt = count($guest_detail);
                                    $ng = 1;
                                }else{
                                    $cgdt = 0;
                                    $ng = 1;
                                }
                            @endphp
                            @if ($cgdt>0)
                            <div class="guest-list">
                                @for ($g = 0; $g < $cgdt; $g++)
                                <span> {!! $ng++.". ". $guest_detail[$g] !!}</span>
                                @endfor
                            </div>
                            @endif
                        @endif
                    </div>
                @else
                    <div class="normal-text">{!! $order->guest_detail !!}</div>
                @endif
                {{-- ORDER DETAIL --}}
                <table class="table-order m-t-18">
                    <tr>
                        @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package")
                            <th colspan="2">Accomodation</th>
                        @elseif($order->service == "Tour Package")
                            <th colspan="2">Tour Package</th>
                        @elseif($order->service == "Activity")
                            <th colspan="2">Activity</th>
                        @elseif($order->service == "Transport")
                            <th colspan="2">Transport</th>
                        @else
                            <th colspan="2">Service</th>
                        @endif
                    </tr>
                    @if ($order->service == "Tour Package")
                        <tr>
                            <td style="width: 30%">
                                Order No.
                            </td>
                            <td style="width: 70%">
                                <b>{{ $order->orderno }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                            Confirmation No.
                            </td>
                            <td style="width: 70%">
                                @if (isset($order->confirmation_order))
                                    <b>{{ $order->confirmation_order }}</b>
                                @else
                                    <b>-</b>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Tour Package
                            </td>
                            <td style="width: 70%">
                                {{ $order->subservice }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Number of Guests
                            </td>
                            <td style="width: 70%">
                                {{ $order->number_of_guests." guests" }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Tour Start
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkin) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Tour End
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkout) }}
                            </td>
                        </tr>
                    @elseif ($order->service == "Hotel")
                        <tr>
                            <td style="width: 30%">
                                Order No.
                            </td>
                            <td style="width: 70%">
                                <b>{{ $order->orderno }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                            Confirmation No.
                            </td>
                            <td style="width: 70%">
                                @if (isset($order->confirmation_order))
                                    <b>{{ $order->confirmation_order }}</b>
                                @else
                                    <b>-</b>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Hotel Name
                            </td>
                            <td style="width: 70%">
                                {{ $order->servicename }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Suites & Villa
                            </td>
                            <td style="width: 70%">
                                {{ $order->subservice }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Check in
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkin) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Check out
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkout) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Duration
                            </td>
                            <td style="width: 70%">
                                {{ $order->duration." Night" }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Number of Guests
                            </td>
                            <td style="width: 70%">
                                {{ $order->number_of_guests." Guests" }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Number of Room
                            </td>
                            <td style="width: 70%">
                                {{ $order->number_of_room." Unit" }}
                            </td>
                        </tr>
                        @if ($jml_extra_bed > 0)
                            <tr>
                                <td style="width: 30%">
                                    Extra Bed
                                </td>
                                <td style="width: 70%">
                                    {{ $jml_extra_bed." Unit" }}
                                </td>
                            </tr>
                        @endif
                    @elseif ($order->service == "Hotel Promo")
                        <tr>
                            <td style="width: 30%">
                                Order No.
                            </td>
                            <td style="width: 70%">
                                <b>{{ $order->orderno }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                            Confirmation No.
                            </td>
                            <td style="width: 70%">
                                @if (isset($order->confirmation_order))
                                    <b>{{ $order->confirmation_order }}</b>
                                @else
                                    <b>-</b>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Service
                            </td>
                            <td style="width: 70%">
                                {{ $order->service }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Hotel Name
                            </td>
                            <td style="width: 70%">
                                {{ $order->servicename }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Suites & Villa
                            </td>
                            <td style="width: 70%">
                                {{ $order->subservice }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Check in
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkin) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Check out
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkout) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Duration
                            </td>
                            <td style="width: 70%">
                                {{ $order->duration." Night" }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Number of Guests
                            </td>
                            <td style="width: 70%">
                                {{ $order->number_of_guests." Guests" }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Number of Room
                            </td>
                            <td style="width: 70%">
                                {{ $order->number_of_room." Unit" }}
                            </td>
                        </tr>
                        @if ($jml_extra_bed > 0)
                            <tr>
                                <td style="width: 30%">
                                    Extra Bed
                                </td>
                                <td style="width: 70%">
                                    {{ $jml_extra_bed." Unit" }}
                                </td>
                            </tr>
                        @endif
                    @elseif ($order->service == "Hotel Package")
                        <tr>
                            <td style="width: 30%">
                                Order No.
                            </td>
                            <td style="width: 70%">
                                <b>{{ $order->orderno }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                            Confirmation No.
                            </td>
                            <td style="width: 70%">
                                @if (isset($order->confirmation_order))
                                    <b>{{ $order->confirmation_order }}</b>
                                @else
                                    <b>-</b>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Service
                            </td>
                            <td style="width: 70%">
                                {{ $order->service }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Hotel Name
                            </td>
                            <td style="width: 70%">
                                {{ $order->servicename }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Suites & Villa
                            </td>
                            <td style="width: 70%">
                                {{ $order->subservice }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Check in
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkin) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Check out
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkout) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Duration
                            </td>
                            <td style="width: 70%">
                                {{ $order->duration." Night" }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Number of Guests
                            </td>
                            <td style="width: 70%">
                                {{ $order->number_of_guests." Guests" }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Number of Room
                            </td>
                            <td style="width: 70%">
                                {{ $order->number_of_room." Unit" }}
                            </td>
                        </tr>
                        @if ($jml_extra_bed > 0)
                            <tr>
                                <td style="width: 30%">
                                    Extra Bed
                                </td>
                                <td style="width: 70%">
                                    {{ $jml_extra_bed." Unit" }}
                                </td>
                            </tr>
                        @endif
                    @elseif ($order->service == "Activity")
                        <tr>
                            <td style="width: 30%">
                                Order No.
                            </td>
                            <td style="width: 70%">
                                <b>{{ $order->orderno }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                            Confirmation No.
                            </td>
                            <td style="width: 70%">
                                @if (isset($order->confirmation_order))
                                    <b>{{ $order->confirmation_order }}</b>
                                @else
                                    <b>-</b>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Activity
                            </td>
                            <td style="width: 70%">
                                {{ $order->servicename }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Activity Date
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkin) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Number of Guests
                            </td>
                            <td style="width: 70%">
                                {{ $order->number_of_guests." Guests" }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Guests Name
                            </td>
                            <td style="width: 70%">
                                {{ $order->guest_detail }}
                            </td>
                        </tr>
                    @elseif ($order->service == "Transport")
                        <tr>
                            <td style="width: 30%">
                                Order No.
                            </td>
                            <td style="width: 70%">
                                <b>{{ $order->orderno }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                            Confirmation No.
                            </td>
                            <td style="width: 70%">
                                @if (isset($order->confirmation_order))
                                    <b>{{ $order->confirmation_order }}</b>
                                @else
                                    <b>-</b>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Transport
                            </td>
                            <td style="width: 70%">
                                {{ $order->servicename }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Capacity
                            </td>
                            <td style="width: 70%">
                                {{ $order->capacity." Seat" }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Number of Guests
                            </td>
                            <td style="width: 70%">
                                {{ $order->number_of_guests." Guests" }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                In
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkin) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Out
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkout) }}
                            </td>
                        </tr>
                    @elseif ($order->service == "Wedding Package")
                        <tr>
                            <td style="width: 30%">
                                Wedding Package
                            </td>
                            <td style="width: 70%">
                                {{ $order->servicename }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Arrival
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkin) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Departure
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->checkout) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                Wedding date
                            </td>
                            <td style="width: 70%">
                                {{ dateFormat($order->travel_date) }}
                            </td>
                        </tr>
                    @else
                        Subservice: -<br>
                    @endif
                </table>
                {{-- Additional Service --}}
                @if(isset($optional_rate_orders) or isset($order->additional_service))
                    <table class="table-order">
                        @if(isset($optional_rate_orders))
                            @if ($optional_rate_orders != "null")
                                @php
                                    $optional_rate_orders_id = json_decode($optional_rate_orders->optional_rate_id);
                                    $optional_rate_orders_nog = json_decode($optional_rate_orders->number_of_guest);
                                    $optional_rate_orders_sd = json_decode($optional_rate_orders->service_date);
                                    $optional_rate_orders_pp = json_decode($optional_rate_orders->price_pax);
                                    $optional_rate_orders_pt = json_decode($optional_rate_orders->price_total);
                                    $elo_optional_rate_order_nog = is_array($optional_rate_orders_nog);
                                    if ($elo_optional_rate_order_nog == 1) {
                                        if (isset($optional_rate_orders_nog)) {
                                            $xsor = count($optional_rate_orders_nog);
                                        }else{
                                            $xsor = 0;
                                            $optional_service_total_price = 0;
                                        }
                                    }else{
                                        $xsor = 0;
                                        $optional_service_total_price = 0;
                                    }
                                @endphp
                                <tr>
                                    <th colspan="4">Additional Charge</th>
                                </tr>
                                @for ($f = 0; $f < $xsor; $f++)
                                    @php
                                        $additional_charge = $optionalrates->where('id', $optional_rate_orders_id[$f])->first();
                                        $hotel = $hotels->where('id', $additional_charge->hotels_id)->first();
                                    @endphp
                                    <tr>
                                        <td style="width:20%">{{ dateFormat($optional_rate_orders_sd[$f]) }}</td>
                                        <td style="width:40%">{!! $additional_charge->name !!}</td>
                                        <td style="width:20%">{!! $optional_rate_orders_nog[$f]." pax" !!}</td>
                                        <td style="width:20%">{!! $hotel->name !!}</td>
                                    </tr>
                                @endfor
                            @endif
                        @endif
                    </table>
                    <table class="table-order">
                        @if(isset($order->additional_service))
                            @if ($order->additional_service != "null")
                                @php
                                    $additional_service = json_decode($order->additional_service);
                                    $additional_service_date = json_decode($order->additional_service_date);
                                    $additional_service_qty = json_decode($order->additional_service_qty);
                                    $additional_service_price = json_decode($order->additional_service_price);

                                    $elo_asd = is_array($additional_service);
                                    if ($elo_asd == 1) {
                                        
                                        if (isset($additional_service)) {
                                            $c_adser = count($additional_service);
                                        }else{
                                            $c_adser = 0;
                                        }
                                    }else{
                                        $c_adser = 0;
                                    }
                                @endphp
                                <tr style="margin-top: 8px">
                                    <th colspan="4">Additional Service</th>
                                </tr>
                                @for ($adser = 0; $adser < $c_adser; $adser++)
                                    <tr>
                                        <td style="width:20%">{{ dateFormat($additional_service_date[$adser]) }}</td>
                                        <td style="width:40%">{!! $additional_service[$adser] !!}</td>
                                        <td style="width:20%">{!! $additional_service_qty[$adser]." pax" !!}</td>
                                        <td style="width:20%">-</td>
                                    </tr>
                                @endfor
                            @endif
                        @endif
                    </table>
                @endif
                @if (isset($airport_shuttles))
                    
                        <table class="table-order">
                            <tr>
                                <th colspan="4">Transport</th>
                            </tr>
                            @foreach ($airport_shuttles as $airport_shuttle)
                                <tr>
                                    <td style="width:20%">{{ dateFormat($airport_shuttle->date) }}</td>
                                    <td style="width:30%">@lang('messages.Airport Shuttle'), {{ $airport_shuttle->transport.", ".$airport_shuttle->duration." hours, ".$airport_shuttle->distance."KM" }}</td>
                                    <td style="width:20%">1 Unit</td>
                                    <td style="width:30%">{{ $airport_shuttle->src." - ".$airport_shuttle->dst }}</td>
                                </tr>
                            @endforeach
                        </table>
                    
                @endif
                {{-- ADDITIONAL INFORMATION --}}
                <table class="table-order">
                    <tr>
                        <th colspan="2">Additional Information</th>
                    </tr>
                    @if (isset($order->benefits))
                        <tr>
                            <td style="width: 20%">Benefits</td>
                            <td style="width: 80%">{!! $order->benefits !!}</td>
                        </tr>
                    @endif
                    @if (isset($order->destinations))
                        <tr>
                            <td style="width: 20%">Destination</td>
                            <td style="width: 80%">{!! $order->destinations !!}</td>
                        </tr>
                    @endif
                    @if (isset($order->itinerary))
                        <tr>
                            <td style="width: 20%">Itinerary</td>
                            <td style="width: 80%">{!! $order->itinerary !!}</td>
                        </tr>
                    @endif
                    @if (isset($order->include))
                        <tr>
                            <td style="width: 20%">Include</td>
                            <td style="width: 80%">{!! $order->include !!}</td>
                        </tr>
                    @endif
                    @if (isset($order->include))
                        <tr>
                            <td style="width: 20%">Information</td>
                            <td style="width: 80%">{!! $order->additional_info !!}</td>
                        </tr>
                    @endif
                </table>
                @if (isset($order->note))
                    <table class="table-order">
                        <tr>
                            <th style="max-width: 100%">Remark</th>
                        </tr>
                        <tr>
                            <td>{!! $order->note !!}</td>
                        </tr>
                    </table>
                @endif
                <div class="notification-text">
                    Thank you for your support of {{ $business->name }}! If you have any questions or need further assistance, please feel free to contact our customer service at email: reservation@balikamitour.com or phone: {{ $business->phone }}
                </div>
            </div>
        </div>
        {{-- INVOICE EN --}}
        @if (isset($invoice))
            <div class="card-box">
                <div class="heading">
                    <table class="table">
                        <tr>
                            <td> 
                                <div class="heading-left">
                                    <div class="business-name">{{ $business->name }}</div>
                                    <div class="business-sub">{{ $business->caption }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="heading-right" style="text-align: right;">
                                    <div class="title">INVOICE</div>
                                </div>
                            </td>
                        </tr>
                        <tr class="tb-heading" style="width: 100%">
                            <td>
                            
                            </td>
                            <td>
                                <div class="order-date" style="text-align: right">{{ dateFormat($order->updated_at) }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="content">
                    <table class="table tb-head">
                        <tr>
                            <td style="width: 20%;">
                                Invoice No.
                            </td>
                            <td style="width: 30%;">
                                {{ $invoice->inv_no }}
                            </td>
                            <td style="width: 20%;">
                                Customer Name
                            </td>
                            <td style="width: 30%;">
                                    @php
                                        $guestName = $guest_name->where('id',$order->pickup_name)->first();
                                    @endphp
                                @if (isset($guestName))
                                    {{ $guestName->name }}
                                @else
                                    : - 
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20%;">
                                Reservation No.
                            </td>
                            <td style="width: 30%;">
                                {{  $reservation->rsv_no }}
                            </td>
                            <td style="width: 20%;">
                            Customer Phone
                            </td>
                            <td style="width: 30%;">
                                @if (isset($guestName->phone))
                                    {{ $guestName->phone }}
                                @else
                                    : - 
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20%;">
                                Order No.
                            </td>
                            <td style="width: 30%;">
                                {{  $order->orderno }}
                            </td>
                            <td style="width: 20%;">
                                Sales Agent
                            </td>
                            <td  style="width: 30%;">
                                {{  $agent->name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20%;">
                                Invoice Date
                            </td>
                            <td style="width: 30%;">
                                {{ dateFormat($invoice->inv_date) }}
                            </td>
                            <td style="width: 20%;">
                                Company / Office
                            </td>
                            <td  style="width: 30%;">
                                {{ $agent->office }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20%;">
                                {{-- Reconfirm Date --}}
                                Payment Dateline
                            </td>
                            <td  style="width: 30%;">
                                {{ dateFormat($invoice->due_date) }}
                            </td>
                            <td style="width: 20%;">
                                Contact Number
                            </td>
                            <td  style="width: 30%;">
                                {{ $agent->phone }}
                            </td>
                        </tr>
                    </table>
                    
                    <table class="table-order" >
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 40%;">Description</th>
                                <th style="width: 15%;">Rate USD</th>
                                <th style="width: 10%;">Unit/Pax</th>
                                <th style="width: 10%;">Night/Times</th>
                                <th style="width: 20%;">Amount</th>
                            </tr>
                            <tr>
                                <td><div class="table-service-name">{{ $i++ }}</div></td>
                                <td>
                                    <div class="table-service-name">
                                        @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package" )
                                            {{ "Date ". date('m/d',strtotime($order->checkin))." - ".date('m/d',strtotime($order->checkout)).", ".$order->servicename }}
                                        @elseif($order->service == "Transport")
                                            {{ "Date ". date('m/d',strtotime($order->checkin))." - ".date('m/d',strtotime($order->checkout)).", ".$order->servicename."(".$order->capacity." seat), ".$order->service_type.", ".$order->dst." to ".$order->src }}
                                        @else
                                            {{ "Date ". date('m/d',strtotime($order->checkin))." - ".date('m/d',strtotime($order->checkout)).", ".$order->subservice }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    {{ "$ ". number_format($order->price_pax, 0, ".", ",") }}
                                </td>
                                <td>
                                    @if ($order->service == "Tour Package")
                                        {{ $order->number_of_guests }}
                                    @elseif($order->service == "Activity")
                                        {{ $order->number_of_guests }}
                                    @elseif($order->service == "Transport")
                                        1
                                    @else
                                        {{ $order->number_of_room }}
                                    @endif
                                </td>
                                <td>
                                    @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package" or $order->service == "Tour Package" )
                                        {{ $order_duration }}
                                    @else
                                        1
                                    @endif
                                    
                                </td>
                                <td>
                                    @if ($order->service == "Hotel" or $order->service == "Hotel Promo" or $order->service == "Hotel Package" or $order->service == "Tour Package" )
                                        {{ "$ ". number_format($amount, 0, ".", ",") }}
                                    @else
                                        {{ "$ ". number_format($order->price_total, 0, ".", ",") }}
                                    @endif
                                </td>
                            </tr>
                            {{-- EXTRA BED --}}
                            @if ($jml_extra_bed > 0)
                                <tr>
                                    <td><div class="table-service-name">{{ $i++ }}</div></td>
                                    <td>
                                        <div class="table-service-name">
                                            {{"Date ". date('m/d',strtotime($order->checkin))." - ".date('m/d',strtotime($order->checkout)).", Extra Bed" }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ "$ ". number_format(((($extrabed_price/$jml_extra_bed)/$jml_extra_bed)/$order->duration), 0, ".", ",") }}
                                    </td>
                                    <td>
                                        {{ $jml_extra_bed }}
                                    </td>
                                    <td>
                                        {{ $order->duration }}
                                    </td>
                                    <td>
                                        {{ "$ ". number_format($extrabed_price/$jml_extra_bed, 0, ".", ",") }}
                                    </td>
                                </tr>
                            @endif
                            {{-- ADDITIONAL CHARGE --}}
                            @if (isset($opsirate_order_date))
                                @if (is_array($opsirate_order_date) == 1)
                                    @php
                                        $s = count($opsirate_order_date);
                                    @endphp
                                    @for ($o = 0; $o < $s; $o++)
                                        @php
                                            $opsirate = $optionalrates->where('id',$opsirate_order_id[$o])->first();
                                        @endphp
                                        <tr>
                                            <td><div class="table-service-name">{{ $i++ }}</div></td>
                                            <td>
                                                <div class="table-service-name">
                                                    {{"Date ". date('m/d',strtotime($opsirate_order_date[$o])).", ".$opsirate->name }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ "$ ". number_format($opsirate_order_price_pax[$o], 0, ".", ",") }}
                                            </td>
                                            <td>
                                                {{ $opsirate_order_nog[$o] }}
                                            </td>
                                            <td>
                                                1
                                            </td>
                                            <td>
                                                {{ "$ ". number_format($opsirate_order_price_total[$o], 0, ".", ",") }}
                                            </td>
                                        </tr>
                                    @endfor
                                @endif
                            @endif
                            {{-- ADDITIONAL SERVICE --}}
                            @if (isset($order->additional_service))
                                @if ($order->additional_service != "null")
                                    @php
                                        $adser = json_decode($order->additional_service);
                                        $adser_date = json_decode($order->additional_service_date);
                                        $adser_qty = json_decode($order->additional_service_qty);
                                        $adser_price = json_decode($order->additional_service_price);
                                        if (isset($adser)) {
                                            $cad = count($adser);
                                        }else{
                                            $cad = 0;
                                        }
                                        $adser_total_price = 0;
                                    @endphp
                                    @for ($v = 0; $v < $cad; $v++)
                                        @php
                                            $adser_tp = $adser_price[$v]*$adser_qty[$v];
                                            $adser_total_price = $adser_total_price + $adser_tp;
                                        @endphp
                                        <tr>
                                            <td><div class="table-service-name">{{ $i++ }}</div></td>
                                            <td>
                                                <div class="table-service-name">
                                                    {{"Date ". date('m/d',strtotime($adser_date[$v])).", ".$adser[$v] }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ "$ ". number_format($adser_price[$v], 0, ".", ",") }}
                                            </td>
                                            <td>
                                                {{ $adser_qty[$v] }}
                                            </td>
                                            <td>
                                                1
                                            </td>
                                            <td>
                                                {{ "$ ". number_format($adser_price[$v]*$adser_qty[$v], 0, ".", ",") }}
                                            </td>
                                        </tr>
                                    @endfor
                                @else
                                    @php
                                        $adser_total_price = 0;
                                    @endphp
                                @endif
                            @else
                                @php
                                    $adser_total_price = 0;
                                @endphp
                            @endif
                            
                            {{-- AIRPORT SHUTTLE --}}
                            @if (isset($airport_shuttles))
                                @foreach ($airport_shuttles as $inv_airport_shuttle)
                                    <tr>
                                        <td><div class="table-service-name">{{ $i++ }}</div></td>
                                        <td><div class="table-service-name">{{ "Date ".date('m/d',strtotime($inv_airport_shuttle->date)). ", Airport Shuttle, ".$inv_airport_shuttle->src." - ".$inv_airport_shuttle->dst.", ". $inv_airport_shuttle->transport }}</div></td>
                                        <td><div class="table-service-name">{{ "$ ". number_format($inv_airport_shuttle->price, 0, ".", ",") }}</div></td>
                                        <td><div class="table-service-name">1</div></td>
                                        <td><div class="table-service-name">1</div></td>
                                        <td><div class="table-service-name">{{ "$ ". number_format($inv_airport_shuttle->price, 0, ".", ",") }}</div></td>
                                    </tr>
                                @endforeach
                            @endif
                    </table>
                    <table class="table-list m-t-18 m-b-18">
                        <tbody>
                            <tr style="text-align: right;">
                                <td style="width:20%;"></td>
                                <td style="width:20%;"></td>
                                <td style="width:20%;">Services</td>
                                <td style="width: 20%">USD</td>
                                <td style="width: 20%">{{ "$ ". number_format($invoice->total_usd + $order->kick_back + $order->discounts + $order->bookingcode_disc + $promotion_disc, 0, ".", ",") }}</td>
                            </tr>
                            <tr style="text-align: right;">
                                <td style="width:20%;"></td>
                                <td style="width:20%;"></td>
                                <td style="width:20%;">Tax</td>
                                <td >USD</td>
                                <td style="width: 20%">$ 0</td>
                            </tr>
                            @if ($order->kick_back > 0)
                                <tr style="text-align: right;">
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;">Kick Back</td>
                                    <td >USD</td>
                                    <td style="width: 20%">{{ "-$ ". number_format($order->kick_back, 0, ".", ",") }}</td>
                                </tr>
                            @endif
                            @if ($order->bookingcode_disc > 0)
                                <tr style="text-align: right;">
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;">Booking Code</td>
                                    <td>USD</td>
                                    <td style="width: 20%">{{ "-$ ". number_format($order->bookingcode_disc, 0, ".", ",") }}</td>
                                </tr>
                            @endif
                            @if ($order->discounts > 0)
                                <tr style="text-align: right;">
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;">Discounts</td>
                                    <td>USD</td>
                                    <td style="width: 20%">{{ "-$ ". number_format($order->discounts, 0, ".", ",") }}</td>
                                </tr>
                            @endif
                            @if ($promotion_disc > 0)
                                <tr style="text-align: right;">
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;">Promotions</td>
                                    <td>USD</td>
                                    <td style="width: 20%">{{ "-$ ". number_format($promotion_disc, 0, ".", ",") }}</td>
                                </tr>
                            @endif
                            @if($invoice->currency->name == "CNY")
                                <tr style="text-align: right; color:gray !important;">
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;"></td>
                                    <td style="width:20%; border-top: 1px solid grey;" class="final-price"><b>Total</b></td>
                                    <td style="width:20%; border-top: 1px solid grey;" class="final-price"><b>USD</b></td>
                                    <td style="width:20%; border-top: 1px solid grey;" class="final-price"><b>{{ "$ ". number_format($invoice->total_usd, 0, ".", ",") }}</b></td>
                                </tr>
                                <tr style="text-align: right;">
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;" class="final-price"><b>Total</b></td>
                                    <td style="width:20%;" class="final-price"><b>CNY</b></td>
                                    <td style="width: 20%" class="final-price"><b>{{ "¥ ". number_format($invoice->total_cny, 0, ".", ",") }}</b></td>
                                </tr>
                            @elseif($invoice->currency->name == "TWD")
                                <tr style="text-align: right; color:gray !important;">
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;"></td>
                                    <td style="width:20%; border-top: 1px solid grey;" class="final-price"><b>Total</b></td>
                                    <td style="width:20%; border-top: 1px solid grey;" class="final-price"><b>USD</b></td>
                                    <td style="width:20%; border-top: 1px solid grey;" class="final-price"><b>{{ "$ ". number_format($invoice->total_usd, 0, ".", ",") }}</b></td>
                                </tr>
                                <tr style="text-align: right;">
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;" class="final-price"><b>Total</b></td>
                                    <td style="width:20%;" class="final-price"><b>TWD</b></td>
                                    <td style="width: 20%" class="final-price"><b>{{ "$ ". number_format($invoice->total_twd, 0, ".", ",") }}</b></td>
                                </tr>
                            @elseif($invoice->currency->name == "IDR")
                                <tr style="text-align: right; color:gray !important;">
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;"></td>
                                    <td style="width:20%; border-top: 1px solid grey;" class="final-price"><b>Total</b></td>
                                    <td style="width:20%; border-top: 1px solid grey;" class="final-price"><b>USD</b></td>
                                    <td style="width:20%; border-top: 1px solid grey;" class="final-price"><b>{{ "$ ". number_format($invoice->total_usd, 0, ".", ",") }}</b></td>
                                </tr>
                                <tr style="text-align: right;">
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;" class="final-price"><b>Total</b></td>
                                    <td style="width:20%;" class="final-price"><b>IDR</b></td>
                                    <td style="width: 20%" class="final-price"><b>{{ "Rp ". number_format($invoice->total_idr, 0, ".", ",") }}</b></td>
                                </tr>
                            @else
                                <tr style="text-align: right;">
                                    <td style="width:20%;"></td>
                                    <td style="width:20%;"></td>
                                    <td style="width:20%; border-top: 1px solid grey;" class="final-price"><b>Total</b></td>
                                    <td style="width:20%; border-top: 1px solid grey;" class="final-price"><b>USD</b></td>
                                    <td style="width:20%; border-top: 1px solid grey;" class="final-price"><b>{{ "$ ". number_format($invoice->total_usd, 0, ".", ",") }}</b></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @if ($bankAccount)
                        <div class="bank-account">
                            <div class="tb-container">
                                <table class="table tb-list">
                                    <tr>
                                        <td style="width: 15%;">
                                            Bank
                                        </td>
                                        <td style="width: 35%;">
                                            {{ $bankAccount->bank }}
                                        </td>
                                        <td style="width:15%;">
                                            Swift Code
                                        </td>
                                        <td style="width: 35%;">
                                            {{ $bankAccount->swift_code }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15%;">
                                            Name
                                        </td>
                                        <td style="width: 35%;">
                                            {{ $bankAccount->name }}
                                        </td>
                                        <td style="width:15%;">
                                            Telephone
                                        </td>
                                        <td style="width: 35%;">
                                            {{ $bankAccount->telephone }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15%;">
                                            Account IDR
                                        </td>
                                        <td style="width: 35%;">
                                            {{ $bankAccount->account_idr }}
                                        </td>
                                        <td style="width: 15%;">
                                            Address
                                        </td>
                                        <td style="width: 35%;">
                                            {{ $bankAccount->address }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15%;">
                                            Account USD
                                        </td>
                                        <td colspan="2" style="width: 35%;">
                                            {{ $bankAccount->account_usd }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <br>
                                        <i>* The transfer fees and administrative costs will be charged to the sender.</i>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif
                    <div class="additional-info">
                        <br>
                        <p>We would like to remind you to follow the following steps in the payment process:<br>
                        1. After receiving our confirmation letter, please review the details of the reservation in the invoice sent.<br>
                        2. If the invoice received is accurate, kindly proceed with the payment before the 'Payment Dateline' mentioned in the invoice.<br>
                        3. If the payment has been completed, please upload the 'proof of payment' in our system.<br>
                        Thank you for your attention and cooperation.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </body>
</html>