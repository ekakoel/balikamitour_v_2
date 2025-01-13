@section('content')
<section class="anim-feed-up">
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    @can('isAdmin')
        <div class="main-container">
            <div class="pd-ltr-20">
                <div class="min-height-200px">
                    <div class="page-header">
                        <div class="title">
                            <i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Tour Package
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/admin-panel">Admin Panel</a></li>
                                <li class="breadcrumb-item"><a href="/partners">Partners</a></li>
                                <li class="breadcrumb-item"><a href="/tours-admin">Tours</a></li>
                                <li class="breadcrumb-item active">Add Tour Package</li>
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
                    <div class="row">
                        {{-- ATTENTIONS --}}
                        <div class="col-md-4 mobile">
                            <div class="row">
                                @include('layouts.attentions')
                            </div>
                        </div>
                        <div class="col-md-8 m-b-18">
                            <div class="card-box">
                                <div class="card-box-title">
                                    <div class="subtitle"><i class="fa fa-briefcase"></i>Tour Package</div>
                                </div>
                                <form id="add-tour" action="/fadd-tour" method="post" enctype="multipart/form-data" id="my-awesome-dropzone">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="row">
                                                <div class="col-12 col-sm-6 col-md-6">
                                                    <div class="card-subtitle">Cover Image</div>
                                                    <div class="dropzone">
                                                        <div class="cover-preview-div">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label for="cover" class="form-label">Cover Image <span> *</span></label><br>
                                                        <input type="file" name="cover" id="cover" class="custom-file-input @error('cover') is-invalid @enderror" placeholder="Choose Cover" value="{{ old('cover') }}" required>
                                                        @error('cover')
                                                            <div class="alert-form alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="form-label">Name </label>
                                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Insert tour package name" value="{{ old('name') }}" required>
                                                @error('name')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="partners_id" class="form-label">Partner <span> *</span></label>
                                                <select id="partners_id" name="partners_id" value="{{ old('partners_id') }}" class="custom-select col-12 @error('partners_id') is-invalid @enderror" required>
                                                    @if (old('partners_id') != "")
                                                        @php
                                                            $prtnr = $partners->where('id',old('partners_id'))->first();
                                                        @endphp
                                                        <option selected value="{{ old('partners_id') }}">{{ $prtnr->name }}</option>
                                                    @else
                                                        <option selected value="">Select Partner</option>
                                                    @endif
                                                    @foreach ($partners as $partner)
                                                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('partners_id')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="location" class="form-label">Location </label>
                                                <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" placeholder="Insert tour location" value="{{ old('location') }}" required>
                                                @error('location')location
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="type" class="form-label">Type <span> *</span></label>
                                                <select id="type" name="type" class="custom-select col-12 @error('type') is-invalid @enderror" required>
                                                    @if (old('type') != "")
                                                        <option selected value="{{ old('type') }}">{{ old('type') }}</option>
                                                    @else
                                                        <option selected value="">Select Type</option>
                                                    @endif
                                                    <option value="Private">Private</option>
                                                    <option value="Group">Group</option>
                                                    <option value="Shared">Shared</option>
                                                </select>
                                                @error('type')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="duration" class="form-label">Duration <span> *</span></label>
                                                <select id="duration" name="duration" class="custom-select col-12 @error('duration') is-invalid @enderror" required>
                                                    @if (old('duration') != "")
                                                        <option selected value="{{ old('duration') }}">{{ old('duration') }}</option>
                                                    @else
                                                        <option selected value="">Select Duration</option>
                                                    @endif
                                                    <option value="1D">1D</option>
                                                    <option value="2D/1N">2D/1N</option>
                                                    <option value="3D/2N">3D/2N</option>
                                                    <option value="4D/3N">4D/3N</option>
                                                    <option value="5D/4N">5D/4N</option>
                                                    <option value="6D/5N">6D/5N</option>
                                                    <option value="7D/6N">7D/6N</option>
                                                </select>
                                                @error('duration')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="description" class="form-label col-form-label">Description <span> *</span></label>
                                                <textarea id="description" name="description" class="ckeditor form-control @error('description') is-invalid @enderror" placeholder="Insert description" value="{{ old('description') }}" required>{{ old('description') }}</textarea>
                                                @error('description')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="destinations" class="form-label col-form-label">Destinations <span> *</span></label>
                                                <textarea id="destinations" name="destinations" class="ckeditor form-control @error('destinations') is-invalid @enderror" placeholder="Insert destinations" value="{{ old('destinations') }}" required>{{ old('destinations') }}</textarea>
                                                @error('destinations')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="itinerary" class="form-label col-form-label">Itinerary <span> *</span></label>
                                                <textarea id="itinerary" name="itinerary" class="ckeditor form-control @error('itinerary') is-invalid @enderror" placeholder="Insert itinerary" value="{{ old('itinerary') }}" required>{{ old('itinerary') }}</textarea>
                                                @error('itinerary')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="include" class="form-label col-form-label">Include <span> *</span></label>
                                                <textarea id="include" name="include" class="ckeditor form-control @error('include') is-invalid @enderror" placeholder="Insert include" value="{{ old('include') }}" required></textarea>
                                                @error('include')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="cancellation_policy" class="form-label col-form-label">Cancellation Policy</label>
                                                <textarea id="cancellation_policy" name="cancellation_policy" class="ckeditor form-control @error('cancellation_policy') is-invalid @enderror" placeholder="Insert additional information" value="{{ old('cancellation_policy') }}"></textarea>
                                                @error('cancellation_policy')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="additional_info" class="form-label col-form-label">Additional Information</label>
                                                <textarea id="additional_info" name="additional_info" class="ckeditor form-control @error('additional_info') is-invalid @enderror" placeholder="Insert additional information" value="{{ old('additional_info') }}"></textarea>
                                                @error('additional_info')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                        <input id="page" name="page" value="add-tour" type="hidden">
                                        <input id="initial_state" name="initial_state" value="" type="hidden">
                                    </div>
                                </form>
                                <div class="card-box-footer">
                                    <button type="submit" form="add-tour" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Add Tour</button>
                                    <a href="/tours-admin">
                                        <button type="button"class="btn btn-danger"><i class="icon-copy fa fa-remove" aria-hidden="true"></i> Cancel</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- ATTENTIONS --}}
                        <div class="col-md-4 desktop">
                            <div class="row">
                                @include('layouts.attentions')
                            </div>
                        </div>
                    </div>
                    @include('layouts.footer')
                </div>
            </div>
        </div>
    @endcan
</section>
@endsection