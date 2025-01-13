<div class="card">
    @if ($hotel->promos->isNotEmpty())
        <div class="persen-promo" data-toggle="tooltip" data-placement="top" title="Promotion" aria-hidden="true">
            <img src="{{ asset('storage/icon/persen.png') }}" alt="Promo discount" loading="lazy">
        </div>
        @php
            $promoImages = [
                'Hot Deal' => 'hot_deal_promo.png',
                'Best Choice' => 'best_choice_promo.png',
                'Best Price' => 'best_price_promo.png',
                'Special Offer' => 'special_offer_promo.png',
            ];
        @endphp
        @foreach ($hotel->promos as $promo)
            @isset($promoImages[$promo->promotion_type])
                <div class="promo-hot-deal">
                    <img src="{{ asset('storage/icon/' . $promoImages[$promo->promotion_type]) }}" alt="{{ $promo->promotion_type }} Promotion">
                </div>
            @endisset
        @endforeach
    @endif
    <div class="image-container">
        <div class="top-lable">
            <i class="icon-copy fa fa-map-marker" aria-hidden="true"></i>
            <a target="__blank" href="{{ $hotel->map }}">
                {{ $hotel->region }}
            </a>
        </div>
        <a href="{{ route('hotels.detail',$hotel->code) }}">
            <img src="{{ $hotel->cover?asset('storage/hotels/hotels-cover/' . $hotel->cover):asset('images/default-image.webp') }}" class="thumbnail-image" loading="lazy">
            <div class="card-detail-title">{{ $hotel->name }}</div>
        </a>
    </div>
</div>
