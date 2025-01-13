<div class="card">
    <div class="image-container">
        <div class="top-lable">
            <i class="icon-copy fa fa-building" aria-hidden="true"></i>
            <a target="__blank" href="{{ $wedding->hotels->map }}">
                {{ $wedding->hotels->name }}
            </a>
        </div>
        <a href="{{ route('wedding.detail',$wedding->code) }}">
            <img src="{{ $wedding->cover?asset('storage/weddings/wedding-cover/' . $wedding->cover):asset('images/default-image.webp') }}" class="thumbnail-image" loading="lazy">
            <div class="card-detail-title">{{ $wedding->name }}</div>
        </a>
    </div>
</div>
