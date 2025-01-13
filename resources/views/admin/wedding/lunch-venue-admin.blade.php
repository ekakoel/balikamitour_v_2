{{-- LUNCH VENUE --}}
<div id="lunchVenue" class="card-box">
    <div class="card-box-title">
        <div class="subtitle"><i class="icon-copy dw dw-food-cart"></i> Lunch Venue </div>
    </div>
    @if (count($lunchVenues)>0)
        <div class="card-box-content m-b-8">
            @foreach ($lunchVenues as $lunch_venue)
                <div class="card">
                    <a href="modal" data-toggle="modal" data-target="#detail-lunch-venue-{{ $lunch_venue->id }}">
                        <div class="card-image-container">
                            <div class="card-status">
                                @if ($lunch_venue->status == "Rejected")
                                    <div class="status-rejected"></div>
                                @elseif ($lunch_venue->status == "Invalid")
                                    <div class="status-invalid"></div>
                                @elseif ($lunch_venue->status == "Active")
                                    <div class="status-active"></div>
                                @elseif ($lunch_venue->status == "Waiting")
                                    <div class="status-waiting"></div>
                                @elseif ($lunch_venue->status == "Draft")
                                    <div class="status-draft"></div>
                                @elseif ($lunch_venue->status == "Archived")
                                    <div class="status-archived"></div>
                                @else
                                @endif
                            </div>
                            @if ($lunch_venue->status == "Draft")
                                <img class="img-fluid rounded thumbnail-image grayscale" src="{{ url('storage/weddings/lunch-venues/' . $lunch_venue->cover) }}" alt="{{ $lunch_venue->name }}">
                            @else
                                <img class="img-fluid rounded thumbnail-image" src="{{ url('storage/weddings/lunch-venues/' . $lunch_venue->cover) }}" alt="{{ $lunch_venue->name }}">
                            @endif
                            <div class="card-price-container">
                                <div class="card-price-bl">
                                    Min: {{ $lunch_venue->min_capacity }}<br>
                                    Max: {{ $lunch_venue->max_capacity }}
                                </div>
                                <div class="card-price-br">
                                    {{ '$ ' . number_format($lunch_venue->publish_rate, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="name-card">
                                <p>
                                    <b>{{ $lunch_venue->name }}</b><br>
                                </p>
                            </div>
                        </div>
                    </a>
                    @canany(['posDev','posAuthor'])
                        <div class="card-btn-container">
                            @if ($lunch_venue->status == "Draft")
                                <a href="/update-lunch-venue-{{ $lunch_venue->id }}">
                                    <button class="btn-update" data-toggle="tooltip" data-placement="top" title="Update"><i class="icon-copy fa fa-pencil"></i></button><br>
                                </a>
                            @endif
                            <form action="/fdelete-lunch-venue/{{ $lunch_venue->id }}" method="post">
                                @csrf
                                @method('delete')
                                <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                <input id="hotels_id" name="hotels_id" value="{{ $hotel->id }}" type="hidden">
                                <button class="btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="top" title="Delete"><i class="icon-copy fa fa-trash"></i></button>
                            </form>
                        </div>
                    @endcanany
                </div>
                {{-- MODAL LUNCH VENUE DETAIL --}}
                <div class="modal fade" id="detail-lunch-venue-{{ $lunch_venue->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="card-box">
                                <div class="card-box-title">
                                    <div class="title"><i class="icon-copy dw dw-food-cart"></i></i> {{ $lunch_venue->name }}</div>
                                    <div class="status-card m-t-8">
                                        @if ($lunch_venue->status == "Rejected")
                                            <div class="status-rejected"></div>
                                        @elseif ($lunch_venue->status == "Invalid")
                                            <div class="status-invalid"></div>
                                        @elseif ($lunch_venue->status == "Active")
                                            <div class="status-active"></div>
                                        @elseif ($lunch_venue->status == "Waiting")
                                            <div class="status-waiting"></div>
                                        @elseif ($lunch_venue->status == "Draft")
                                            <div class="status-draft"></div>
                                        @elseif ($lunch_venue->status == "Archived")
                                            <div class="status-archived"></div>
                                        @else
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="card-banner">
                                                <img src="{{ asset ('storage/weddings/lunch-venues/' . $lunch_venue->cover) }}" alt="{{ $lunch_venue->name }}" loading="lazy">
                                            </div>
                                            <div class="card-text">
                                                <div class="card-ptext-margin">
                                                    <div class="row ">
                                                        <div class="col-6 col-sm-4">
                                                            <div class="card-subtitle">Venue</div>
                                                            <p>{{ $lunch_venue->name }}</p>
                                                        </div>
                                                        <div class="col-6 col-sm-4">
                                                            <div class="card-subtitle">Minimum Capacity</div>
                                                            <p>{{ $lunch_venue->min_capacity. " Guest" }}</p>
                                                        </div>
                                                        <div class="col-6 col-sm-4">
                                                            <div class="card-subtitle">Maximum Capacity</div>
                                                            <p>{{ $lunch_venue->max_capacity. " Guest" }}</p>
                                                        </div>
                                                        <div class="col-6 col-sm-12">
                                                            <hr class="form-hr">
                                                        </div>
                                                        <div class="col-6 col-sm-4">
                                                            <div class="card-subtitle">Periode Start</div>
                                                            <p>{{ dateFormat($lunch_venue->periode_start) }}</p>
                                                        </div>
                                                        <div class="col-6 col-sm-4">
                                                            <div class="card-subtitle">Periode End</div>
                                                            <p>{{ dateFormat($lunch_venue->periode_end) }}</p>
                                                        </div>
                                                        <div class="col-6 col-sm-4">
                                                            <div class="card-subtitle">Publish Rate</div>
                                                            <div class="usd-rate">{{ '$ ' . number_format($lunch_venue->publish_rate, 0, ',', '.') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($lunch_venue->description != "")
                                                <div class="card-text">
                                                    <div class="row ">
                                                        <div class="col-12 col-sm-12">
                                                            <div class="tab-inner-title-light">
                                                                Description
                                                            </div>
                                                            <p>{!! $lunch_venue->description !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($lunch_venue->terms_and_conditions != "")
                                                <div class="card-text">
                                                    <div class="row ">
                                                        <div class="col-12 col-sm-12">
                                                            <div class="tab-inner-title-light">
                                                                Terms and Conditions
                                                            </div>
                                                            <p>{!! $lunch_venue->terms_and_conditions !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <form id="activate-lunch-venue-{{ $lunch_venue->id }}" action="/factivate-lunch-venue-{{ $lunch_venue->id }}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    @method('PUT')
                                </form>
                                <form id="deactivate-lunch-venue-{{ $lunch_venue->id }}" action="/fdeactivate-lunch-venue-{{ $lunch_venue->id }}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    @method('PUT')
                                </form>
                                <div class="card-box-footer">
                                    @if ($lunch_venue->status == "Draft")
                                        <a href="/update-lunch-venue-{{ $lunch_venue->id }}">
                                            <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                        </a>
                                        <button type="submit" form="activate-lunch-venue-{{ $lunch_venue->id }}" class="btn btn-info"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Activate</button>
                                    @else
                                        <button type="submit" form="deactivate-lunch-venue-{{ $lunch_venue->id }}" class="btn btn-dark"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Save as Draft</button>
                                    @endif
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="notification">Lunch venue not found, please add one!</div>
    @endif
    @canany(['posDev','posAuthor'])
        {{-- MODAL ADD LUNCH VENUE --}}
        <div class="modal fade" id="add-lunch-venue-{{ $hotel->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="card-box">
                        <div class="card-box-title">
                            <div class="title"><i class="icon-copy fa fa-plus"></i> Add Lunch Venue</div>
                        </div>
                        <form id="add-lunch-venue" action="/fcreate-new-lunch-venue/{{ $hotel->id }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12">
                                    <div class="row">
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="banner" class="form-label">Cover Image</label>
                                                <div class="dropzone">
                                                    <div class="banner-preview-div">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="cover" class="form-label">Cover Image <span> *</span></label><br>
                                        <input type="file" name="cover" id="banner" class="custom-file-input @error('cover') is-invalid @enderror" placeholder="Choose Cover" value="{{ old('cover') }}" required>
                                        @error('cover')
                                            <div class="alert-form alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6 col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6 col-md-6">
                                    <div class="form-group">
                                        <label for="min_capacity" class="form-label">Minimum Capacity</label>
                                        <input type="number" name="min_capacity" class="form-control @error('min_capacity') is-invalid @enderror" placeholder="Capacity" value="{{ old('min_capacity') }}">
                                        @error('min_capacity')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6 col-md-6">
                                    <div class="form-group">
                                        <label for="max_capacity" class="form-label">Maximum Capacity</label>
                                        <input type="number" min="1" name="max_capacity" class="form-control @error('max_capacity') is-invalid @enderror" placeholder="Capacity" value="{{ old('max_capacity') }}" required>
                                        @error('max_capacity')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6 col-md-6">
                                    <div class="form-group">
                                        <label for="periode_start" class="form-label">Periode Start</label>
                                        <input type="text" name="periode_start" class="form-control date-picker @error('periode_start') is-invalid @enderror" placeholder="Periode Start" value="{{ old('periode_start') }}" required>
                                        @error('periode_start')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6 col-md-6">
                                    <div class="form-group">
                                        <label for="periode_end" class="form-label">Periode End</label>
                                        <input type="text" name="periode_end" class="form-control date-picker @error('periode_end') is-invalid @enderror" placeholder="Periode End" value="{{ old('periode_end') }}" required>
                                        @error('periode_end')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6 col-md-6">
                                    <div class="form-group">
                                        <label for="markup" class="form-label">Markup</label>
                                        <input type="number" name="markup" class="form-control @error('markup') is-invalid @enderror" placeholder="Markup" value="{{ old('markup') }}">
                                        @error('markup')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6 col-md-6">
                                    <div class="form-group">
                                        <label for="publish_rate" class="form-label">Publish Rate</label>
                                        <input type="number" name="publish_rate" class="form-control @error('publish_rate') is-invalid @enderror" placeholder="Publish Rate" value="{{ old('publish_rate') }}">
                                        @error('publish_rate')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" class="ckeditor form-control @error('description') is-invalid @enderror" placeholder="Insert description" value="{{ old('description') }}">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="terms_and_conditions" class="form-label">Terms and Conditions</label>
                                        <textarea name="terms_and_conditions" class="ckeditor form-control @error('terms_and_conditions') is-invalid @enderror" placeholder="Insert terms_and_conditions" value="{{ old('terms_and_conditions') }}">{{ old('terms_and_conditions') }}</textarea>
                                        @error('terms_and_conditions')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="card-box-footer">
                            <button type="submit" form="add-lunch-venue" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcanany
    <div class="card-box-footer">
        <a href="modal" data-toggle="modal" data-target="#add-lunch-venue-{{ $hotel->id }}">
            <button class="btn btn-primary"><i class="icon-copy fa fa-plus-circle" aria-hidden="true"></i> Lunch Venue</button>
        </a>
    </div>
</div>