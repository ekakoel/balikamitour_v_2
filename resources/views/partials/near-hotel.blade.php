<div class="col-md-12">
    <div class="card-box">
        <div class="pages-subtitle">
            @lang('messages.Hotels Around') {{ $hotel->region }}
            <span>@lang('messages.Availability') {{ count($nearhotels) }}</span>
        </div>
        <div class="card-box-content">
            @foreach ($nearhotels as $hotel)
                <div class="card m-b-18">
                    @if ($hotel->promos->isNotEmpty())
                        <div class="persen-promo" data-toggle="tooltip" data-placement="top" title="Promotion" aria-hidden="true">
                            <img src="{{ asset('storage/icon/persen.png') }}" alt="Promo discount" loading="lazy">
                        </div>
                    @endif
                    <div class="image-container">
                        <div class="first">
                            <ul class="card-lable">
                                <li class="item">
                                    <div class="meta-box">
                                        <i class="icon-copy fa fa-map-marker" aria-hidden="true"></i>
                                        <p class="text">{{ $hotel->region }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <a href="hotel-{{ $hotel->code }}">
                            <img src="{{ asset('storage/hotels/hotels-cover/' . $hotel->cover) }}"  class="img-fluid rounded thumbnail-image">
                            <div class="card-detail-title">{{ $hotel->name }}</div>
                        </a>
                        {{-- <form action="{{ route('hotels.prices',$hotel->code) }}" method="POST" role="search">
                            {{ csrf_field() }}
                            <input id="checkincout" name="checkincout" type="hidden" value="{{ date('m/d/Y',strtotime(session('booking_dates.checkin')))." - ".date('m/d/Y',strtotime(session('booking_dates.checkout'))) }}" >
                            <button type="submit" style="border: none; background: none; padding: 0; cursor: pointer;">
                                <img src="{{ asset('storage/hotels/hotels-cover/' . $hotel->cover) }}"  class="img-fluid rounded thumbnail-image">
                                <div class="card-detail-title">{{ $hotel->name }}</div>
                            </button>
                        </form> --}}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>