<?php
    use App\Models\Services;
    use App\Models\Promotion;
    use App\Models\Orders;
    use App\Models\OrderWedding;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Input;
    use App\Http\Requests\StoremenuRequest;
    use App\Http\Requests\UpdatemenuRequest;
    // Services =======================================================================================================
    $services_menu = Services::where('status','Active')->get();
    $services_admin = Services::all();
    $now = Carbon::now();
    $left_orders_pending = Orders::where('status','Pending')->get();
    $left_orders_wedding_pending = OrderWedding::where('status','Pending')->get();
    $c_left_orders_pending = count($left_orders_pending);
    $c_left_orders_wedding_pending = count($left_orders_wedding_pending);
    $c_o_pending = $c_left_orders_pending+$c_left_orders_wedding_pending;
    $o_wedding_pending = $c_left_orders_wedding_pending;
    $o_tour_pending = $c_left_orders_pending;

    // PROMOTION
    $promotions = Promotion::where('periode_start','<', $now)
        ->where('periode_end','>',$now)
        ->where('status','Active')->get();
    $logoColor = config('app.logo_img_color');
    $logoWhite = config('app.logo_img_white');
    $logoBlack = config('app.logo_img_black');
?>
<div class="left-side-bar d-print-none">
    <div class="brand-logo">
        <a href="dashboard">
            <img src="{{ asset('storage/logo/'.$logoColor) }}" alt="Logo Bali Kami Tour" class="dark-logo" loading="lazy">
            <img src="{{ asset('storage/logo/'.$logoWhite) }}" alt="Logo Bali Kami Tour" class="light-logo" loading="lazy">
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            @if (count($promotions) > 0)
                <div class="promotion-box">
                    <p>@lang('messages.Active Promotion')</p>
                    @foreach ($promotions as $promotion)
                        <div class="promotion-item">
                            <div class="promotion-description" data-toggle="tooltip" data-placement="top" title="@lang('messages.Ongoing promotion'){{" ". $promotion->name." "}}@lang('messages.and get discounts'){{ " $".$promotion->discounts." " }}@lang('messages.until'){{ " ". dateFormat($promotion->periode_end) }}" >
                                <b>{{ $promotion->name }}</b>
                                <p>{{ dateFormat($promotion->periode_start)." - ".dateFormat($promotion->periode_end) }}</p>
                            </div>
                            <div class="promotion-discounts">
                                {{ "$".$promotion->discounts }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            @if (Auth::user()->status == "Active")
                @if (auth()->user()->is_approved == 1)
                    <ul id="accordion-menu">
                        <li>
                            <a href="dashboard" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-dashboard" aria-hidden="true"></span>@lang('messages.Dashboard')
                            </a>
                        </li>
                        @foreach ($services_menu as $menuitem)
                            <li>
                                <a href="{{ url("$menuitem->nicname") }}" class="dropdown-toggle no-arrow">
                                    <span class="micon">{!! $menuitem->icon !!}</span> @lang("messages.".$menuitem->name)
                                </a>
                            </li>
                        @endforeach
                        {{-- <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle">
                                <span class="micon fi-wrench"></span><span class="mtext">@lang("messages.Tools")</span>
                            </a>
                            <ul class="submenu">
                                <li>
                                    <a href="trip-planner" class="dropdown-toggle no-arrow">
                                        <span class="icon-copy fi-map" aria-hidden="true"></span> @lang('messages.Trip Planner')
                                    </a>
                                </li>
                                <li>
                                    <a href="wedding-planner" class="dropdown-toggle no-arrow">
                                        <span class="icon-copy fi-clipboard-notes" aria-hidden="true"></span> @lang('messages.Wedding Planner')
                                    </a>
                                </li>
                            </ul>
                        </li> --}}
                        @canany(['posDev','posAuthor','posRsv','weddingRsv','weddingSls','weddingAuthor','weddingDvl'])
                            <li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle">
                                    <span class="micon fa fa-th"></span><span class="mtext">@lang("messages.Admin Panel")</span>
                                </a>
                                <ul class="submenu">
                                    @can('posDev')
                                        <li>
                                            <a href="admin-panel">
                                                <span class="micon fa fa-dashboard" aria-hidden="true"></span> @lang("messages.Admin Panel")
                                            </a>
                                        </li>
                                    @endcan
                                    @canany(['posDev','posAuthor','posRsv','weddingRsv','weddingAuthor','weddingSls','weddingDvl'])
                                        <li>
                                            <a href="currency">
                                                <span class="icon-copy fa fa-money" aria-hidden="true"></span> @lang("messages.Currency")
                                            </a>
                                        </li>
                                    @endcanany
                                    @can('posDev')
                                        <li>
                                            <a href="user-manager">
                                                <span class="icon-copy fa fa fa-users" aria-hidden="true"></span> @lang("messages.User Manager")
                                            </a>
                                        </li>
                                        <li>
                                            <a href="term-and-condition">
                                                <span class="icon-copy fa fa fa-certificate" aria-hidden="true"></span> @lang("messages.Term And Condition")
                                            </a>
                                        </li>
                                        <li>
                                            <a href="attentions">
                                                <span class="icon-copy fa fa-exclamation-circle" aria-hidden="true"></span> @lang("messages.Attentions")
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                            @canany(['posDev','posAuthor','posRsv','weddingDvl','weddingSls','weddingAuthor','weddingRsv'])
                                <li class="dropdown">
                                    <a href="javascript:;" class="dropdown-toggle">
                                        <span class="micon icon-copy fa fa-percent"></span><span class="mtext">@lang("messages.Promo")</span>
                                    </a>
                                    <ul class="submenu">
                                        <li>
                                            <a href="promotion">
                                                <i class="fa fa-bullhorn" aria-hidden="true"></i> @lang("messages.Promotion")
                                            </a>
                                        </li>
                                        <li>
                                            <a href="booking-code">
                                                <i class="fa fa-calendar-check-o" aria-hidden="true"></i> @lang("messages.Booking Code")
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcanany
                            @canany(['posDev','posAuthor','posRsv','weddingDvl','weddingSls','weddingAuthor','weddingRsv'])
                                @can('posDev')
                                    <li class="order-count">
                                        <a href="orders-admin" class="dropdown-toggle no-arrow">
                                            <i class="micon icon-copy fa fa-tags" aria-hidden="true"></i> @lang("messages.Orders")
                                            <div class="order-pending-text" data-toggle="tooltip" data-placement="top" title="Pending Orders" >
                                                <p>
                                                    <i class="icon-copy ti-alarm-clock"></i> <span>{{ $c_o_pending }}</span>
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                @endcan
                                @canany(['posAuthor','posRsv'])
                                    <li class="order-count">
                                        <a href="orders-admin" class="dropdown-toggle no-arrow">
                                            <i class="micon icon-copy fa fa-tags" aria-hidden="true"></i> @lang("messages.Orders")
                                            <div class="order-pending-text" data-toggle="tooltip" data-placement="top" title="Pending Orders" >
                                                @if ($o_tour_pending > 0)
                                                    <p>
                                                        <i class="icon-copy ti-alarm-clock"></i> <span>{{ $o_tour_pending }}</span>
                                                    </p>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @endcanany
                                @canany(['weddingDvl','weddingSls','weddingAuthor','weddingRsv'])
                                    <li class="order-count">
                                        <a href="orders-admin" class="dropdown-toggle no-arrow">
                                            <i class="micon icon-copy fa fa-tags" aria-hidden="true"></i> @lang("messages.Orders")
                                            <div class="order-pending-text" data-toggle="tooltip" data-placement="top" title="Pending Orders" >
                                                @if ($o_wedding_pending > 0)
                                                    <p>
                                                        <i class="icon-copy ti-alarm-clock"></i> <span>{{ $o_wedding_pending }}</span>
                                                    </p>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @endcanany
                            @endcanany
                            <li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle">
                                    <span class="micon icon-copy fa fa-handshake-o"></span><span class="mtext">@lang("messages.Provider")</span>
                                </a>
                                <ul class="submenu">
                                    {{-- <li>
                                        <a href="/partners">
                                            <i class="fa fa-handshake-o" aria-hidden="true"></i> @lang("messages.Partners")
                                        </a>
                                    </li> --}}
                                    @canany(['posDev','weddingDvl','weddingSls','weddingAuthor','weddingRsv'])
                                        <li>
                                            <a href="/vendors-admin">
                                                <i class="icon-copy fi-torso-business"></i> Wedding Vendors
                                            </a>
                                        </li>
                                    @endcanany
                                    @canany(['posDev','posAuthor','posRsv'])
                                        <li>
                                            <a href="/guides-admin">
                                                <i class="icon-copy fa fa-user" aria-hidden="true"></i> @lang("messages.Guide")
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/drivers-admin">
                                                <i class="icon-copy fa fa-user-circle-o" aria-hidden="true"></i> @lang("messages.Driver")
                                            </a>
                                        </li>
                                    @endcanany
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle">
                                    <span class="micon dw dw-list3"></span><span class="mtext">@lang("messages.Services")</span>
                                </a>
                                <ul class="submenu">
                                    @canany(['posDev','posAuthor','posRsv','weddingDvl','weddingSls','weddingAuthor','weddingRsv'])
                                        @foreach ($services_admin as $menuadmin)
                                            @if ($menuadmin->name == "Weddings")
                                                @canany(['posDev','weddingDvl','weddingSls','weddingAuthor','weddingRsv'])
                                                    <li>
                                                        <a href="{{ url("$menuadmin->nicname"."-admin") }}">
                                                            <span class="micon">{!! $menuadmin->icon." " !!}</span>@lang("messages.".$menuadmin->name)
                                                        </a>
                                                    </li>
                                                @endcanany
                                            @else
                                                @canany(['posDev','posAuthor','posRsv'])
                                                    <li>
                                                        <a href="{{ url("$menuadmin->nicname"."-admin") }}">
                                                            <span class="micon">{!! $menuadmin->icon." " !!}</span>@lang("messages.".$menuadmin->name)
                                                        </a>
                                                    </li>
                                                @endcanany
                                            @endif
                                        @endforeach
                                    @endcanany
                                </ul>
                            </li>
                        @endcanany
                    </ul>
                @else
                    <div class="notifikasi-menu">
                        @lang('messages.Your account is in the approval process, please wait for 2 x 24 hours for approval! Thank you.')
                    </div>
                @endif
            @else
                <div class="notifikasi-menu">
                    @lang('messages.Your account has been disabled because it does not comply with the established terms.')
                </div>
            @endif
        </div>
    </div>
</div>