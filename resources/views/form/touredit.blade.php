@section('content')
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    @can('isAdmin')
        <div class="main-container">
            <div class="pd-ltr-20">
                <div class="min-height-200px">
                    <div class="page-header">
                        <div class="title"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i>Edit Tour Package
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
                                <li class="breadcrumb-item"><a href="/detail-tour-{{ $tours->id }}">Tour Detail</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ "Edit Tour ". $tours->name }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        {{-- ATTENTIONS --}}
                        <div class="col-md-4 mobile">
                            <div class="row">
                                @include('admin.usd-rate')
                                @include('layouts.attentions')
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-box">
                                <div class="card-box-title">
                                    <div class="subtitle"><i class="icon-copy fa fa-briefcase"></i>{{ $tours->name }}</div>
                                </div>
                                <form id="edit-tour" action="/fupdate-tour/{{ $tours->id }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="dropzone">
                                                        <div class="cover-preview-div">
                                                            <img src="{{ asset('storage/tours/tours-cover/' . $tours->cover)  }}" alt="{{ $tours->name }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="cover" class="form-label col-form-label">Cover Image </label>
                                                <input type="file" name="cover" id="cover" class="custom-file-input @error('cover') is-invalid @enderror" placeholder="Choose Cover" value="{{ old('cover') }}">
                                                @error('cover')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="cover" class="form-label col-form-label">Status </label>
                                                <select id="status" name="status" class="custom-select col-12 @error('status') is-invalid @enderror" required>
                                                    <option selected="{{ $tours->status }}">{{ $tours->status }}</option>
                                                    <option value="Active">Active</option>
                                                    <option value="Draft">Draft</option>
                                                    <option value="Archived">Archived</option>
                                                </select>
                                                @error('status')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label class="form-label col-form-label">Name </label>
                                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Tour Package Name" value="{{ $tours->name }}" required>
                                                @error('name')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="partners_id" class="form-label col-form-label">Partner </label>
                                                <select id="partners_id" name="partners_id" value="{{ old('partners_id') }}" class="custom-select col-12 @error('partners_id') is-invalid @enderror" required>
                                                    @if (isset($partner))
                                                        <option selected value="{{ $partner->id }}">{{ $partner->name }}</option>
                                                    @else
                                                        <option selected value="">Select Partner</option>
                                                    @endif
                                                    @foreach ($partners as $partner)
                                                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('partners_id')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="location" class="form-label col-form-label">Location </label>
                                                <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" placeholder="Tour Package Name" value="{{ $tours->location }}" required>
                                                @error('location')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="type" class="form-label col-form-label">Type </label>
                                                <select id="type" name="type" class="custom-select col-12 @error('type') is-invalid @enderror" required>
                                                    <option selected value="{{ $tours->type }}">{{ $tours->type }}</option>
                                                    <option value="Private">Private</option>
                                                    <option value="Group">Group</option>
                                                    <option value="Share">Share</option>
                                                </select>
                                                @error('type')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="duration" class="form-label col-form-label">Duration </label>
                                                <select id="duration" name="duration" value="{{ old('duration') }}" class="custom-select col-12 @error('duration') is-invalid @enderror" required>
                                                    <option selected value="{{ $tours->duration }}">{{ $tours->duration }}</option>
                                                    <option value="1D">1D</option>
                                                    <option value="2D/1N">2D/1N</option>
                                                    <option value="3D/2N">3D/2N</option>
                                                    <option value="4D/3N">4D/3N</option>
                                                    <option value="5D/4N">5D/4N</option>
                                                    <option value="6D/5N">6D/5N</option>
                                                    <option value="7D/6N">7D/6N</option>
                                                </select>
                                                @error('duration')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="description" class="form-label col-form-label">Description </label>
                                                <textarea id="description" name="description" class="ckeditor form-control border-radius-0" required>{!! $tours->description !!}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="destinations" class="form-label col-form-label">Destinations </label>
                                                <textarea id="destinations" name="destinations" class="ckeditor form-control border-radius-0" required>{!! $tours->destinations !!}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="itinerary" class="form-label col-form-label">Itinerary </label>
                                                <textarea id="itinerary" name="itinerary" class="ckeditor form-control border-radius-0" required>{!! $tours->itinerary !!}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="include" class="form-label col-form-label">Include </label>
                                                <textarea id="include" name="include" class="ckeditor form-control border-radius-0" required>{!! $tours->include !!}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="cancellation_policy" class="form-label col-form-label">Cancellation Policy</label>
                                                <textarea id="cancellation_policy" name="cancellation_policy" class="ckeditor form-control border-radius-0">{!! $tours->cancellation_policy !!}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="additional_info" class="form-label col-form-label">Additional Information</label>
                                                <textarea id="additional_info" name="additional_info" class="ckeditor form-control border-radius-0">{!! $tours->additional_info !!}</textarea>
                                            </div>
                                        </div>
                                        <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                        <input id="page" name="page" value="admin-tour-edit" type="hidden">
                                        <input id="initial_state" name="initial_state" value="{{ $tours->status }}" type="hidden">
                                    </div>
                                </form>
                                <div class="card-box-footer">
                                    <button type="submit" form="edit-tour" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Update</button>
                                    <a href="detail-tour-{{ $tours['id'] }}">
                                        <button type="button"class="btn btn-danger"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- ATTENTIONS --}}
                        <div class="col-md-4 desktop">
                            <div class="row">
                               @include('admin.usd-rate')
                                @include('layouts.attentions')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footer')
    @endcan
@endsection