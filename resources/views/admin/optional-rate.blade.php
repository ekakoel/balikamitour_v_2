<div id="optional-rate" class="row">
    <div class="col-md-8">
        <div class="card-box">
            <div class="card-box-title">
                <div class="subtitle"><i class="fa fa-asterisk" aria-hidden="true"></i> Additional Charge</div>
            </div>
            @if (count($optional_rate) > 0)
                <table id="tbOptionalrate" class="data-table table stripe nowrap" >
                    <thead>
                        <tr>
                            <th style="width: 10%;">Name</th>
                            <th style="width: 5%;">Contract Rate</th>
                            <th style="width: 5%;">Markup</th>
                            <th style="width: 5%;">Tax {{ "(".$taxes->tax."%)" }}</th>
                            <th style="width: 10%;">Published Rate</th>
                            @canany(['posDev','posAuthor'])
                                <th class="datatable-nosort text-center" style="width: 5%;">Action</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($optional_rate as $optionalrate)
                            @php
                                $usdrates_optional_rate = ceil($optionalrate->contract_rate / $usdrates->rate);
                                $tax_optional_rate = $taxes->tax / 100;
                                $pajak_optional_rate = ceil(($usdrates_optional_rate + $optionalrate->markup)*$tax_optional_rate);
                            @endphp
                            <tr>
                                <td>
                                    <b>{{ $optionalrate->type }}</b>
                                    <p>{{ $optionalrate->name }}</p>
                                </td>
                                <td>
                                    <div class="rate-usd"> {{ "$ ". number_format($usdrates_optional_rate, 0, ",", ".") }}</div>
                                    <div class="rate-idr"> {{ "IDR ". number_format($optionalrate->contract_rate, 0, ",", ".") }}</div>
                                </td>
                                <td>
                                    <div class="rate-usd"> {{ "$ ".number_format($optionalrate->markup, 0,",",".") }}</div>
                                    <div class="rate-idr"> {{ "IDR ".number_format($optionalrate->markup * $usdrates->rate, 0,",",".") }}</div>
                                </td>
                                <td>
                                    <div class="rate-usd"> {{ "$ ".$pajak_optional_rate }}</div>
                                    <div class="rate-idr"> {{ "IDR ".number_format($pajak_optional_rate * $usdrates->rate, 0,",",".") }}</div>
                                </td>
                                <td>
                                    <div class="rate-usd">{{ "$ ". number_format($usdrates_optional_rate + $optionalrate->markup + $pajak_optional_rate, 0, ",", ".") }}</div>
                                    <div class="rate-idr">{{ "IDR ".number_format(($usdrates_optional_rate + $optionalrate->markup + $pajak_optional_rate) * $usdrates->rate, 0, ",", ".") }}</div>
                                </td>
                                @canany(['posDev','posAuthor'])
                                    <td class="text-right">
                                        <div class="table-action">
                                            <a href="modal" data-toggle="modal" data-target="#edit-optionalrate-{{ $optionalrate->id }}">
                                                <button class="btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-copy fa fa-edit"></i></button>
                                            </a>
                                            <form action="/fdelete-optionalrate/{{ $optionalrate->id }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <input type="hidden" name="author" value="{{ Auth::user()->id }}">
                                                <input type="hidden" name="hotels_id" value="{{ $hotel->id }}">
                                                <button class="btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="top" title="Delete"><i class="icon-copy fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                @endcanany
                            </tr>
                            @canany(['posDev','posAuthor'])
                                {{-- MODAL OPTIONAL RATE EDIT --}}
                                <div class="modal fade" id="edit-optionalrate-{{ $optionalrate->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="card-box">
                                                <div class="card-box-title">
                                                    <div class="title"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Additional Charge Edit</div>
                                                </div>
                                                <form id="edit-optional-rate-{{ $optionalrate->id }}" action="/fupdate-optionalrate/{{ $optionalrate->id }}" method="post" enctype="multipart/form-data">
                                                    @method('put')
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="type">Type </label>
                                                                <input name="type" id="type" wire:model="type" class="form-control @error('type') is-invalid @enderror" placeholder="Select Date and Time" type="text" value="{{ $optionalrate->type }}" required>
                                                                @error('type')
                                                                    <span class="invalid-feedback">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="name">Name </label>
                                                                <input name="name" id="name" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Select Date and Time" type="text" value="{{ $optionalrate->name }}" required>
                                                                @error('name')
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
                                                                    <input type="number" id="contract_rate" name="contract_rate" class="input-icon form-control @error('contract_rate') is-invalid @enderror" placeholder="Insert Markup" value="{{ $optionalrate->contract_rate }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="markup">Markup <span> *</span></label>
                                                                <div class="btn-icon">
                                                                    <span>$</span>
                                                                    <input type="number" id="markup" name="markup" class="input-icon form-control @error('markup') is-invalid @enderror" placeholder="Insert Markup" value="{{ $optionalrate->markup }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="description">Description </label>
                                                                <textarea name="description" id="edit_desc_a_c" wire:model="description" class="ckeditor form-control @error('description') is-invalid @enderror" placeholder="Select Date and Time" type="text" required>{!! $optionalrate->description !!}</textarea>
                                                                @error('description')
                                                                    <span class="invalid-feedback">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                                        <input id="service_id" name="service_id" value="{{ $hotel->id }}" type="hidden">
                                                    </div>
                                                </form>
                                                <div class="card-box-footer">
                                                    <button type="submit" form="edit-optional-rate-{{ $optionalrate->id }}" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Update</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcanany
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="notif">Optional rates not found!</div>
            @endif
            @canany(['posDev','posAuthor'])
                <div class="card-box-footer">
                    <a href="modal" data-toggle="modal" data-target="#add-optionalrate-{{ $hotel->id }}" data-toggle="tooltip" data-placement="top" title="Detail">
                        <button type="button" class="btn btn-primary btn-sm"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Additional Charge</button>
                    </a>
                </div>
                {{-- MODAL ADD OPTIONAL RATE --}}
                <div class="modal fade" id="add-optionalrate-{{ $hotel->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="card-box">
                                <div class="card-box-title">
                                    <div class="title"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Additional Charge</div>
                                </div>
                                <form id="add-optional-rate" action="/fadd-optionalrate" method="post" enctype="multipart/form-data">
                                    @csrf
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="type">Type </label>
                                                        <input name="type" id="type"  type="text" wire:model="type" class="form-control @error('type') is-invalid @enderror" placeholder="Insert type" value="{{ old('type') }}" required>
                                                        @error('type')
                                                            <span class="invalid-feedback">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name">Name </label>
                                                        <input name="name" id="name"  type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Insert name" value="{{ old('name') }}" required>
                                                        @error('name')
                                                            <span class="invalid-feedback">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contract_rate">Contract Rate<span> *</span></label>
                                                        <div class="btn-icon">
                                                            <span>Rp</span>
                                                            <input type="number" id="contract_rate" name="contract_rate" class="input-icon form-control @error('contract_rate') is-invalid @enderror" placeholder="Insert contract rate" value="{{ old('contract_rate') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="markup">Markup<span> *</span></label>
                                                        <div class="btn-icon">
                                                            <span>$</span>
                                                            <input type="number" id="markup" name="markup" class="input-icon form-control @error('markup') is-invalid @enderror" placeholder="Insert markup" value="{{ old('markup') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="description">Description </label>
                                                            <textarea name="description" id="add_desc_a_c" wire:model="description" class="ckeditor form-control @error('description') is-invalid @enderror" placeholder="Select Date and Time" type="text" required>{!! old('description') !!}</textarea>
                                                        @error('description')
                                                            <span class="invalid-feedback">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input name="hotels_id" value="{{ $hotel->id }}" type="hidden">
                                        <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                        <input id="service_id" name="service_id" value="{{ $hotel->id }}" type="hidden">
                                    </div>
                                </form>
                                <div class="card-box-footer">
                                    <button type="submit" form="add-optional-rate" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Add Price</button>
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