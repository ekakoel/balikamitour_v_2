@section('content')
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    @can('isAdmin')
        @php
            $usd = ceil($tours->contract_rate / $usdrates->rate);
            $usd_markup = $usd + $tours->markup;
            $pajak = ceil($usd_markup *($taxes->tax / 100));
            $final_prices = $usd_markup + $pajak;
        @endphp
        <div class="main-container">
            <div class="pd-ltr-20">
                <div class="min-height-200px">
                    <div class="page-header">
                        <div class="title">
                            <i class="icon-copy fa fa-briefcase" aria-hidden="true"></i> Tour Package
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/admin-panel">Admin Panel</a></li>
                                <li class="breadcrumb-item"><a href="/tours-admin">Tours Package</a></li>
                                @if (isset($tours->partners_id))
                                    <li class="breadcrumb-item"><a href="/detail-partner-{{ $tours->partners_id }}">{{ $partner->name }}</a></li>
                                @else
                                    <li class="breadcrumb-item">?</li>
                                @endif
                                <li class="breadcrumb-item active" aria-current="page">{{ $tours->name }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="info-action">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="product-wrap">
                        <div class="product-detail-wrap">
                            <div class="row">
                                {{-- ATTENTIONS --}}
                                @if (count($attentions)>0)
                                    <div class="col-md-4 mobile">
                                        <div class="row">
                                            @include('admin.usd-rate')
                                            @include('layouts.attentions')
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-8">
                                    <div class="card-box">
                                        <div class="card-box-title">
                                            <div class="title">{{ $tours->name }}</div>
                                            <div class="status-card">
                                                @if ($tours->status == "Rejected")
                                                    <div class="status-rejected"></div>
                                                @elseif ($tours->status == "Invalid")
                                                    <div class="status-invalid"></div>
                                                @elseif ($tours->status == "Active")
                                                    <div class="status-active"></div>
                                                @elseif ($tours->status == "Waiting")
                                                    <div class="status-waiting"></div>
                                                @elseif ($tours->status == "Draft")
                                                    <div class="status-draft"></div>
                                                @elseif ($tours->status == "Archived")
                                                    <div class="status-archived"></div>
                                                @else
                                                @endif
                                            </div>
                                        </div>
                                        <div class="page-card">
                                            <div class="card-banner">
                                                <img src="{{ asset ('storage/tours/tours-cover/' . $tours->cover) }}" alt="{{ $tours->name }}" loading="lazy">
                                            </div>
                                            <div class="card-content">
                                                <div class="data-web"><i class="icon-copy fa fa-map-marker" aria-hidden="true"></i> {{  $tours->location  }}</div>
                                                <hr class="form-hr">
                                                <div class="card-text">
                                                    <div class="row ">
                                                        <div class="col-4">
                                                            <div class="card-subtitle">Partner:</div>
                                                            <p>
                                                                @if (isset($tours->partners_id))
                                                                    <a href="/detail-partner-{{ $partner->id }}">
                                                                        {{ $partner->name }}
                                                                    </a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="card-subtitle">Duration:</div>
                                                            <p>{{ $tours->duration }}</p>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="card-subtitle">Capacity:</div>
                                                            <p>{{ $tours->qty." Pax" }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="form-hr">
                                            </div>
                                        </div>
                                        <div class="card-text">
                                            <div class="card-subtitle">Description:</div>
                                            <p>{!! $tours->description !!}</p>
                                        </div>
                                        <div class="card-text">
                                            <div class="card-subtitle">Destinations:</div>
                                            <p>{!! $tours->destinations !!}</p>
                                        </div>
                                        <div class="card-text">
                                            <div class="card-subtitle">Itinerary:</div>
                                            <p>{!! $tours->itinerary !!}</p>
                                        </div>
                                        <div class="card-text">
                                            <div class="card-subtitle">Include:</div>
                                            <p>{!! $tours->include !!}</p>
                                        </div>
                                        @if ($tours->additional_info != "")
                                            <div class="card-text">
                                                <div class="card-subtitle">Additional Information:</div>
                                                <p>{!! $tours->additional_info !!}</p>
                                            </div>
                                        @endif
                                        @if ($tours->cancellation_policy != "")
                                            <div class="card-text">
                                                <div class="card-subtitle">Cancellation Policy:</div>
                                                <div class="cancelation-policy-view">
                                                    {!! $tours->cancellation_policy !!}
                                                </div>
                                            </div>
                                        @endif
                                        <div class="card-box-footer">
                                            @canany(['posDev','posAuthor'])
                                                <a href="/edit-tour-{{ $tours['id'] }}"><button class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button></a>
                                            @endcanany
                                            <a href="/tours-admin"><button class="btn btn-secondary" ><i class="icon-copy fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                                        </div>
                                    </div>
                                    <div id="prices" class="card-box">
                                        <div class="card-box-title">
                                            <div class="title">Prices</div>
                                        </div>
                                        <div class="input-container">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
                                                <input id="searchPriceByCapacity" type="text" onkeyup="searchPriceByCapacity()" class="form-control" name="search-byroom" placeholder="Filter by Capacity">
                                            
                                            </div>
                                        </div>
                                        <table id="tbPrice" class="data-table table stripe nowrap" >
                                            <thead>
                                                <tr>
                                                    <th style="width: 20%;">Capacity</th>
                                                    <th style="width: 10%;">Contract Rate</th>
                                                    <th style="width: 15%;">Markup</th>
                                                    <th style="width: 10%;">Tax</th>
                                                    <th style="width: 15%;">Rate / Pax</th>
                                                    <th style="width: 20%;">Status</th>
                                                    <th class="datatable-nosort text-center" style="width: 10%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($prices as $price)
                                                    @php
                                                        $harga_kontrak_usd = ceil($price->contract_rate/$usdrates->rate);
                                                        $price_tax = ceil(($harga_kontrak_usd + $price->markup)*($taxes->tax/100));
                                                        $price_tax_idr = $price_tax*$usdrates->rate;
                                                        $public_rate_pax = $harga_kontrak_usd + $price->markup + $price_tax;
                                                        $public_rate_pax_idr = $public_rate_pax*$usdrates->rate;
                                                        $markup_idr = ceil($price->markup*$usdrates->rate);
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $price->min_qty." - ". $price->max_qty." Guests" }}</td>
                                                        <td>{{ "$ ".number_format($harga_kontrak_usd, 0, ".", ",") }}</td>
                                                        <td>{{ "$ ".number_format($price->markup, 0, ".", ",") }}</td>
                                                        <td>{{ "$ ".number_format($price_tax, 0, ".", ",") }}</td>
                                                        <td>{{ "$ ".number_format($public_rate_pax, 0, ".", ",") }}</td>
                                                        <td>
                                                            @if ($price->status == "Draft")
                                                                <div class="status-draft"></div>
                                                            @else
                                                                <div class="status-active"></div>
                                                            @endif
                                                        </td>
                                                        <td class="text-right">
                                                            <div class="table-action">
                                                                <a href="modal" data-toggle="modal" data-target="#detail-price-{{ $price->id }}">
                                                                    <button class="btn-view"><i class="fa fa-eye"></i></button>
                                                                </a>
                                                                @canany(['posDev','posAuthor'])
                                                                    <a href="modal" data-toggle="modal" data-target="#update-price-{{ $price->id }}">
                                                                        <button class="btn-edit"><i class="fa fa-edit"></i></button>
                                                                    </a>
                                                                    <form action="/fdelete-tour-price-{{ $price->id }}" method="post">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button class="btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="top" title="Delete"><i class="icon-copy fa fa-trash"></i></button>
                                                                    </form>
                                                                @endcan
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    {{-- MODAL PRICE DETAIL =========================================================================================================================================================--}}
                                                    <div class="modal fade" id="detail-price-{{ $price->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="card-box">
                                                                    <div class="card-box-title">
                                                                        <div class="title"><i class="dw dw-eye"></i> Price {{ $tours->name." | " .$price->min_qty." - ".$price->max_qty." guests" }}</div>
                                                                    </div>
                                                                    <div class="status-card">
                                                                        @if ($price->status == "Draft")
                                                                            <div class="status-draft"></div>
                                                                        @else
                                                                            <div class="status-active"></div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <div class="subtitle">USD Rate {{ ": ". number_format($usdrates->rate, 0, ".", ",") }}</div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <hr class="form-hr">
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="row">
                                                                                        <div class="col-2 m-b-8">
                                                                                            <p><b>Number of Guests :</b></p>
                                                                                            <p>{{ $price->min_qty." - ".$price->max_qty." guests" }}</p>
                                                                                        </div>
                                                                                        <div class="col-2  m-b-8">
                                                                                            <p><b>Contract Rate :</b></p>
                                                                                            <div class="rate-usd">{{ "$ ".number_format($harga_kontrak_usd, 0, ".", ",") }}</div>
                                                                                            <div class="rate-idr">{{ "IDR ". number_format($price->contract_rate, 0, ".", ",") }}</div>
                                                                                        </div>
                                                                                        <div class="col-2  m-b-8">
                                                                                            <p><b>Markup :</b></p>
                                                                                            <div class="rate-usd">{{ "$ ".number_format($price->markup, 0, ".", ",") }}</div>
                                                                                            <div class="rate-idr">{{ "IDR ". number_format($markup_idr, 0, ".", ",") }}</div>
                                                                                        </div>
                                                                                        <div class="col-2  m-b-8">
                                                                                            <p><b>TAX :</b></p>
                                                                                            <div class="rate-usd">{{ "$ ".number_format($price_tax, 0, ".", ",") }}</div>
                                                                                            <div class="rate-idr">{{ "IDR ". number_format($price_tax_idr, 0, ".", ",") }}</div>
                                                                                        </div>
                                                                                        <div class="col-2  m-b-8">
                                                                                            <p><b>Price / Pax :</b></p>
                                                                                            <div class="rate-usd">{{ "$ ".number_format($public_rate_pax, 0, ".", ",") }}</div>
                                                                                            <div class="rate-idr">{{ "IDR ". number_format($public_rate_pax_idr, 0, ".", ",") }}</div>
                                                                                        </div>
                                                                                        <div class="col-2 m-b-8">
                                                                                            <p><b>Expired Date :</b></p>
                                                                                            <p>{{ dateFormat($price->expired_date) }}</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-box-footer">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- MODAL EDIT PRICE =========================================================================================================================================================--}}
                                                    @canany(['posDev','posAuthor'])
                                                        <div class="modal fade" id="update-price-{{ $price->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="card-box text-left">
                                                                        <div class="card-box-title">
                                                                            <div class="title"><i class="dw dw-pencil"></i>Edit Price {{ $tours->name." | " .$price->min_qty." - ".$price->max_qty." guests" }}</div>
                                                                        </div>
                                                                        <form id="fedit-price-{{ $price->id }}" action="/fedit-tour-price-{{ $price->id }}" method="post" enctype="multipart/form-data">
                                                                            @method("PUT")
                                                                            {{ csrf_field() }}
                                                                            <div class="row">
                                                                                <div class="col-md-4 text-left">
                                                                                    <div class="form-group">
                                                                                        <label for="min_qty">Minimum Guests </label>
                                                                                        <input name="min_qty" type="number" min="1" id="min_qty" class="form-control @error('min_qty') is-invalid @enderror" placeholder="Minimum guests" type="text" value="{{ $price->min_qty }}" required>
                                                                                        @error('min_qty')
                                                                                            <span class="invalid-feedback">
                                                                                                <strong>{{ $message }}</strong>
                                                                                            </span>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4 text-left">
                                                                                    <div class="form-group">
                                                                                        <label for="max_qty">Maximum Guests </label>
                                                                                        <input name="max_qty" type="number" min="1" id="max_qty" class="form-control  @error('max_qty') is-invalid @enderror" placeholder="Minimum guests" type="text" value="{{ $price->max_qty }}" required>
                                                                                        @error('max_qty')
                                                                                            <span class="invalid-feedback">
                                                                                                <strong>{{ $message }}</strong>
                                                                                            </span>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4 text-left">
                                                                                    <div class="form-group">
                                                                                        <label for="status">Status </label>
                                                                                        <select id="status" name="status" class="custom-select @error('status') is-invalid @enderror" required>
                                                                                            <option selected value="{{ $price->status }}">{{ $price->status }}</option>
                                                                                            <option value="Draft">Draft</option>
                                                                                            <option value="Active">Active</option>
                                                                                        </select>
                                                                                        @error('status')
                                                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4 text-left">
                                                                                    <div class="form-group">
                                                                                        <label for="contract_rate">Contract Rate</label>
                                                                                        <div class="btn-icon">
                                                                                            <span>Rp</span>
                                                                                            <input name="contract_rate" type="number" min="1" id="contract_rate" class="form-control input-icon @error('contract_rate') is-invalid @enderror" placeholder="Contract rate" type="text" value="{{ $price->contract_rate }}" required>
                                                                                        </div>
                                                                                        @error('contract_rate')
                                                                                            <span class="invalid-feedback">
                                                                                                <strong>{{ $message }}</strong>
                                                                                            </span>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4 text-left">
                                                                                    <div class="form-group">
                                                                                        <label for="markup">Markup</label>
                                                                                        <div class="btn-icon">
                                                                                            <span>$</span>
                                                                                            <input name="markup" type="number" min="1" id="markup" class="form-control input-icon  @error('markup') is-invalid @enderror" placeholder="insert markup" type="text" value="{{ $price->markup }}" required>
                                                                                        </div>
                                                                                        @error('markup')
                                                                                            <span class="invalid-feedback">
                                                                                                <strong>{{ $message }}</strong>
                                                                                            </span>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label for="expired_date">Epired Date </label>
                                                                                        <input name="expired_date" id="expired_date" wire:model="expired_date" class="form-control date-picker @error('expired_date') is-invalid @enderror" placeholder="Select Date and Time" type="text" value="{{ $price->expired_date }}" required>
                                                                                        @error('expired_date')
                                                                                            <span class="invalid-feedback">
                                                                                                <strong>{{ $message }}</strong>
                                                                                            </span>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="tours_id" value="{{ $tours->id }}">
                                                                        </form>
                                                                        <div class="card-box-footer">
                                                                            <button type="submit" form="fedit-price-{{ $price->id }}" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Save</button>
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endcan
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @canany(['posDev','posAuthor'])
                                            <div class="card-box-footer">
                                                <a href="modal" data-toggle="modal" data-target="#add-price">
                                                    <button class="button btn btn-primary" data-toggle="tooltip" data-placement="top" title="Add"><i class="icon-copy fa fa-plus"></i> Add Price</button>
                                                </a>
                                                {{-- MODAL ADD PRICE =========================================================================================================================================================--}}
                                                <div class="modal fade" id="add-price" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="card-box text-left">
                                                                <div class="card-box-title">
                                                                    <div class="title"><i class="fa fa-plus"></i>Add Price</div>
                                                                </div>
                                                                <form id="fadd-price-{{ $tours->id }}" action="/fadd-tour-price-{{ $tours->id }}" method="post" enctype="multipart/form-data">
                                                                    {{ csrf_field() }}
                                                                    <div class="row">
                                                                        <div class="col-md-4 text-left">
                                                                            <div class="form-group">
                                                                                <label for="min_qty">Minimum Guests </label>
                                                                                <input name="min_qty" type="number" min="1" id="min_qty" class="form-control  @error('min_qty') is-invalid @enderror" placeholder="Minimum guests" type="text" required>
                                                                                @error('min_qty')
                                                                                    <span class="invalid-feedback">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 text-left">
                                                                            <div class="form-group">
                                                                                <label for="max_qty">Maximum Guests </label>
                                                                                <input name="max_qty" type="number" min="1" id="max_qty" class="form-control  @error('max_qty') is-invalid @enderror" placeholder="Minimum guests" type="text" required>
                                                                                @error('max_qty')
                                                                                    <span class="invalid-feedback">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 text-left">
                                                                            <div class="form-group">
                                                                                <label for="contract_rate">Contract Rate</label>
                                                                                <input name="contract_rate" type="number" min="1" id="contract_rate" class="form-control  @error('contract_rate') is-invalid @enderror" placeholder="Minimum guests" type="text" required>
                                                                                @error('contract_rate')
                                                                                    <span class="invalid-feedback">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 text-left">
                                                                            <div class="form-group">
                                                                                <label for="markup">Markup</label>
                                                                                <input name="markup" type="number" min="1" id="markup" class="form-control  @error('markup') is-invalid @enderror" placeholder="Minimum guests" type="text" required>
                                                                                @error('markup')
                                                                                    <span class="invalid-feedback">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="expired_date">Epired Date </label>
                                                                                <input name="expired_date" id="expired_date" wire:model="expired_date" class="form-control date-picker @error('expired_date') is-invalid @enderror" placeholder="Select Date and Time" type="text" required>
                                                                                @error('expired_date')
                                                                                    <span class="invalid-feedback">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="tours_id" value="{{ $tours->id }}">
                                                                </form>
                                                                <div class="card-box-footer">
                                                                    <button type="submit" form="fadd-price-{{ $tours->id }}" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                                {{-- ATTENTIONS --}}
                                @if (count($attentions)>0)
                                    <div class="col-md-4 desktop">
                                        <div class="row">
                                            @include('admin.usd-rate')
                                            @include('layouts.attentions')
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
        </div>
    @endcan
@endsection

<script>
    function searchPriceByCapacity() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchPriceByCapacity");
        filter = input.value.toUpperCase();
        table = document.getElementById("tbPrice");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
            } else {
            tr[i].style.display = "none";
            }
        }       
        }
    }
</script>

