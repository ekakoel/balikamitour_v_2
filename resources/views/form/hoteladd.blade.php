@section('content')
{{-- @include('component.sysload') --}}
<section class="anim-feed-up">
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    @can('isAdmin')
        <div class="main-container">
            <div class="pd-ltr-20">
                <div class="min-height-200px">
                    <div class="page-header">
                        <div class="title"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add Hotels</div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/admin-panel">Admin Panel</a></li>
                                <li class="breadcrumb-item"><a href="/hotels-admin">Hotels</a></li>
                                <li class="breadcrumb-item active">Add Hotels</li>
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
                        <div class="col-md-8">
                            <div class="card-box">
                                <div class="card-box-title">
                                    <div class="title">Detail Hotel</div>
                                </div>
                                <form id="add-hotel" action="/fadd-hotel" method="post" enctype="multipart/form-data" id="my-awesome-dropzone">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cover" class="form-label">Cover Image</label>
                                                        <div class="dropzone">
                                                            <div class="cover-preview-div">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="cover" class="form-label">Cover Image <span> *</span></label><br>
                                                <input type="file" name="cover" id="cover" class="custom-file-input @error('cover') is-invalid @enderror" placeholder="Choose Cover" value="{{ old('cover') }}" required>
                                                @error('cover')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="form-label">Name </label>
                                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Insert hotel name" value="{{ old('name') }}" required>
                                                @error('name')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="contact_person" class="form-label">Contact Person </label>
                                                <input type="text" id="contact_person" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" placeholder="Insert contact person" value="{{ old('contact_person') }}" required>
                                                @error('contact_person')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="phone" class="form-label">Phone Number </label>
                                                <input type="number" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Insert contact person phone" value="{{ old('phone') }}" required>
                                                @error('phone')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="min_stay" class="form-label">Min Stay </label>
                                                <input type="number" min="1" id="min_stay" name="min_stay" class="form-control @error('min_stay') is-invalid @enderror" placeholder="Minimum stay" value="{{ old('min_stay') }}" required>
                                                @error('min_stay')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="max_stay" class="form-label">Max Stay </label>
                                                <input type="number" min="1" id="max_stay" name="max_stay" class="form-control @error('max_stay') is-invalid @enderror" placeholder="Maximum stay" value="{{ old('max_stay') }}" required>
                                                @error('max_stay')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="address" class="form-label">Address </label>
                                                <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Insert address" value="{{ old('address') }}" required>
                                                @error('address')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="region" class="form-label">Region </label>
                                                <input type="text" id="region" name="region" class="form-control @error('region') is-invalid @enderror" placeholder="Insert region" value="{{ old('region') }}" required>
                                                @error('region')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="map" class="form-label">Map Location </label>
                                                <input type="text" id="map" name="map" class="form-control @error('map') is-invalid @enderror" placeholder="Google Map link" value="{{ old('map') }}" required>
                                                @error('map')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="web" class="form-label">Website </label>
                                                <input type="text" id="web" name="web" class="form-control @error('web') is-invalid @enderror" placeholder="Ex: www.example.com" value="{{ old('web') }}">
                                                @error('web')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="airport_distance" class="form-label">Airport Distance (Hours)</label>
                                                <input type="number" min="1" id="airport_distance" name="airport_distance" class="form-control @error('airport_distance') is-invalid @enderror" value="{{ old('airport_distance') }}" required>
                                                @error('airport_distance')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="airport_duration" class="form-label">Airport Duration (Km)</label>
                                                <input type="number" min="1" id="airport_duration" name="airport_duration" class="form-control @error('airport_duration') is-invalid @enderror" value="{{ old('airport_duration') }}" required>
                                                @error('airport_duration')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="description" class="form-label">Description <span> *</span></label>
                                                <textarea id="description" name="description" class="tiny_mce form-control border-radius-0" placeholder="Insert description">{{ old('description') }}</textarea>
                                                @error('description')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="facility" class="form-label">Facility</label>
                                                <textarea id="facility" name="facility" class="tiny_mce form-control border-radius-0" placeholder="Insert facility">{{ old('facility') }}</textarea>
                                                @error('facility')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="cancellation_policy" class="form-label">Cancellation Policy</label>
                                                <textarea id="cancellation_policy" name="cancellation_policy" class="tiny_mce form-control border-radius-0" placeholder="Insert cancellation policy">{{ old('cancellation_policy') }}</textarea>
                                                @error('cancellation_policy')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="additional_info" class="form-label">Additional Information</label>
                                                <textarea id="additional_info" name="additional_info" class="tiny_mce form-control border-radius-0" placeholder="Insert additional information">{{ old('additional_info') }}</textarea>
                                                @error('additional_info')
                                                    <div class="alert-form alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                        <input id="page" name="page" value="add-hotel" type="hidden">
                                        <input id="initial_state" name="initial_state" value="" type="hidden">
                                    </div>
                                </form>
                                <div class="card-box-footer">
                                    <button type="submit" form="add-hotel" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Add Hotel</button>
                                    <a href="/tours-admin"><button type="button"class="btn btn-danger"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button></a>
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