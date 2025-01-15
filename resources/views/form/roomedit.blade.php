@section('content')
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    @can('isAdmin')
        <div class="main-container">
            <div class="pd-ltr-20">
                <div class="min-height-200px">
                    <div class="page-header">
                        <div class="title">
                            <i class="icon-copy fa fa-briefcase" aria-hidden="true"></i> Edit Room</div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="room-admin">Hotel</a></li>
                                <li class="breadcrumb-item"><a href="/detail-hotel-{{ $hotel->id }}">{{ $hotel->name }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Room {{ $room->rooms }}</li>
                            </ol>
                        </nav>
                    </div>
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
                                    <div class="title">Detail Room</div>
                                </div>
                                <form id="edit-room" action="/fedit-room-{{ $room->id }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="row">
                                                @if ($room->cover != "")
                                                    <div class="col-md-6">
                                                        <div class="preview-cover">
                                                            <img src="{{ asset('storage/hotels/hotels-room/'. $room->cover)  }}" alt="{{ $room->name }}">
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-md-6">
                                                    <div class="dropzone">
                                                        <div class="cover-preview-div">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cover" class="form-label">Cover Image </label>
                                                        <input type="file" name="cover" id="cover" class="custom-file-input @error('cover') is-invalid @enderror" placeholder="Choose Cover" value="{{ old('cover') }}">
                                                        @error('cover')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cover" class="form-label">Status</label>
                                                        <select id="status" name="status" class="form-control custom-select @error('status') is-invalid @enderror" required>
                                                            <option selected="{{ $room->status }}">{{ $room->status }}</option>
                                                            <option value="Active">Active</option>
                                                            <option value="Draft">Draft</option>
                                                            <option value="Archived">Archived</option>
                                                        </select>
                                                        @error('status')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rooms" class="form-label">Name </label>
                                                <input type="text" id="rooms" name="rooms" class="form-control @error('rooms') is-invalid @enderror" placeholder="Insert hotel rooms" value="{{ $room->rooms }}" required>
                                                @error('rooms')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="capacity" class="form-label">Capacity </label>
                                                <input type="text" id="capacity" name="capacity" class="form-control @error('capacity') is-invalid @enderror" placeholder="Insert capacity" value="{{ $room->capacity }}" required>
                                                @error('capacity')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="include" class="form-label">Include </label>
                                                <textarea id="include" name="include" class="tiny_mce form-control" placeholder="Insert include" required>{{ $room->include }}</textarea>
                                                @error('include')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="additional_info" class="form-label">Additional Information</label>
                                                <textarea id="additional_info" name="additional_info" class="tiny_mce form-control" placeholder="Insert additional information">{{ $room->additional_info }}</textarea>
                                            </div>
                                            @error('additional_info')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                        <input id="hotels_id" name="hotels_id" value="{{ $hotel->id }}" type="hidden">
                                        <input id="page" name="page" value="edit-room" type="hidden">
                                    </div>
                                </form>
                                <div class="card-box-footer">
                                    <button type="submit" form="edit-room" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Update</button>
                                    <a href="/detail-hotel-{{ $hotel->id }}#rooms">
                                        <button type="button"class="btn btn-danger"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
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
@endsection
    
