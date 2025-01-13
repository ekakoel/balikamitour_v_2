<div class="row">
    <div class="col-md-8">
        <div class="card-box">
            <div class="card-box-title">
                <div class="subtitle"><i class="fa fa-usd" aria-hidden="true"></i> Normal Price</div>
            </div>
            @if (count($prices) > 0)
                <div class="input-container">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
                        <input id="searchPriceByRoom" type="text" onkeyup="searchPriceByRoom()" class="form-control" name="search-byroom" placeholder="Filter by room">
                    
                    </div>
                </div>
                <table id="tbPrice" class="data-table table stripe nowrap" >
                    <thead>
                        <tr>
                            <th style="width: 7%;">Room Type</th>
                            <th style="width: 5%;">Contract Rate</th>
                            <th style="width: 5%;">Markup</th>
                            <th style="width: 5%;">Kick Back</th>
                            <th style="width: 5%;">Tax{{ " (".$taxes->tax."%)" }}</th>
                            <th style="width: 5%;">Published Rate</th>
                            <th class="datatable-nosort text-center" style="width: 5%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hotel->rooms as $room)
                            @foreach ($room->prices as $price)
                            {{-- Markup Value =============================================================================== --}}
                            @if ($price->end_date > $now)
                                @php
                                    $usdrates_normal_price = ceil($price->contract_rate / $usdrates->rate);
                                    $pajak_normal_price = $taxes->tax / 100;
                                    $harga_sebelum_pajak = $usdrates_normal_price + $price->markup;
                                    $tax_normal_price = ceil($harga_sebelum_pajak * $pajak_normal_price);
                                    $final_normal_price = $tax_normal_price + $harga_sebelum_pajak;
                                    $harga_kick_back = $final_normal_price - $price->kick_back;
                                @endphp
                                    <tr>
                                        <td>
                                            <b>{{ $room->rooms }}</b>
                                            <p>{{ dateFormat($price->start_date)." - ".dateFormat($price->end_date) }}</p>
                                        </td>
                                        <td>
                                            <div class="rate-usd">{{ "$ ".number_format($usdrates_normal_price, 0, ",", ".") }}</div>
                                            <div class="rate-idr">{{"IDR " . number_format($price->contract_rate, 0, ",", ".") }}</div>
                                        </td>
                                        <td>
                                            <div class="rate-usd">{{ "$ ".number_format($price->markup, 0, ",", ".") }}</div>
                                            <div class="rate-idr">{{ "IDR ".number_format($price->markup * $usdrates->rate, 0, ",", ".") }}</div>
                                        </td>
                                        <td>
                                            @if ($price->kick_back > 0)
                                                <div class="rate-usd">{{"$ " . number_format($price->kick_back, 0, ",", ".") }}</div>
                                                <div class="rate-idr">{{"IDR " . number_format($price->kick_back * $usdrates->rate, 0, ",", ".") }}</div>
                                            @else
                                                <div class="rate-usd">-</div>
                                                <div class="rate-idr">-</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="rate-usd">{{"$ " . number_format($tax_normal_price, 0, ",", ".") }}</div>
                                            <div class="rate-idr">{{"IDR " . number_format($tax_normal_price * $usdrates->rate, 0, ",", ".") }}</div>
                                        </td>
                                        <td>
                                            @if ($price->kick_back > 0)
                                                <div class="rate-usd-kicked">{{"$ " . number_format($final_normal_price, 0, ",", ".") }}</div>
                                                <div class="rate-idr-kicked">{{"IDR " . number_format($final_normal_price * $usdrates->rate, 0, ",", ".") }}</div>
                                                <div class="rate-usd">{{"$ " . number_format($harga_kick_back, 0, ",", ".") }}</div>
                                                <div class="rate-idr">{{"IDR " . number_format(($harga_kick_back * $usdrates->rate), 0, ",", ".") }}</div>
                                            @else
                                                <div class="rate-usd">{{"$ " . number_format($final_normal_price, 0, ",", ".") }}</div>
                                                <div class="rate-idr">{{"IDR " . number_format(($final_normal_price * $usdrates->rate), 0, ",", ".") }}</div>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <div class="table-action">
                                                <a href="modal" data-toggle="modal" data-target="#detail-price-{{ $price->id }}">
                                                    <button class="btn-view" data-toggle="tooltip" data-placement="top" title="Detail"><i class="dw dw-eye"></i></button>
                                                </a>
                                                @canany(['posDev','posAuthor'])
                                                    <a href="modal" data-toggle="modal" data-target="#edit-price-{{ $price->id }}">
                                                        <button class="btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-copy fa fa-edit"></i></button>
                                                    </a>
                                                    <form action="/delete-price/{{ $price->id }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="top" title="Delete"><i class="icon-copy fa fa-trash"></i></button>
                                                    </form>
                                                @endcanany
                                            </div>
                                        </td>
                                        {{-- MODAL PRICE DETAIL =========================================================================================================================================================--}}
                                        <div class="modal fade" id="detail-price-{{ $price->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="card-box">
                                                        <div class="card-box-title">
                                                            <div class="title"><i class="dw dw-eye"></i> Price</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="subtitle">USD Rate {{ ": ". number_format($usdrates->rate, 0, ",", ".") }}</div>
                                                            </div>
                                                            <div class="col-12">
                                                                <hr class="form-hr">
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="row">
                                                                    <div class="col-12 m-b-8">
                                                                        <p><b>Hotel Name :</b></p>
                                                                        <p>{{ $hotel->name }}</p>
                                                                    </div>
                                                                    <div class="col-12  m-b-8">
                                                                        <p><b>Room :</b></p>
                                                                        <p>{{ $room->rooms }}</p>
                                                                    </div>
                                                                    <div class="col-12  m-b-8">
                                                                        <p><b>Starting :</b></p>
                                                                        <p>{{ dateFormat($price->start_date) }}</p>
                                                                    </div>
                                                                    <div class="col-12 m-b-8">
                                                                        <p><b>Ending :</b></p>
                                                                        <p>{{ dateFormat($price->end_date) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="row">
                                                                    <div class="col-12  m-b-8">
                                                                        <p><b>Contract Rate :</b></p>
                                                                        <div class="rate-usd">{{ "$ ".number_format($usdrates_normal_price, 0, ",", ".") }}</div>
                                                                        <div class="rate-idr">{{ "IDR ". number_format($price->contract_rate, 0, ",", ".") }}</div>
                                                                    </div>
                                                                    <div class="col-12  m-b-8">
                                                                        <p><b>Markup :</b></p>
                                                                        <div class="rate-usd">{{ "$ ".number_format($price->markup, 0, ",", ".") }}</div>
                                                                        <div class="rate-idr">{{ "IDR ". number_format($usdrates->rate * $price->markup, 0, ",", ".") }}</div>
                                                                    </div>
                                                                    <div class="col-12  m-b-8">
                                                                        <p><b>Kick Back :</b></p>
                                                                        @if ($price->kick_back > 0)
                                                                            <div class="rate-kick-back">{{ "$ ".number_format($price->kick_back, 0, ",", ".") }}</div>
                                                                            <div class="rate-idr">{{ "IDR ". number_format($usdrates->rate * $price->kick_back, 0, ",", ".") }}</div>
                                                                        @else
                                                                            <div class="rate-usd">-</div>
                                                                            <div class="rate-idr">-</div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-12  m-b-8">
                                                                        <p><b>Tax :</b></p>
                                                                        @if ($tax_normal_price > 0)
                                                                            <div class="rate-usd">{{ "$ ".number_format($tax_normal_price, 0, ",", ".") }}</div>
                                                                            <div class="rate-idr">{{ "IDR ". number_format($usdrates->rate * $tax_normal_price, 0, ",", ".") }}</div>
                                                                        @else
                                                                            <div class="rate-usd">-</div>
                                                                            <div class="rate-idr">-</div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <hr class="form-hr">
                                                            </div>
                                                            @if ($price->kick_back >0)
                                                                <div class="col-12  m-b-8">
                                                                    <div class="row">
                                                                        <div class="col-6  m-b-8">
                                                                            <p><b>Published Rate :</b></p>
                                                                            <div class="price-usd">
                                                                                <strike>{{ "$ ".number_format($final_normal_price, 0, ",", ".") }}</strike>
                                                                            </div>
                                                                            <div class="price-idr">
                                                                                <strike>{{ "$ ".number_format($final_normal_price* $usdrates->rate, 0, ",", ".") }}</strike>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6  m-b-8">
                                                                            <p><b>Published With Kick Back Rate :</b></p>
                                                                            <div class="price-usd">
                                                                                {{ "$ ".number_format($harga_kick_back, 0, ",", ".") }}
                                                                            </div>
                                                                            <div class="price-idr">
                                                                                {{ "$ ".number_format($harga_kick_back*$usdrates->rate, 0, ",", ".") }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="col-12 m-b-8">
                                                                    <p><b>Published Rate :</b></p>
                                                                    <div class="price-usd">
                                                                        {{ "$ ".number_format($final_normal_price, 0, ",", ".") }}
                                                                    </div>
                                                                    <div class="price-idr">
                                                                        {{ "$ ".number_format($final_normal_price * $usdrates->rate, 0, ",", ".") }}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="card-box-footer">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @canany(['posDev','posAuthor'])
                                            {{-- MODAL PRICE EDIT ===========================================================================================================================================================--}}
                                            <div class="modal fade" id="edit-price-{{ $price->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="card-box">
                                                            <div class="card-box-title">
                                                                <div class="title"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Price Edit</div>
                                                            </div>
                                                    
                                                            <form id="update-price-{{ $price->id }}" action="/fedit-price-{{ $price->id }}" method="post" enctype="multipart/form-data">
                                                                @method('put')
                                                                {{ csrf_field() }}
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label for="rooms_id">Room </label>
                                                                                    <select id="rooms_id" name="rooms_id" class="custom-select @error('rooms_id') is-invalid @enderror" required>
                                                                                        <option selected value="{{ $room->id }}">{{ $room->rooms }}</option>
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
                                                                                    <label for="start_date">Starting </label>
                                                                                    <input name="start_date" id="start_date" wire:model="start_date" class="form-control date-picker @error('start_date') is-invalid @enderror" placeholder="Select Date and Time" type="text" value="{{ dateFormat($price->start_date) }}" required>
                                                                                    @error('start_date')
                                                                                        <span class="invalid-feedback">
                                                                                            <strong>{{ $message }}</strong>
                                                                                        </span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="end_date">Ending </label>
                                                                                    <input name="end_date" id="end_date" wire:model="end_date" class="form-control date-picker @error('end_date') is-invalid @enderror" placeholder="Select Date and Time" type="text" value="{{ dateFormat($price->end_date) }}" required>
                                                                                    @error('end_date')
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
                                                                                        <input type="number" id="contract_rate" name="contract_rate" class="input-icon form-control @error('contract_rate') is-invalid @enderror" placeholder="Insert Markup" value="{{ $price->contract_rate }}" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="markup">Markup </label>
                                                                                    <div class="btn-icon">
                                                                                        <span>$</span>
                                                                                        <input type="number" id="markup" name="markup" class="input-icon form-control @error('markup') is-invalid @enderror" placeholder="Insert Markup" value="{{ $price->markup }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="kick_back">Kick Back</label>
                                                                                    <div class="btn-icon">
                                                                                        <span>$</span>
                                                                                        <input type="number" id="kick_back" name="kick_back" class="input-icon form-control @error('kick_back') is-invalid @enderror" placeholder="Insert kick back" value="{{ $price->kick_back }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                                                    <input id="hotels_id" name="hotels_id" value="{{ $hotel->id }}" type="hidden">
                                                                </div>
                                                            </form>
                                                            <div class="card-box-footer">
                                                                <button type="submit" form="update-price-{{ $price->id }}" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Update</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endcanany
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="notif">!!! The hotel doesn't have a price yet, please add a price now!</div>
            @endif
            @canany(['posDev','posAuthor'])
                <div class="card-box-footer">
                    <a href="/add-hotel-price-{{ $hotel->id }}" data-toggle="tooltip" data-placement="top" title="Add Normal Price">
                        <button type="button" class="btn btn-primary btn-sm"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Price</button>
                    </a>
                </div>
            @endcanany
        </div>
    </div>
</div>