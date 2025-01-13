@php
    use Carbon\Carbon;
@endphp
<div id="package" class="product-wrap m-b-18">
    <div class="product-detail-wrap">
        <div class="row">
            <div class="col-md-8">
                <div class="card-box p-b-0">
                    <div class="card-box-title">
                        <div class="subtitle"><i class="icon-copy fa fa-cubes" aria-hidden="true"></i> Package</div>
                    </div>
                    @if (count($hotel->packages) > 0)
                        <div class="input-container">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
                                <input id="searchPackageByName" type="text" onkeyup="searchPackageByName()" class="form-control" name="search-package-byname" placeholder="Filter by name">
                            </div>
                        </div> 
                        <table id="tbPackage" class="data-table table nowrap" >
                            <thead>
                                <tr>
                                    <th style="width: 10%;">Name</th>
                                    <th style="width: 5%;">Period</th>
                                    <th style="width: 5%;">Contract Rate</th>
                                    <th style="width: 5%;">Markup</th>
                                    <th style="width: 5%;">Tax</th>
                                    <th style="width: 5%;">Published Rate</th>
                                    <th class="datatable-nosort text-center" style="width: 5%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hotel->rooms as $room)
                                    @foreach ($room->packages as $package)
                                        @php
                                            $package_usd = ceil($package->contract_rate / $usdrates->rate);
                                            $package_usd_markup = $package_usd + $package->markup;
                                            $package_tax = $taxes->tax / 100;
                                            $package_tax_price = ceil($package_usd_markup * $package_tax);
                                            $package_final_price = $package_tax_price + $package_usd_markup ;
                                        @endphp
                                        @if ($package->stay_period_end < $now)
                                            <tr class="expired-background">
                                        @elseif ($package->stay_period_start <= $now and $package->stay_period_end >= $now)
                                            <tr class="onbook-background">
                                        @else
                                            <tr>
                                        @endif
                                            <td>
                                                <b>{{ $package->name }}</b><br>
                                                    {{ $room->rooms }}<br>
                                                @if ($package->booking_code != "")
                                                    <b>{{ $package->booking_code }}</b><br>
                                                @endif
                                               
                                                @if ($package->stay_period_end < $now)
                                                    <div class="status-expired m-t-8"></div>
                                                @else
                                                    @if ($package->status == "Rejected")
                                                        <div class="status-rejected m-t-8"></div>
                                                    @elseif ($package->status == "Invalid")
                                                        <div class="status-invalid m-t-8"></div>
                                                    @elseif ($package->status == "Active")
                                                        <div class="status-active m-t-8"></div>
                                                    @elseif ($package->status == "Waiting")
                                                        <div class="status-waiting m-t-8"></div>
                                                    @elseif ($package->status == "Draft")
                                                        <div class="status-draft m-t-8"></div>
                                                    @elseif ($package->status == "Archived")
                                                        <div class="status-archived m-t-8"></div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <b>Minimum Stay</b>
                                                <p>{{ $package->duration." Night" }}</p>
                                                <b>Stay Period</b>
                                                <p>{{ dateFormat($package->stay_period_start) }} - {{ dateFormat($package->stay_period_end) }}<br></p>
                                                @if ($package->stay_period_end < $now)
                                                    <div class="expired-ico">
                                                        <img src="{{ asset ('storage/icon/expired.png') }}" alt="{{ $package->name }}" loading="lazy">
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="rate-usd">{{ "$ ".number_format($package_usd, 0, ",", ".") }}</div>
                                                <div class="rate-idr">{{ "IDR ". number_format($package->contract_rate, 0, ",", ".") }}</div>
                                            </td>
                                            <td>
                                                <div class="rate-usd">{{ "$ ".number_format($package->markup, 0, ",", ".") }}</div>
                                                <div class="rate-idr">{{ "IDR ". number_format($package->markup * $usdrates->rate, 0, ",", ".") }}</div>
                                            </td>
                                            <td>
                                                <div class="rate-usd">{{ "$ ".number_format($package_tax_price, 0, ",", ".") }}</div>
                                                <div class="rate-idr">{{ "IDR ". number_format($package_tax_price * $usdrates->rate, 0, ",", ".") }}</div>
                                            </td>
                                            <td>
                                                <div class="rate-usd">{{ "$ ".number_format($package_final_price, 0, ",", ".") }}</div>
                                                <div class="rate-idr">{{ "IDR ". number_format($package_final_price* $usdrates->rate, 0, ",", ".") }}</div>
                                            </td>
                                            <td class="text-right">
                                                <div class="table-action">
                                                    <a href="modal" data-toggle="modal" data-target="#detail-package-{{ $package->id }}">
                                                        <button class="btn-view" data-toggle="tooltip" data-placement="top" title="Detail"><i class="dw dw-eye"></i></button>
                                                    </a>
                                                    @canany(['posDev','posAuthor'])
                                                        <a href="modal" data-toggle="modal" data-target="#edit-package-{{ $package->id }}">
                                                            <button class="btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-copy fa fa-pencil"></i></button>
                                                        </a>
                                                        <form action="/delete-package/{{ $package->id }}" method="post">
                                                            <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                                            <input id="hotels_id" name="hotels_id" value="{{ $hotel->id }}" type="hidden">
                                                            <button class="btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="top" title="Delete">
                                                                <i class="icon-copy fa fa-trash"></i></button>
                                                            @csrf
                                                            @method('delete')
                                                        </form>
                                                    @endcanany
                                                </div>
                                            </td>
                                            {{-- MODAL DETAIL PACKAGE =========================================================================================--}}
                                            <div class="modal fade" id="detail-package-{{ $package->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="card-box">
                                                            <div class="card-box-title">
                                                                <div class="title"><i class="dw dw-eye"></i> Package</div>
                                                                <div class="status-card">
                                                                    @if ($package->stay_period_end < $now)
                                                                        <div class="status-expired"></div>
                                                                    @else
                                                                        @if ($package->status == "Rejected")
                                                                            <div class="status-rejected"></div>
                                                                        @elseif ($package->status == "Invalid")
                                                                            <div class="status-invalid"></div>
                                                                        @elseif ($package->status == "Active")
                                                                            <div class="status-active"></div>
                                                                        @elseif ($package->status == "Waiting")
                                                                            <div class="status-waiting"></div>
                                                                        @elseif ($package->status == "Draft")
                                                                            <div class="status-draft"></div>
                                                                        @elseif ($package->status == "Archived")
                                                                            <div class="status-archived"></div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                @if ($package->booking_code != "" or $package->stay_period_end < $now)
                                                                    <div class="col-6">
                                                                        @if ($package->booking_code != "")
                                                                            <div class="subtitle m-t-8">{{ "Code : ".$package->booking_code }}</div>
                                                                        @endif
                                                                        @if ($package->stay_period_end < $now)
                                                                            <div class="expired-ico">
                                                                                <img src="{{ asset ('storage/icon/expired.png') }}" alt="{{ $package->name }}" loading="lazy">
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-6 flex-end">
                                                                        <div class="subtitle">USD Rate {{ ": ". number_format($usdrates->rate, 0, ",", ".") }}</div>
                                                                    </div>
                                                                @else
                                                                    <div class="col-12 text-left flex-end">
                                                                        <div class="subtitle">USD Rate {{ ": ". number_format($usdrates->rate, 0, ",", ".") }}</div>
                                                                    </div>
                                                                @endif
                                                                <div class="col-12">
                                                                    <hr class="form-hr">
                                                                </div>
                                                                <div class="col-12 col-sm-6 m-b-8">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p><b>Name :</b></p>
                                                                        </div>
                                                                        <div class="col-12 m-b-8">
                                                                            <p>{{ $package->name }}</p>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <p><b>Room :</b></p>
                                                                        </div>
                                                                        <div class="col-12 m-b-8">
                                                                            <p>{{ $room->rooms }}</p>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <p><b>Stay Period Start :</b></p>
                                                                        </div>
                                                                        <div class="col-12 m-b-8">
                                                                            <p>{{ dateFormat($package->stay_period_start) }}</p>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <p><b>Stay Period End :</b></p>
                                                                        </div>
                                                                        <div class="col-12 m-b-8">
                                                                            <p>{{ dateFormat($package->stay_period_end) }}</p>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <p><b>Minimum Stay :</b></p>
                                                                        </div>
                                                                        <div class="col-12 m-b-8">
                                                                                <p>{{ $package->duration." Night" }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p><b>Contract Rate :</b></p>
                                                                        </div>
                                                                        <div class="col-12 m-b-8">
                                                                            <div class="rate-usd">{{ "$ ".number_format($package_usd, 0, ",", ".") }}</div>
                                                                            <div class="rate-idr">{{ "IDR ". number_format($package->contract_rate, 0, ",", ".") }}</div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <p><b>Markup :</b></p>
                                                                        </div>
                                                                        <div class="col-12 m-b-8">
                                                                            <div class="rate-usd">{{ "$ ".number_format($package->markup, 0, ",", ".") }}</div>
                                                                            <div class="rate-idr">{{ "IDR ". number_format($package_tax_price * $usdrates->rate, 0, ",", ".") }}</div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <p><b>Tax :</b></p>
                                                                        </div>
                                                                        <div class="col-12 m-b-8">
                                                                            <div class="rate-usd">{{ "$ ".number_format($package_tax_price, 0, ",", ".") }}</div>
                                                                            <div class="rate-idr">{{ "IDR ". number_format($package_tax_price * $usdrates->rate, 0, ",", ".") }}</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                @if ($package->include != "" or $package->benefits != "" or $package->additional_info != "")
                                                                    <div class="col-12">
                                                                        <hr class="form-hr">
                                                                    </div>
                                                                    <div class="col-12 ">
                                                                        @if ($package->include != "")
                                                                            <p><b>Include :</b></p>
                                                                            <p>{!! $package->include !!}</p>
                                                                        @endif
                                                                        @if ($package->benefits != "")
                                                                            <p class="m-t-8"><b>Benefits :</b></p>
                                                                            <p>{!! $package->benefits !!}</p>
                                                                        @endif
                                                                        @if ($package->additional_info != "")
                                                                            <p class="m-t-8"><b>Additional Information :</b></p>
                                                                            <p>{!! $package->additional_info !!}</p>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                                <div class="col-12">
                                                                    <hr class="form-hr">
                                                                </div>
                                                                <div class="col-12">
                                                                    <p><b>Published Rate :</b></p>
                                                                </div>
                                                                <div class="col-12 price-usd m-b-8">
                                                                    {{ "$ ". number_format($package_final_price, 0, ",", ".") }}
                                                                </div>
                                                                <div class="col-12 price-idr m-b-8">
                                                                    {{ "IDR ". number_format($package_final_price * $usdrates->rate, 0, ",", ".") }}
                                                                </div>
                                                            </div>
                                                            <div class="card-box-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @canany(['posDev','posAuthor'])
                                                {{-- MODAL EDIT PACKAGE =======================================================================================--}}
                                                <div class="modal fade" id="edit-package-{{ $package->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="card-box">
                                                                <div class="card-box-title">
                                                                    <div class="title"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Package Edit</div>
                                                                </div>
                                                                <form id="update-package-{{ $package->id }}" action="/fedit-package-{{ $package->id }}" method="post" enctype="multipart/form-data">
                                                                    @method('put')
                                                                    {{ csrf_field() }}
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="booking_code">Booking Code</label>
                                                                                <input name="booking_code" id="booking_code" type="text" wire:model="booking_code" class="form-control @error('booking_code') is-invalid @enderror" placeholder="Insert booking code" value="{{ $package->booking_code }}">
                                                                                @error('booking_code')
                                                                                    <span class="invalid-feedback">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="status">Status </label>
                                                                                <select id="status" name="status" class="custom-select col-12 @error('status') is-invalid @enderror" required>
                                                                                    <option selected value="{{ $package->status }}">{{ $package->status }}</option>
                                                                                    <option value="Active">Active</option>
                                                                                    <option value="Draft">Draft</option>
                                                                                </select>
                                                                                @error('status')
                                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="name">Name </label>
                                                                                <input name="name" id="name" type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Select Date and Time" value="{{ $package->name }}" required>
                                                                                @error('name')
                                                                                    <span class="invalid-feedback">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="rooms_id">Rooms </label>
                                                                                <select id="rooms_id" name="rooms_id" class="custom-select col-12 @error('rooms_id') is-invalid @enderror" required>
                                                                                    <option selected value="{{ $room->id }}">{{ $room->rooms }}</option>
                                                                                    @foreach ($rooms as $prsroom)
                                                                                        <option value="{{ $prsroom->id }}">{{ $prsroom->rooms }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @error('rooms_id')
                                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="stay_period_start">Stay Period Start </label>
                                                                                <input  type="text" name="stay_period_start" id="stay_period_start" class="form-control date-picker @error('stay_period_start') is-invalid @enderror" value="{{ dateFormat($package->stay_period_start) }}" required>
                                                                                @error('stay_period_start')
                                                                                    <span class="invalid-feedback">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="stay_period_end">Stay Period End </label>
                                                                                <input type="text" name="stay_period_end" id="stay_period_end" class="form-control date-picker @error('stay_period_end') is-invalid @enderror" value="{{ dateFormat($package->stay_period_end) }}" required>
                                                                                @error('stay_period_end')
                                                                                    <span class="invalid-feedback">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="contract_rate">Contract Rate </label>
                                                                                <div class="btn-icon">
                                                                                    <span>Rp</span>
                                                                                    <input type="number" id="contract_rate" name="contract_rate" class="input-icon form-control @error('contract_rate') is-invalid @enderror" placeholder="Insert Markup" value="{{ $package->contract_rate }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="markup">Markup </label>
                                                                                <div class="btn-icon">
                                                                                    <span>$</span>
                                                                                    <input type="number" id="markup" name="markup" class="input-icon form-control @error('markup') is-invalid @enderror" placeholder="Insert Markup" value="{{ $package->markup }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="duration">Minimum Stay </label>
                                                                                <input  type="text" name="duration" id="duration" class="form-control date-picker @error('duration') is-invalid @enderror" value="{{ $package->duration }}" required>
                                                                                @error('duration')
                                                                                    <span class="invalid-feedback">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="include">Include</label>
                                                                                <textarea id="edit_pack_include" name="include" class="ckeditor form-control border-radius-0 @error('include') is-invalid @enderror" placeholder="Insert some text ...">{!! $package->include !!}</textarea>
                                                                                @error('include')
                                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="benefits">Benefits</label>
                                                                                <textarea id="edit_pack_benefits" name="benefits"  class="ckeditor form-control border-radius-0 @error('benefits') is-invalid @enderror" placeholder="Insert some text ...">{!! $package->benefits !!}</textarea>
                                                                                @error('benefits')
                                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="additional_info">Additional Information</label>
                                                                                <textarea id="edit_pack_additional_info" name="additional_info"  class="ckeditor form-control border-radius-0 @error('additional_info') is-invalid @enderror" placeholder="Insert some text ...">{!! $package->additional_info !!}</textarea>
                                                                                @error('additional_info')
                                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                                                        <input id="hotels_id" name="hotels_id" value="{{ $hotel->id }}" type="hidden">
                                                                    </div>
                                                                </form>
                                                                <div class="card-box-footer">
                                                                    <button type="submit" form="update-package-{{ $package->id }}" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Update</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endcanany
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="notif">!!! The hotel doesn't have a package yet, please add a package!</div>
                    @endif
                    @canany(['posDev','posAuthor'])
                        <div class="card-box-footer">
                            <a href="modal" data-toggle="modal" data-target="#add-package-{{ $hotel->id }}" data-toggle="tooltip" data-placement="top" title="Detail">
                                <button type="button" class="btn btn-primary btn-sm"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add package</button>
                            </a>
                        </div>
                        {{-- MODAL ADD PACKAGE --}}
                        <div class="modal fade" id="add-package-{{ $hotel->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="card-box">
                                        <div class="card-box-title">
                                            <div class="title"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add package</div>
                                        </div>
                                        <form id="create-package" action="/fadd-package" method="post" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="booking_code">Booking Code</label>
                                                        <input type="text" name="booking_code" id="booking_code" wire:model="booking_code" class="form-control  @error('booking_code') is-invalid @enderror" placeholder="Insert booking code" value="{{ old('booking_code') }}">
                                                        @error('booking_code')
                                                            <span class="invalid-feedback">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="duration">Minimum Stay </label>
                                                        <input type="number" min="1" name="duration" class="form-control @error('duration') is-invalid @enderror" placeholder="Insert duration" value="{{ old('duration') }}" required>
                                                        @error('duration')
                                                            <span class="invalid-feedback">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name">Name </label>
                                                        <input type="text" name="name" id="name" wire:model="name" class="form-control  @error('name') is-invalid @enderror" placeholder="Insert package name" value="{{ old('name') }}" required>
                                                        @error('name')
                                                            <span class="invalid-feedback">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="rooms_id">Room <span> *</span></label>
                                                        <select id="rooms_id" name="rooms_id" class="custom-select @error('rooms_id') is-invalid @enderror" required>
                                                            <option selected value="">Select room</option>
                                                            @foreach ($rooms as $sroom)
                                                                <option value="{{ $sroom->id }}">{{ $sroom->rooms }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('rooms_id')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="stay_period_start">Stay Period Start </label>
                                                        <input type="text" name="stay_period_start" class="form-control date-picker @error('stay_period_start') is-invalid @enderror" placeholder="Select date" value="{{ old('stay_period_start') }}" required>
                                                        @error('stay_period_start')
                                                            <span class="invalid-feedback">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="stay_period_end">Stay Period End </label>
                                                        <input type="text" name="stay_period_end" class="form-control date-picker @error('stay_period_end') is-invalid @enderror" placeholder="Select date" value="{{ old('stay_period_end') }}" required>
                                                        @error('stay_period_end')
                                                            <span class="invalid-feedback">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contract_rate">Contract Rate <span> *</span></label>
                                                        <div class="btn-icon">
                                                            <span>Rp</span>
                                                            <input type="number" id="contract_rate" name="contract_rate" class="input-icon form-control @error('contract_rate') is-invalid @enderror" placeholder="Insert contract rate" value="{{ old('contract_rate') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="markup">Markup <span> *</span></label>
                                                        <div class="btn-icon">
                                                            <span>$</span>
                                                            <input type="number" id="markup" name="markup" class="input-icon form-control @error('markup') is-invalid @enderror" placeholder="Insert markup" value="{{ old('markup') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="include">Include</label>
                                                        <textarea id="include_package" name="include"  class="ckeditor form-control @error('include') is-invalid @enderror" placeholder="Insert some text ...">{{ old('include') }}</textarea>
                                                        @error('include')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="benefits">Benefits</label>
                                                        <textarea id="benefits_package" name="benefits"  class="ckeditor form-control @error('benefits') is-invalid @enderror" placeholder="Insert some text ...">{{ old('benefits') }}</textarea>
                                                        @error('benefits')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="additional_info">Additional Information</label>
                                                        <textarea id="additional_info_package" name="additional_info"  class="ckeditor form-control @error('additional_info') is-invalid @enderror" placeholder="Insert some text ...">{{ old('additional_info') }}</textarea>
                                                        @error('additional_info')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                                <input id="hotels_id" name="hotels_id" value="{{ $hotel->id }}" type="hidden">
                                            </div>
                                        </form>
                                        <div class="card-box-footer">
                                            <button type="submit" form="create-package" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Add Package</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcanany
                </div>
            </div>
        </div>
    </div>
</div>