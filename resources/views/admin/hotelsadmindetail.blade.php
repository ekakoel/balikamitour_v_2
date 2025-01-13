@php
    use Carbon\Carbon;
@endphp
@section('content')
@section('title','Hotel Details')
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    @can('isAdmin')
        <div class="main-container">
            <div class="pd-ltr-20">
                <div class="min-height-200px">
                    <div class="page-header">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="title"><i class="micon fa fa-building" aria-hidden="true"></i> Hotel</div>
                                <nav aria-label="breadcrumb" role="navigation">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/admin-panel">Admin Panel</a></li>
                                        <li class="breadcrumb-item"><a href="/hotels-admin">Hotels</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $hotel->name }}</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
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
                    <div class="row">
                        {{-- ATTENTIONS MOBILE --}}
                        <div class="col-md-4 mobile">
                            <div class="row">
                                @include('admin.usd-rate')
                                @include('layouts.attentions')
                                <div class="col-md-12">
                                    <div class="card-box">
                                        <div class="card-box-title">
                                            <div class="subtitle"><i class="icon-copy ion-ios-pulse-strong"></i> Log</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <p><b>{{ $hotel->name }}</b></p>
                                            </div>
                                            <div class="col-6">
                                                <p><b>{{ dateTimeFormat($hotel->created_at) }}</b></p>
                                            </div>
                                            <div class="col-12">
                                                <hr class="form-hr">
                                            </div>
                                            <div class="col-6">
                                                <p><b>Author :</b> {{ $author->name }}</p>
                                            </div>
                                            <div class="col-6 text-right">
                                                <p><i>{{ Carbon::parse($hotel->created_at)->diffForHumans();  }}</i></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p><b>Rooms :</b> {{ count($rooms)." Type" }}</p>
                                                @php
                                                    $last_price = $prices->where('end_date','>', $now);
                                                    $clp = count($last_price);
                                                    $end_date = $now;
                                                    $hi = $now;
                                                @endphp
                                                @foreach ($prices as $lprices)
                                                
                                                    @php
                                                        $ed = $lprices->end_date;
                                                    @endphp
                                                    @if ($ed > $hi)
                                                        @php
                                                            $end_date = $ed;
                                                            $hi = $ed;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                @if ($end_date > date('Y-m-d',strtotime($now)))
                                                    <p><b>Last Price :</b> {{ dateFormat($end_date) }}</p>
                                                @else
                                                    <p style="color:red;">Expired</p>
                                                @endif
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            {{-- HOTEL DETAIL --}}
                            <div class="card-box">
                                <div class="card-box-title">
                                    <div class="subtitle"><i class="fa fa-file-text" aria-hidden="true"></i> {{ $hotel->name }}</div>
                                </div>
                                <div class="page-card">
                                    <div class="card-banner">
                                        <img src="{{ asset ('storage/hotels/hotels-cover/' . $hotel->cover) }}" alt="{{ $hotel->name }}" loading="lazy">
                                    </div>
                                    <div class="card-content">
                                        <div class="status-card p-t-8">
                                            @if ($hotel->status == "Rejected")
                                                <div class="status-rejected"></div>
                                            @elseif ($hotel->status == "Invalid")
                                                <div class="status-invalid"></div>
                                            @elseif ($hotel->status == "Active")
                                                <div class="status-active"></div>
                                            @elseif ($hotel->status == "Waiting")
                                                <div class="status-waiting"></div>
                                            @elseif ($hotel->status == "Draft")
                                                <div class="status-draft"></div>
                                            @elseif ($hotel->status == "Archived")
                                                <div class="status-archived"></div>
                                            @else
                                            @endif
                                        </div>
                                        <div class="data-web">{{ $hotel->web }}</div>
                                        <hr class="form-hr">
                                        <div class="card-text">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="card-subtitle">Contact Person:</div>
                                                    <p>{{ $hotel->contact_person }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <div class="card-subtitle">Phone:</div>
                                                    <p>{{ $hotel->phone }}</p>
                                                </div>
                                                <div class="col-12">
                                                    <hr class="form-hr">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="card-subtitle">Address:</div>
                                                    <p>{{ $hotel->address }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <div class="card-subtitle">Region:</div>
                                                    <a target="__blank" href="{{ $hotel->map }}">
                                                        <p class="text"><i class="icon-copy fa fa-map-marker" aria-hidden="true"></i>{{ " ". $hotel->region }}</p>
                                                    </a>
                                                </div>
                                                <div class="col-12">
                                                    <hr class="form-hr">
                                                </div>
                                                <div class="col-6">
                                                    <div class="card-subtitle">Min Stay:</div>
                                                    <p>{{ $hotel->min_stay." nights" }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <div class="card-subtitle">Max Stay:</div>
                                                    <p>{{ $hotel->max_stay." nights" }}</p>
                                                </div>
                                            </div>
                                            @if (isset($hotel->check_in_time) and isset($hotel->check_out_time))
                                                <hr class="form-hr">
                                                <div class="row">
                                                    @if (isset($hotel->check_out_time))
                                                        <div class="col-6">
                                                            <div class="card-subtitle">Check-out:</div>
                                                            <p><i class="fa fa-clock-o" aria-hidden="true"></i> {{ date('H.i',strtotime($hotel->check_out_time)) }}</p>
                                                        </div>
                                                    @endif
                                                    @if (isset($hotel->check_in_time))
                                                        <div class="col-6">
                                                            <div class="card-subtitle">Check-in:</div>
                                                            <p><i class="fa fa-clock-o" aria-hidden="true"></i> {{ date('H.i',strtotime($hotel->check_in_time)) }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                            @if (isset($hotel->airport_distance) and isset($hotel->airport_duration))
                                                <hr class="form-hr">
                                                <div class="row">
                                                    @if (isset($hotel->airport_distance))
                                                        <div class="col-6">
                                                            <div class="card-subtitle">Airport Distance:</div>
                                                            <p><i class="fa fa-map" aria-hidden="true"></i> {{ $hotel->airport_distance." Km" }}</p>
                                                        </div>
                                                    @endif
                                                    @if (isset($hotel->airport_duration))
                                                        <div class="col-6">
                                                            <div class="card-subtitle">Airport Duration:</div>
                                                            <p><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $hotel->airport_duration." Hours" }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <hr class="form-hr">
                                    </div>
                                </div>
                                <div class="card-text">
                                    <div class="card-subtitle">Description:</div>
                                    <p>{!! $hotel->description !!}</p>
                                </div>
                                @if ($hotel->facility != "")
                                    <div class="card-text">
                                        <div class="card-subtitle">Facility:</div>
                                        <p>{!! $hotel->facility !!}</p>
                                    </div>
                                @endif
                                @if ($hotel->benefits != "")
                                    <div class="card-text">
                                        <div class="card-subtitle">Benefits:</div>
                                        <p>{!! $hotel->benefits !!}</p>
                                    </div>
                                @endif
                                @if ($hotel->optional_rate != "")
                                    <div class="card-text">
                                        <div class="card-subtitle">Additional Charge:</div>
                                        <p>{!! $hotel->optional_rate !!}</p>
                                    </div>
                                @endif
                                @if ($hotel->additional_info != "")
                                    <div class="card-text">
                                        <div class="card-subtitle">Additional Information:</div>
                                        {!! $hotel->additional_info !!}
                                    </div>
                                @endif
                                @if ($hotel->cancellation_policy != "")
                                    <div class="card-text">
                                        <div class="card-subtitle">Cancellation Policy:</div>
                                        <p>{!! $hotel->cancellation_policy !!}</p>
                                    </div>
                                @endif
                                @canany(['posDev','posAuthor'])
                                    <div class="card-box-footer">
                                        <a href="edit-hotel-{{ $hotel->id }}">
                                            <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit Hotel</button>
                                        </a>
                                    </div>
                                @endcanany
                            </div>
                            {{-- CONTRACT --}}
                            <div class="card-box">
                                <div class="card-box-title">
                                    <div class="subtitle"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Contract</div>
                                </div>
                                <div class="card-box-content">
                                    @if (count($contracts) > 0)
                                        @foreach ($contracts->where('hotels_id',$hotel->id) as $contract)
                                            <div class="card-contract p-8">
                                                <div class="card-subtitle"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> {{ $contract->name }}</div>
                                                <p><i>{{ dateFormat($contract->period_start).' - '.dateFormat($contract->period_end) }}</i></p>
                                                <hr class="form-hr">
                                                <div class="card-action text-right">
                                                    <a class="action-btn" href="modal" data-toggle="modal" data-target="#contract-{{ $contract->id }}">
                                                        <i class="icon-copy fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                    @canany(['posDev','posAuthor'])
                                                        <a class="action-btn" href="modal" data-toggle="modal" data-target="#edit-contract-{{ $contract->id }}">
                                                            <i class="icon-copy fa fa-pencil" aria-hidden="true"></i>
                                                        </a>
                                                        <a class="action-btn" href="modal" data-toggle="modal" data-target="#delete-contract-{{ $contract->id }}">
                                                            <i class="icon-copy fa fa-trash" aria-hidden="true"></i>
                                                        </a>
                                                    @endcanany
                                                </div>
                                                {{-- MODAL VIEW CONTRACT --}}
                                                <div class="modal fade" id="contract-{{ $contract->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content" style="padding: 0; background-color:transparent; border:none;">
                                                            <div class="modal-body pd-5">
                                                                <embed src="storage/hotels/hotels-contract/{{ $contract->file_name }}" frameborder="10" width="100%" height="850px">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @canany(['posDev','posAuthor'])
                                                    {{-- MODAL DELETE CONTRACT --}}
                                                    <div class="modal fade" id="delete-contract-{{ $contract->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="card-box">
                                                                    <div class="card-box-title">
                                                                        <div class="title"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Delete Contract</div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <p><b>File Name:</b></p>
                                                                            <p>{{ $contract->file_name }}</p>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <p><b>Contract Name:</b></p>
                                                                            <p>{{ $contract->name }}</p>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <p><b>Period Start:</b></p>
                                                                            <p>{{ dateFormat($contract->period_start) }}</p>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <p><b>Period End:</b></p>
                                                                            <p>{{ dateFormat($contract->period_end) }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-box-footer">
                                                                        <form action="/fdelete-contract/{{ $contract->id }}" method="post">
                                                                            @csrf
                                                                            @method('delete')
                                                                            <input type="hidden" name="file_name" value="{{ $contract->file_name }}">
                                                                            <input type="hidden" name="author" value="{{ Auth::user()->id }}">
                                                                            <input type="hidden" name="hotels_id" value="{{ $hotel->id }}">
                                                                            <button class="btn btn-danger" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="top" title="Delete"><i class="icon-copy fa fa-trash"></i> Delete</button>
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- MODAL EDIT CONTRACT --}}
                                                    <div class="modal fade" id="edit-contract-{{ $contract->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="card-box">
                                                                    <div class="card-box-title">
                                                                        <div class="title"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit Contract</div>
                                                                    </div>
                                                                
                                                                    <form id="update-hotel-contract-{{ $contract->id }}" action="/fupdate-hotel-contract/{{ $contract->id }}" method="post" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('put')
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="file_name"><i class="icon-copy fa fa-file-pdf-o" aria-hidden="true"></i> {{ $contract->file_name }}</label>
                                                                                    <input type="file" name="file_name" id="file_name" class="custom-file-input @error('file_name') is-invalid @enderror" placeholder="Choose Contract">
                                                                                    @error('file_name')
                                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="contract_name">Contract Name</label>
                                                                                    <input name="contract_name" id="contract_name"  type="text" class="form-control @error('contract_name') is-invalid @enderror" placeholder="Insert contract name" value="{{ $contract->name }}">
                                                                                    @error('contract_name')
                                                                                        <span class="invalid-feedback">
                                                                                            <strong>{{ $message }}</strong>
                                                                                        </span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="period_start">Period Start</label>
                                                                                    <input readonly name="period_start" id="period_start"  type="text" class="form-control date-picker @error('period_start') is-invalid @enderror" placeholder="Insert contract name" value="{{ dateFormat($contract->period_start) }}">
                                                                                    @error('period_start')
                                                                                        <span class="invalid-feedback">
                                                                                            <strong>{{ $message }}</strong>
                                                                                        </span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="period_end">Period End</label>
                                                                                    <input readonly name="period_end" id="period_end"  type="text" class="form-control date-picker @error('period_end') is-invalid @enderror" placeholder="Select date" value="{{ dateFormat($contract->period_end) }}">
                                                                                    @error('period_end')
                                                                                        <span class="invalid-feedback">
                                                                                            <strong>{{ $message }}</strong>
                                                                                        </span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            <input name="hotels_id" value="{{ $hotel->id }}" type="hidden">
                                                                            <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                                                        </div>
                                                                    </form>
                                                                    <div class="card-box-footer">
                                                                        <button type="submit" form="update-hotel-contract-{{ $contract->id }}" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Update</button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endcanany
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="notif">Contract not available</div>
                                    @endif
                                </div>
                                @canany(['posDev','posAuthor'])
                                    <div class="card-box-footer">
                                        <a href="modal" data-toggle="modal" data-target="#add-contract-{{ $hotel->id }}" data-toggle="tooltip" data-placement="top" title="Add more contract">
                                            <button type="button" class="btn btn-primary btn-sm"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Contract</button>
                                        </a>
                                    </div>
                                    {{-- MODAL ADD Contract =====================================================================================================================--}}
                                    <div class="modal fade" id="add-contract-{{ $hotel->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="card-box">
                                                    <div class="card-box-title">
                                                        <div class="title"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Contract</div>
                                                    </div>
                                                
                                                    <form id="addContract" action="/fadd-hotel-contract" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        {{ csrf_field() }}
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="file_name">Contract PDF File</label>
                                                                    <input type="file" name="file_name" id="file_name" class="custom-file-input @error('file_name') is-invalid @enderror" placeholder="Choose Contract" value="{{ old('file_name') }}" required>
                                                                    @error('file_name')
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="contract_name">Contract Name</label>
                                                                    <input name="contract_name" id="contract_name"  type="text" class="form-control @error('contract_name') is-invalid @enderror" placeholder="Insert contract name" value="{{ old('contract_name') }}" required>
                                                                    @error('contract_name')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="period_start" >Period Start</label>
                                                                    <input readonly name="period_start" id="period_start"  type="text" class="form-control date-picker @error('period_start') is-invalid @enderror" placeholder="Select Date" value="{{ old('period_start') }}" required>
                                                                    @error('period_start')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="period_end">Period End</label>
                                                                    <input name="period_end" id="period_end"  type="text" class="form-control date-picker @error('period_end') is-invalid @enderror" placeholder="Select date" value="{{ old('period_end') }}" required>
                                                                    @error('period_end')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            
                                                            <input name="hotels_id" value="{{ $hotel->id }}" type="hidden">
                                                            <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                                        </div>
                                                    </form>
                                                    <div class="card-box-footer">
                                                        <button type="submit" form="addContract" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Add</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcanany
                            </div>
                        </div>
                        {{-- ATTENTIONS DESKTOP --}}
                        <div class="col-md-4 desktop">
                            <div class="row">
                                @include('admin.usd-rate')
                                @include('layouts.attentions')
                                <div class="col-md-12">
                                    <div class="card-box">
                                        <div class="card-box-title">
                                            <div class="subtitle"><i class="icon-copy ion-ios-pulse-strong"></i> Log</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <p><b>{{ $hotel->name }}</b></p>
                                            </div>
                                            <div class="col-6">
                                                <p><b>{{ dateTimeFormat($hotel->created_at) }}</b></p>
                                            </div>
                                            <div class="col-12">
                                                <hr class="form-hr">
                                            </div>
                                            <div class="col-6">
                                                <p><b>Author :</b> {{ $author->name }}</p>
                                            </div>
                                            <div class="col-6 text-right">
                                                <p><i>{{ Carbon::parse($hotel->created_at)->diffForHumans();  }}</i></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p><b>Rooms :</b> {{ count($rooms)." Type" }}</p>
                                                @php
                                                    $last_price = $prices->where('end_date','>', $now);
                                                    $clp = count($last_price);
                                                    $end_date = $now;
                                                    $hi = $now;
                                                @endphp
                                                @foreach ($prices as $lprices)
                                                
                                                    @php
                                                        $ed = $lprices->end_date;
                                                    @endphp
                                                    @if ($ed > $hi)
                                                        @php
                                                            $end_date = $ed;
                                                            $hi = $ed;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                @if ($end_date > date('Y-m-d',strtotime($now)))
                                                    <p><b>Last Price :</b> {{ dateFormat($end_date) }}</p>
                                                @else
                                                    <p style="color:red;">Expired</p>
                                                @endif
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ROOM --}}
                    <div id="rooms" class="product-wrap">
                        <div class="product-detail-wrap">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card-box">
                                        <div class="card-box-title">
                                            <div class="subtitle"><i class="fa fa-bed" aria-hidden="true"></i> Suites & Villas</div>
                                        </div>
                                        @if (count($hotel->rooms) > 0)
                                            <div class="card-box-content">
                                                @foreach ($hotel->rooms as $room)
                                                    <div class="card">
                                                        <a href="modal" data-toggle="modal" data-target="#detail-room-{{ $room->id }}">
                                                            <div class="card-image-container">
                                                                <div class="card-status">
                                                                    @if ($room->status == "Rejected")
                                                                        <div class="status-rejected"></div>
                                                                    @elseif ($room->status == "Invalid")
                                                                        <div class="status-invalid"></div>
                                                                    @elseif ($room->status == "Active")
                                                                        <div class="status-active"></div>
                                                                    @elseif ($room->status == "Waiting")
                                                                        <div class="status-waiting"></div>
                                                                    @elseif ($room->status == "Draft")
                                                                        <div class="status-draft"></div>
                                                                    @elseif ($room->status == "Archived")
                                                                        <div class="status-archived"></div>
                                                                    @else
                                                                    @endif
                                                                </div>
                                                                @if ($room->status == "Draft")
                                                                    <img class="img-fluid rounded thumbnail-image grayscale" src="{{ url('storage/hotels/hotels-room/' . $room->cover) }}" alt="{{ $room->rooms }}">
                                                                @else
                                                                    <img class="img-fluid rounded thumbnail-image" src="{{ url('storage/hotels/hotels-room/' . $room->cover) }}" alt="{{ $room->rooms }}">
                                                                @endif
                                                                <div class="name-card">
                                                                    <p>
                                                                        {{ $room->rooms }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        @canany(['posDev','posAuthor'])
                                                            <div class="card-btn-container">
                                                                <a href="/edit-room-{{ $room->id }}">
                                                                    <button class="btn-update" data-toggle="tooltip" data-placement="top" title="Update"><i class="icon-copy fa fa-pencil"></i></button><br>
                                                                    {{-- <button type="button" class="btn btn-update"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i></button> --}}
                                                                </a>
                                                                <form action="/delete-room/{{ $room->id }}" method="post">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                                                    <input id="hotels_id" name="hotels_id" value="{{ $hotel->id }}" type="hidden">
                                                                    <button class="btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="top" title="Delete"><i class="icon-copy fa fa-trash"></i></button>
                                                                </form>
                                                            </div>
                                                        @endcanany
                                                    </div>
                                                    {{-- MODAL ROOM DETAIL --}}
                                                    <div class="modal fade" id="detail-room-{{ $room->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="card-box">
                                                                    <div class="card-box-title">
                                                                        <div class="title"><i class="fa fa-bed" aria-hidden="true"></i> {{ $room->rooms }}</div>
                                                                        <div class="status-card">
                                                                            @if ($room->status == "Rejected")
                                                                                <div class="status-rejected"></div>
                                                                            @elseif ($room->status == "Invalid")
                                                                                <div class="status-invalid"></div>
                                                                            @elseif ($room->status == "Active")
                                                                                <div class="status-active"></div>
                                                                            @elseif ($room->status == "Waiting")
                                                                                <div class="status-waiting"></div>
                                                                            @elseif ($room->status == "Draft")
                                                                                <div class="status-draft"></div>
                                                                            @elseif ($room->status == "Archived")
                                                                                <div class="status-archived"></div>
                                                                            @else
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="page-card">
                                                                                <div class="card-banner">
                                                                                    <img src="{{ asset ('storage/hotels/hotels-room/' . $room->cover) }}" alt="{{ $room->rooms }}" loading="lazy">
                                                                                </div>
                                                                                <div class="card-content">
                                                                                    <div class="card-text">
                                                                                        <div class="row ">
                                                                                            <div class="col-12 col-sm-12">
                                                                                                <div class="card-subtitle">Capacity:</div>
                                                                                                <p>{{ $room->capacity. " Guest" }}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    @if ($room->include != "")
                                                                                        <hr class="form-hr">
                                                                                        <div class="card-text">
                                                                                            <div class="row ">
                                                                                                <div class="col-12 col-sm-12">
                                                                                                    <div class="card-subtitle">Include:</div>
                                                                                                    <p>{!! $room->include !!}</p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                    @if ($room->additional_info != "")
                                                                                        <hr class="form-hr">
                                                                                        <div class="card-text">
                                                                                            <div class="row ">
                                                                                                <div class="col-12 col-sm-12">
                                                                                                    <div class="card-subtitle">additional_info:</div>
                                                                                                    <p>{!! $room->additional_info !!}</p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-box-footer">
                                                                        @canany(['posDev','posAuthor'])
                                                                            <a href="/edit-room-{{ $room->id }}">
                                                                                <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                                                            </a>
                                                                        @endcanany
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="notif">!!! The hotel doesn't have a rooms yet, please add a rooms now!</div>
                                        @endif
                                        @canany(['posDev','posAuthor'])
                                            <div class="card-box-footer">
                                                <a href="add-room-{{ $hotel->id }}">
                                                    <button type="button" class="btn btn-primary btn-sm"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Rooms</button>
                                                </a>
                                            </div>
                                        @endcanany
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (count($hotel->rooms) > 0)
                        {{-- EXTRA BED ------------------------------------------------------------------------------------------------------- --}}
                        @include('admin.extrabed')
                        {{-- OPTIONAL RATE --------------------------------------------------------------------------------------------------- --}} 
                        @include('admin.optional-rate')
                        {{-- PRICE ----------------------------------------------------------------------------------------------------------- --}}
                        @include('admin.hotel-normal-price')
                        {{-- PROMO ----------------------------------------------------------------------------------------------------------- --}} 
                        @include('admin.hotel-promo-price')
                        {{-- PACKAGE --------------------------------------------------------------------------------------------------------- --}} 
                        @include('admin.hotel-package-price')
                    @endif
                </div>
                @include('layouts.footer')
            </div>
        </div>
    @endcan
@endsection
<script>
    function searchPriceByRoom() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchPriceByRoom");
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
<script>
    function searchPromoByName() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchPromoByName");
        filter = input.value.toUpperCase();
        table = document.getElementById("tbPromo");
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
<script>
    function searchPackageByName() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchPackageByName");
        filter = input.value.toUpperCase();
        table = document.getElementById("tbPackage");
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
