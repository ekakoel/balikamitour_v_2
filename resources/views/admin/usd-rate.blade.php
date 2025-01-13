@php
    use App\Models\UsdRates;
    $usdrates = UsdRates::where('name','USD')->first();
    $cnyrates = UsdRates::where('name','CNY')->first();
    $twdrates = UsdRates::where('name','TWD')->first();
@endphp
<div class="col-md-12">
    <div class="card-box">
        <div class="card-box-title">
            <div class="subtitle"><i class="fa fa-money" aria-hidden="true"></i> Currency</div>
        </div>
        <div class="grid-3-container">
            <div class="grid-box">
                <div class="grid-box-title">USD <span>($)</span></div>
                <div class="grid-box-content">{{ "IDR ".number_format($usdrates->sell, 0, ",", ".") }} <span>S</span></div>
                <div class="grid-box-content">{{ "IDR ".number_format($usdrates->buy, 0, ",", ".") }} <span>B</span></div>
            </div>
            <div class="grid-box">
                <div class="grid-box-title">CNY <span>(¥)</span></div>
                <div class="grid-box-content">{{ "IDR ".number_format($cnyrates->sell, 0, ",", ".") }} <span>S</span></div>
                <div class="grid-box-content">{{ "IDR ".number_format($cnyrates->buy, 0, ",", ".") }} <span>B</span></div>
            </div>
            <div class="grid-box">
                <div class="grid-box-title">TWD <span>(NT$)</span></div>
                <div class="grid-box-content">{{ "IDR ".number_format($twdrates->sell, 0, ",", ".") }} <span>S</span></div>
                <div class="grid-box-content">{{ "IDR ".number_format($twdrates->buy, 0, ",", ".") }} <span>B</span></div>
            </div>
        </div>
    </div>
</div>