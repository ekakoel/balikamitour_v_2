@section('title', __('messages.Hotels'))
@section('content')
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="row">
                {{-- Promotion Section --}}
                @if($promotions->isNotEmpty())
                    <div class="col-md-12">
                        <div class="promotion-bookingcode">
                            @foreach ($promotions as $promotion)
                                @include('partials.promotion-card', ['promotion' => $promotion])
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- Hotel Section --}}
                <div class="col-md-12">
                    <div class="card-box">
                        <div class="card-box-title">
                            <div class="subtitle">
                                <i class="fa fa-building"></i> @lang('messages.Hotel')
                            </div>
                        </div>
                        {{-- Search Form --}}
                        <form action="{{ route('hotels.search') }}" method="POST">
                            @csrf
                            <div class="search-container flex-end">
                                <div class="search-item">
                                    <input type="text" class="form-control" name="hotel_name" 
                                        placeholder="@lang('messages.Search by name')" 
                                        value="{{ request('hotel_name') }}">
                                </div>
                                <div class="search-item">
                                    <input type="text" class="form-control" name="hotel_region" 
                                        placeholder="@lang('messages.Search by region')" 
                                        value="{{ request('hotel_region') }}">
                                </div>
                                <div class="search-item text-right">
                                    <button type="submit" class="btn-search btn-primary">
                                        <i class='icon-copy fa fa-search' aria-hidden='true'></i> 
                                        @lang('messages.Search')
                                    </button>
                                </div>
                            </div>
                        </form>
                        {{-- Hotel List --}}
                        <div class="card-box-content">
                            @forelse ($hotels as $hotel)
                                @include('partials.hotel-card', ['hotel' => $hotel])
                            @empty
                                <div class="no-data">@lang('messages.No Hotels Found')</div>
                            @endforelse
                        </div>
                        {{-- Pagination --}}
                        @if($hotels->hasPages())
                            <div class="pagination">
                                <div class="pagination-panel">
                                    {{ $hotels->withQueryString()->links() }}
                                </div>
                                <div class="pagination-desk">
                                    {{ $hotels->total() }} <span>@lang('messages.Hotels Available')</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
