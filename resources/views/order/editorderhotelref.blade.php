
@php
    $r = 1;
    use Carbon\Carbon;
    // Cache static config values
    $logoDark = Cache::remember('app.logo_dark', 3600, fn() => config('app.logo_dark'));
    $altLogo = Cache::remember('app.alt_logo', 3600, fn() => config('app.alt_logo'));
@endphp
<div class="col-md-8">
    <div class="card-box">
        <div class="card-box-title">
            <div class="subtitle"><i class="fa fa-pencil"></i> @lang('messages.Edit Order')</div>
        </div>
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="order-bil text-left">
                    <!-- Use lazy loading for images -->
                    <img src="{{ $logoDark }}" alt="{{ $altLogo }}" loading="lazy">
                </div>
            </div>
            <div class="col-6 col-md-6 flex-end">
                <div class="label-title">@lang('messages.Order')</div>
            </div>
            <div class="col-md-12 text-right">
                <div class="label-date float-right" style="width: 100%">
                    {{ $order->formatted_date }}
                </div>
            </div>
        </div>
        <form id="edit-order" action="{{ route('fupdate.order', $order->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Other form content -->
        </form>
    </div>
</div>

<!-- Move inline styles to CSS files -->
<style>
    .label-date {
        width: 100%;
    }
</style>
