
<div class="card">
    <div class="image-container">
        <div class="top-lable">
            <p><i class="icon-copy ion-ios-person"></i>{{ $transport->capacity . ' ' . __('messages.Seat') }}</p>
        </div>
        <a href="{{ route('transport.detail',$transport->code) }}">
            <img src="{{ $transport->cover?asset('storage/transports/transports-cover/' . $transport->cover):asset('images/default-image.webp') }}" class="thumbnail-image" loading="lazy">
            <div class="card-detail-title">{{ $transport->name }}</div>
        </a>
    </div>
</div>
