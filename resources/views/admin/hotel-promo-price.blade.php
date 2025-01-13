<div id="promo" class="row">
    <div class="col-md-8">
        <div class="card-box">
            <div class="card-box-title">
                <div class="subtitle"><i class="fa fa-percent" aria-hidden="true"></i> Promo</div>
            </div>
            @if (count($promos) > 0)
                <div class="input-container">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
                        <input id="searchPromoByName" type="text" onkeyup="searchPromoByName()" class="form-control" name="search-promo-byname" placeholder="Filter by name">
                    </div>
                </div>
                <table id="tbPromo" class="data-table table nowrap" >
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
                        @foreach ($promos as $promo)
                            @php
                                $promo_room = $rooms->where('id',$promo->rooms_id)->first();
                            @endphp
                            
                            @php
                                $promo_usdrates = ceil($promo->contract_rate / $usdrates->rate);
                                $tax_promo_price = $taxes->tax / 100;
                                $promo_tax = ceil(($promo_usdrates + $promo->markup) * $tax_promo_price);
                                $harga_publish_promo_price = ceil($promo_usdrates * $tax_promo_price);
                            @endphp
                            @if ($promo->book_periode_end < $now)
                                <tr class="expired-background">
                            @elseif ($promo->book_periode_start <= $now and $promo->book_periode_end >= $now)
                                <tr class="onbook-background">
                            @else
                                <tr>
                            @endif
                                <td>
                                    @if ($promo->promotion_type == "Hot Deal")
                                        <div class="promotion-name bg-red">{{ $promo->promotion_type }}</div>
                                    @elseif ($promo->promotion_type == "Best Choice")
                                        <div class="promotion-name bg-green">{{ $promo->promotion_type }}</div>
                                    @elseif ($promo->promotion_type == "Best Price")
                                        <div class="promotion-name bg-orange">{{ $promo->promotion_type }}</div>
                                    @elseif ($promo->promotion_type == "Special Offer")
                                        <div class="promotion-name bg-blue">{{ $promo->promotion_type }}</div>
                                    @endif
                                    <b>{{ $promo->name }}</b><br>
                                    <p>
                                        {{ $promo_room->rooms }}
                                        @if ($promo->minimum_stay > 1)
                                            (min: {{ $promo->minimum_stay }} nights)
                                        @else
                                            (min: {{ $promo->minimum_stay }} night)
                                        @endif
                                        <br>
                                    </p>
                                    @if ($promo->booking_code != "")
                                        <b>{{ $promo->booking_code }}</b><br>
                                    @endif

                                    @if ($promo->book_periode_end < $now)
                                        <div class="status-expired m-t-8"></div>
                                    @else
                                        @if ($promo->status == "Rejected")
                                            <div class="status-rejected m-t-8"></div>
                                        @elseif ($promo->status == "Invalid")
                                            <div class="status-invalid m-t-8"></div>
                                        @elseif ($promo->status == "Active")
                                            <div class="status-active m-t-8"></div>
                                        @elseif ($promo->status == "Waiting")
                                            <div class="status-waiting m-t-8"></div>
                                        @elseif ($promo->status == "Draft")
                                            <div class="status-draft m-t-8"></div>
                                        @elseif ($promo->status == "Archived")
                                            <div class="status-archived m-t-8"></div>
                                        @else
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <b>Booking Period</b>
                                    <p>{{ dateFormat($promo->book_periode_start) }} - {{ dateFormat($promo->book_periode_end) }}<br></p>
                                    @if ($promo->book_periode_end < $now)
                                        <div class="expired-ico">
                                            <img src="{{ asset ('storage/icon/expired.png') }}" alt="{{ $promo->name }}" loading="lazy">
                                        </div>
                                    @endif
                                    <b>Stay Period</b>
                                    <p>{{ dateFormat($promo->periode_start) }} - {{ dateFormat($promo->periode_end) }}</p>
                                </td>
                                <td>
                                    <div class="rate-usd">{{ "$ ". number_format($promo_usdrates, 0, ",", ".") }}</div>
                                    <div class="rate-idr">{{ "IDR ". number_format($promo->contract_rate, 0, ",", ".") }}</div>
                                </td>
                                <td>
                                    <div class="rate-usd">{{ "$ ". number_format($promo->markup, 0, ",", ".") }}</div>
                                    <div class="rate-idr">{{ "IDR ". number_format($promo->markup * $usdrates->rate, 0, ",", ".") }}</div>
                                </td>
                                <td>
                                    <div class="rate-usd">{{ "$ ". number_format($promo_tax, 0, ",", ".") }}</div>
                                    <div class="rate-idr">{{ "IDR ". number_format($promo_tax * $usdrates->rate, 0, ",", ".") }}</div>
                                </td>
                                <td>
                                    <div class="rate-usd">{{ "$ ". number_format(($promo_usdrates + $promo->markup + $promo_tax), 0, ",", ".") }}</div>
                                    <div class="rate-idr">{{ "IDR ". number_format(($promo_usdrates + $promo->markup + $promo_tax)* $usdrates->rate, 0, ",", ".") }}</div>
                                </td>
                                <td class="text-right">
                                    <div class="table-action">
                                        <a href="modal" data-toggle="modal" data-target="#detail-promo-{{ $promo->id }}">
                                            <button class="btn-view" data-toggle="tooltip" data-placement="top" title="Detail"><i class="dw dw-eye"></i></button>
                                        </a>
                                        @canany(['posDev','posAuthor'])
                                            <a href="modal" data-toggle="modal" data-target="#edit-promo-{{ $promo->id }}">
                                                <button class="btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-copy fa fa-pencil"></i></button>
                                            </a>
                                            <form action="/delete-promo/{{ $promo->id }}" method="post">
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
                                {{-- MODAL DETAIL PROMO --}}
                                <div class="modal fade" id="detail-promo-{{ $promo->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="card-box">
                                                <div class="card-box-title">
                                                    <div class="title"><i class="dw dw-eye"></i> Promo</div>
                                                    <div class="status-card">
                                                        @if ($promo->book_periode_end < $now)
                                                            <div class="status-expired"></div>
                                                        @else
                                                            @if ($promo->status == "Rejected")
                                                                <div class="status-rejected"></div>
                                                            @elseif ($promo->status == "Invalid")
                                                                <div class="status-invalid"></div>
                                                            @elseif ($promo->status == "Active")
                                                                <div class="status-active"></div>
                                                            @elseif ($promo->status == "Waiting")
                                                                <div class="status-waiting"></div>
                                                            @elseif ($promo->status == "Draft")
                                                                <div class="status-draft"></div>
                                                            @elseif ($promo->status == "Archived")
                                                                <div class="status-archived"></div>
                                                            @else
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    @if ($promo->book_periode_end < $now)
                                                        <div class="col-12 col-sm-5">
                                                            <div class="expired-ico">
                                                                <img src="{{ asset ('storage/icon/expired.png') }}" alt="{{ $promo->name }}" loading="lazy">
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($promo->booking_code != "")
                                                        <div class="col-6 text-right">
                                                            <div class="code-title">Booking code</div>
                                                            <div class="code">{{ $promo->booking_code }}</div>
                                                        </div>
                                                        <div class="col-6 text-left flex-end">
                                                            <div class="subtitle">USD Rate {{ ": ". number_format($usdrates->rate, 0, ",", ".") }}</div>
                                                        </div>
                                                    @else
                                                        <div class="col-12 text-left flex-end">
                                                            <div class="subtitle">USD Rate {{ ": ". number_format($usdrates->rate, 0, ",", ".") }}</div>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-12">
                                                        <hr class="form-hr">
                                                    </div>
                                                    <div class="col-6 col-sm-6 m-b-8">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <p><b>Name :</b></p>
                                                            </div>
                                                            <div class="col-12 m-b-8">
                                                                <p>{{ $promo->name }}</p>
                                                            </div>
                                                            <div class="col-12">
                                                                <p><b>Room :</b></p>
                                                            </div>
                                                            <div class="col-12 m-b-8">
                                                                <p>{{ $promo->rooms->rooms }}</p>
                                                            </div>
                                                            <div class="col-12">
                                                                <p><b>Minimum Stay :</b></p>
                                                            </div>
                                                            <div class="col-12 m-b-8">
                                                                <p>{{ $promo->minimum_stay.' Nights' }}</p>
                                                            </div>
                                                            <div class="col-12">
                                                                <p><b>Booking Period :</b></p>
                                                            </div>
                                                            @if ($promo->book_periode_end < $now)
                                                                <div class="col-12 m-b-8" style="color:red;">
                                                                    <p>{{ dateFormat($promo->book_periode_start)." - ". dateFormat($promo->book_periode_end) }}</p>
                                                                </div>
                                                            @else
                                                                <div class="col-12 m-b-8">
                                                                <p>{{ dateFormat($promo->book_periode_start)." - ". dateFormat($promo->book_periode_end) }}</p>
                                                                </div>
                                                            @endif
                                                            <div class="col-12">
                                                                <p><b>Stay Period :</b></p>
                                                            </div>
                                                            <div class="col-12 m-b-8">
                                                                <p>{{ dateFormat($promo->periode_start)." - ". dateFormat($promo->periode_end) }}</p>
                                                            </div>  
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-6">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <p><b>Markup :</b></p>
                                                            </div>
                                                            <div class="col-12 m-b-8">
                                                                <div class="rate-usd">{{ "$ ".number_format($promo->markup, 0, ",", ".") }}</div>
                                                                <div class="rate-idr">{{ "IDR ".number_format($promo->markup * $usdrates->rate, 0, ",", ".") }}</div>
                                                            </div>
                                                            <div class="col-12">
                                                                <p><b>Contract Rate :</b></p>
                                                            </div>
                                                            <div class="col-12 m-b-8">
                                                                <div class="rate-usd">{{ "$ ". number_format($promo_usdrates, 0, ",", ".") }}</div>
                                                                <div class="rate-idr">{{ "IDR ". number_format($promo->contract_rate, 0, ",", ".") }}</div>
                                                            </div>
                                                            <div class="col-12">
                                                                <p><b>Tax :</b></p>
                                                            </div>
                                                            <div class="col-12 m-b-8">
                                                                <div class="rate-usd">{{ "$ ". number_format($harga_publish_promo_price, 0, ",", ".") }}</div>
                                                                <div class="rate-idr">{{ "IDR ". number_format($harga_publish_promo_price * $usdrates->rate, 0, ",", ".") }}</div>
                                                            </div>
                                                        </div>       
                                                    </div>
                                                    @if ($promo->include != "" or $promo->benefits != "" or $promo->additional_info != "")
                                                        <div class="col-12">
                                                            <hr class="form-hr">
                                                        </div>
                                                        <div class="col-12 ">
                                                            @if ($promo->include != "")
                                                                <p><b>Include :</b></p>
                                                                <p>{!! $promo->include !!}</p>
                                                            @endif
                                                            @if ($promo->benefits != "")
                                                                <p class="m-t-8"><b>Benefits :</b></p>
                                                                <p>{!! $promo->benefits !!}</p>
                                                            @endif
                                                            @if ($promo->additional_info != "")
                                                                <p class="m-t-8"><b>Additional Information :</b></p>
                                                                <p>{!! $promo->additional_info !!}</p>
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
                                                        {{ "$ ". number_format(($promo_usdrates + $promo->markup + $harga_publish_promo_price), 0, ",", ".") }}
                                                    </div>
                                                    <div class="col-12 price-idr m-b-8">
                                                        {{ "IDR ". number_format(($promo_usdrates + $promo->markup + $harga_publish_promo_price)* $usdrates->rate, 0, ",", ".") }}
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
                                    {{-- MODAL EDIT PROMO --}}
                                    <div class="modal fade" id="edit-promo-{{ $promo->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="card-box">
                                                    <div class="card-box-title">
                                                        <div class="title"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Promo Edit</div>
                                                    </div>
                                                    <form id="update-promo-{{ $promo->id }}" action="/fedit-promo-{{ $promo->id }}" method="post" enctype="multipart/form-data">
                                                        @method('put')
                                                        {{ csrf_field() }}
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="promotion_type">Promotion Type </label>
                                                                    <select id="promotion_type" name="promotion_type" class="form-control custom-select @error('promotion_type') is-invalid @enderror" required>
                                                                        <option selected value="{{ $promo->promotion_type }}">{{ $promo->promotion_type }}</option>
                                                                        <option value="Special Offer">Special Offer</option>
                                                                        <option value="Best Choice">Best Choice</option>
                                                                        <option value="Best Price">Best Price</option>
                                                                        <option value="Hot Deal">Hot Deal</option>
                                                                    </select>
                                                                    @error('promotion_type')
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="quotes">Quotes</label>
                                                                    <input type="text" name="quotes" id="quotes" wire:model="quotes" class="form-control  @error('quotes') is-invalid @enderror" placeholder="Ex: Get special price for special moment!" value="{!! $promo->quotes !!}">
                                                                    @error('quotes')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="booking_code">Booking Code</label>
                                                                    <input name="booking_code" id="booking_code" type="text" wire:model="booking_code" class="form-control @error('booking_code') is-invalid @enderror" placeholder="Insert booking code" value="{{ $promo->booking_code }}">
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
                                                                    <select id="status" name="status" class="custom-select @error('status') is-invalid @enderror" required>
                                                                        <option selected value="{{ $promo->status }}">{{ $promo->status }}</option>
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
                                                                    <input name="name" id="name" type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Select Date and Time" value="{{ $promo->name }}" required>
                                                                    @error('name')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                @php
                                                                    $pr_room = $rooms->where('id',$promo->rooms_id)->first();
                                                                @endphp
                                                                <div class="form-group">
                                                                    <label for="rooms_id">Rooms </label>
                                                                    <select id="rooms_id" name="rooms_id" class="custom-select @error('rooms_id') is-invalid @enderror" required>
                                                                        <option selected value="{{ $pr_room->id }}">{{ $pr_room->rooms }}</option>
                                                                        @foreach ($rooms as $psroom)
                                                                            <option value="{{ $psroom->id }}">{{ $psroom->rooms }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('rooms_id')
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="book_periode_start">Book Period Start </label>
                                                                    <input name="book_periode_start" id="book_periode_start" class="form-control date-picker @error('book_periode_start') is-invalid @enderror" placeholder="Select Date and Time" type="text" value="{{ dateFormat($promo->book_periode_start) }}" required>
                                                                    @error('book_periode_start')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="book_periode_end">Book Period End </label>
                                                                    <input name="book_periode_end" id="book_periode_end" class="form-control date-picker @error('book_periode_end') is-invalid @enderror" placeholder="Select Date and Time" type="text" value="{{ dateFormat($promo->book_periode_end) }}" required>
                                                                    @error('book_periode_end')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="periode_start">Stay Period Start </label>
                                                                    <input name="periode_start" id="periode_start" class="form-control date-picker @error('periode_start') is-invalid @enderror" placeholder="Select Date and Time" type="text" value="{{ dateFormat($promo->periode_start) }}" required>
                                                                    @error('periode_start')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="periode_end">Stay Period End </label>
                                                                    <input name="periode_end" id="periode_end" class="form-control date-picker @error('periode_end') is-invalid @enderror" placeholder="Select Date and Time" type="text" value="{{ dateFormat($promo->periode_end) }}" required>
                                                                    @error('periode_end')
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
                                                                        <input type="number" id="contract_rate" name="contract_rate" class="input-icon form-control @error('contract_rate') is-invalid @enderror" placeholder="Insert Markup" value="{{ $promo->contract_rate }}" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="markup">Markup </label>
                                                                    <div class="btn-icon">
                                                                        <span>$</span>
                                                                        <input type="number" id="markup" name="markup" class="input-icon form-control @error('markup') is-invalid @enderror" placeholder="Insert Markup" value="{{ $promo->markup }}" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="minimum_stay">Minimum Stay </label>
                                                                    <input type="number" min="1" max="8" name="minimum_stay" class="form-control  @error('minimum_stay') is-invalid @enderror" placeholder="Insert minimum stay" value="{{ $promo->minimum_stay }}" required>
                                                                    @error('minimum_stay')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="include">Include</label>
                                                                    <textarea id="include_promo_edit" name="include"  class="ckeditor form-control @error('include') is-invalid @enderror" placeholder="Insert some text ...">{!! $promo->include !!}</textarea>
                                                                    @error('include')
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                <label for="benefits">Benefits</label>
                                                                    <textarea id="benefits_edit_promo" name="benefits"  class="ckeditor form-control @error('benefits') is-invalid @enderror" placeholder="Insert some text ...">{!! $promo->benefits !!}</textarea>
                                                                    @error('benefits')
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="additional_info">Additional Information</label>
                                                                    <textarea id="additional_info" name="additional_info"  class="ckeditor form-control  @error('additional_info') is-invalid @enderror" placeholder="Insert some text ...">{!! $promo->additional_info !!}</textarea>
                                                                    @error('additional_info')
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <input id="note" name="note" value="" type="hidden">
                                                            <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                                            <input id="hotels_id" name="hotels_id" value="{{ $hotel->id }}" type="hidden">
                                                        </div>
                                                    </form>
                                                    <div class="card-box-footer">
                                                        <button type="submit" form="update-promo-{{ $promo->id }}" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Update</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcanany
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="notif">The hotel doesn't have a promotion yet, please add a promotion now!</div>
            @endif
            @canany(['posDev','posAuthor'])
                <div class="card-box-footer">
                    <a href="/send-promo-to-specific-agent-{{ $hotel->id }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="icon-copy fa fa-envelope" aria-hidden="true"></i> Send to Specific Email</button>
                    </a>
                    <a href="/send-promo-to-agent-{{ $hotel->id }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="icon-copy fa fa-envelope" aria-hidden="true"></i> Send Email Promo</button>
                    </a>
                    <a href="modal" data-toggle="modal" data-target="#add-promo-{{ $hotel->id }}" data-toggle="tooltip" data-placement="top" title="Detail">
                        <button type="button" class="btn btn-primary btn-sm"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Promo</button>
                    </a>
                </div>
                {{-- MODAL ADD PROMO --}}
                <div class="modal fade" id="add-promo-{{ $hotel->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="card-box">
                                <div class="card-box-title">
                                    <div class="title"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Promo</div>
                                </div>
                                <form id="create-promo" action="/fadd-promo" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="promotion_type">Promotion Type </label>
                                                <select id="promotion_type" name="promotion_type" class="form-control custom-select @error('promotion_type') is-invalid @enderror" required>
                                                    <option selected value="Special Offer">Special Offer</option>
                                                    <option value="Best Choice">Best Choice</option>
                                                    <option value="Best Price">Best Price</option>
                                                    <option value="Hot Deal">Hot Deal</option>
                                                </select>
                                                @error('promotion_type')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="quotes">Quotes</label>
                                                <input type="text" name="quotes" id="quotes" wire:model="quotes" class="form-control  @error('quotes') is-invalid @enderror" placeholder="Ex: Get special price for special moment!" value="{{ old('quotes') }}">
                                                @error('quotes')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
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
                                                <label for="name">Name </label>
                                                <input type="text" name="name" id="name" wire:model="name" class="form-control  @error('name') is-invalid @enderror" placeholder="Insert promo name" value="{{ old('name') }}" required>
                                                @error('name')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rooms_id">Room </label>
                                                <select id="rooms_id" name="rooms_id" class="form-control custom-select @error('rooms_id') is-invalid @enderror" required>
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
                                                <label for="minimum_stay">Minimum Stay </label>
                                                <input type="number" min="1" max="8" name="minimum_stay" id="minimum_stay" wire:model="minimum_stay" class="form-control custom-input-number  @error('minimum_stay') is-invalid @enderror" placeholder="Insert minimum stay" value="{{ old('minimum_stay') }}" required>
                                                @error('minimum_stay')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="book_periode_start">Book Period Start </label>
                                                <input type="text" name="book_periode_start" id="book_periode_start" wire:model="book_periode_start" class="form-control date-picker @error('book_periode_start') is-invalid @enderror" placeholder="Select date" value="{{ old('book_periode_start') }}" required>
                                                @error('book_periode_start')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="book_periode_end">Book Period End </label>
                                                <input type="text" name="book_periode_end" id="book_periode_end" wire:model="book_periode_end" class="form-control date-picker @error('book_periode_end') is-invalid @enderror" placeholder="Select date" value="{{ old('book_periode_end') }}" required>
                                                @error('book_periode_end')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="periode_start">Stay Period Start </label>
                                                <input type="text" name="periode_start" id="periode_start" wire:model="periode_start" class="form-control date-picker @error('periode_start') is-invalid @enderror" placeholder="Select date" value="{{ old('periode_start') }}" required>
                                                @error('periode_start')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="periode_end">Stay Period End </label>
                                                <input type="text" name="periode_end" id="periode_end" wire:model="periode_end" class="form-control date-picker @error('periode_end') is-invalid @enderror" placeholder="Select date" value="{{ old('periode_end') }}" required>
                                                @error('periode_end')
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
                                                    <input type="number" id="contract_rate" name="contract_rate" class="input-icon form-control @error('contract_rate') is-invalid @enderror" placeholder="Insert contract rate" value="{{ old('contract_rate') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="markup">Markup </label>
                                                <div class="btn-icon">
                                                    <span>$</span>
                                                    <input type="number" id="markup" name="markup" class="input-icon form-control @error('markup') is-invalid @enderror" placeholder="Insert markup" value="{{ old('markup') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="include">Include</label>
                                                <textarea id="include" name="include"  class="ckeditor form-control border-radius-0 @error('include') is-invalid @enderror" placeholder="Insert some text ...">{{ old('include') }}</textarea>
                                                @error('include')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="benefits">Benefits</label>
                                                <textarea id="benefits" name="benefits"  class="ckeditor form-control border-radius-0 @error('benefits') is-invalid @enderror" placeholder="Insert some text ...">{{ old('benefits') }}</textarea>
                                                @error('benefits')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="additional_info">Additional Information</label>
                                                <textarea id="additional_info" name="additional_info"  class="ckeditor form-control border-radius-0 @error('additional_info') is-invalid @enderror" placeholder="Insert some text ...">{{ old('additional_info') }}</textarea>
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
                                    <button type="submit" form="create-promo" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Add Promo</button>
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