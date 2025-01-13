@section('content')
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    @canany(['posDev','posAuthor','posRsv'])
        <div class="main-container">
            <div class="pd-ltr-20">
                <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="title">
                                <i class="fa fa-user" aria-hidden="true"></i> Guide Manager
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/admin-panel">Admin Panel</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Guide Manager</li>
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
                    @if (\Session::has('invalid'))
                        <div class="alert alert-danger">
                            <ul>
                                <li>{!! \Session::get('invalid') !!}</li>
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="row">
                    {{-- ATTENTIONS --}}
                    <div class="col-md-4 mobile">
                        @include('layouts.attentions')
                    </div>
                    <div class="col-md-8">
                        <div class="card-box">
                            <div class="card-box-title">
                                <div class="title">All Guide</div>
                            </div>
                            <div class="input-container">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
                                    <input id="searchGuideByName" type="text" onkeyup="searchGuideByName()" class="form-control" name="search-guide-byname" placeholder="Search by name">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
                                    <input id="searchGuideByLanguage" type="text" onkeyup="searchGuideByLanguage()" class="form-control" name="search-guide-language" placeholder="Search by language">
                                </div>
                            </div>
                            <div class="table-container">
                               
                                    <table id="tbGuides" class="data-table table stripe hover" >
                                        <thead>
                                            <tr>
                                                <th style="width: 10%">No</th>
                                                <th style="width: 20%">Name</th>
                                                <th style="width: 20%">Phone</th>
                                                <th style="width: 20%">Language</th>
                                                <th style="width: 10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($guides as $no=>$guide)
                                                
                                                <tr>
                                                    <td>
                                                        {{ ++$no }}
                                                    </td>
                                                    <td>
                                                        {{ $guide->name }}
                                                    </td>
                                                    <td>
                                                        {{ $guide->phone }}
                                                    </td>
                                                    <td>
                                                        {{ $guide->language }}
                                                    </td>
                                                    <form id="destroy-guide-{{ $guide->id }}" action="/fdestroy-guide/{{ $guide->id }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                    <td>
                                                        <div class="table-action">
                                                            <a href="modal" data-toggle="modal" data-target="#guide-view-{{ $guide->id }}">
                                                                <button class="btn-view"><i class="dw dw-eye"></i></button>
                                                            </a>
                                                            @canany(['posDev','posAuthor'])
                                                                <a href="modal" data-toggle="modal" data-target="#guide-edit-{{ $guide->id }}">
                                                                    <button class="btn-edit"><i class="icon-copy fa fa-edit"></i></button>
                                                                </a>
                                                                <button type="submit" form="destroy-guide-{{ $guide->id }}" class="btn-delete" onclick="return confirm('Are you sure you want to remove the {{ $guide->name }} from the list of guides?');" type="submit" data-toggle="tooltip" data-placement="top" title="Delete"><i class="icon-copy fa fa-trash-o" aria-hidden="true"></i></button>
                                                            @endcan
                                                        </div>
                                                        {{-- MODAL VIEW GUIDE DETAIL ----------------------------------------------------------------------------------------------------------- --}}
                                                        <div class="modal fade" id="guide-view-{{ $guide->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="card-box">
                                                                        <div class="card-box-title">
                                                                            <div class="title"><i class="icon-copy fa fa-user" aria-hidden="true"></i> Guide Detail</div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="row">
                                                                                <div class="col-md-3">
                                                                                    <div class="user-manager-img m-b-18">
                                                                                        <img src="{{ asset('storage/user/profile/default_user_img.png') }}" alt="{{ $guide->name }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-3 col-md-2">
                                                                                    <p>Name</p>
                                                                                    <p>Sex</p>
                                                                                    <p>Phone</p>
                                                                                    <p>Email</p>
                                                                                    <p>Language</p>
                                                                                    <p>Address</p>
                                                                                    <p>From</p>
                                                                                </div>
                                                                                <div class="col-9 col-md-6">
                                                                                    <P>: {{ $guide->name }}</P>
                                                                                    @if ($guide->sex == "f")
                                                                                        <P>: Female</P>
                                                                                    @else
                                                                                        <P>: Male</P>
                                                                                    @endif
                                                                                    <P>: {{ $guide->phone }}</P>
                                                                                    <P>: {{ $guide->email }}</P>
                                                                                    <P>: {{ $guide->language }}</P>
                                                                                    <P>: {{ $guide->address }}</P>
                                                                                    <P>: {{ $guide->country }}</P>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card-box-footer">
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- MODAL EDIT GUIDE ----------------------------------------------------------------------------------------------------------- --}}
                                                        @canany(['posDev','posAuthor'])
                                                            <div class="modal fade" id="guide-edit-{{ $guide->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="card-box">
                                                                            <div class="card-box-title">
                                                                                <div class="title"><i class="fa fa-pencil"></i>Edit Guide</div>
                                                                            </div>
                                                                            <form id="update-guide-{{ $guide->id }}" action="/fedit-guide-{{ $guide->id }}" method="post" enctype="multipart/form-data">
                                                                                @csrf
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="row">
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="name" class="form-label">Name </label>
                                                                                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Insert Guide Name" value="{{ $guide->name }}">
                                                                                                    @error('name')
                                                                                                        <span class="invalid-feedback">
                                                                                                            <strong>{{ $message }}</strong>
                                                                                                        </span>
                                                                                                    @enderror
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="sex" class="form-label">Sex </label>
                                                                                                    <select name="sex" id="sex" class="custom-select @error('sex') is-invalid @enderror">
                                                                                                        @if ($guide->sex == "f")
                                                                                                            <option selected value="{{ $guide->sex }}"><p>Female</p></option>
                                                                                                            <option value="m"><p>Male</p></option>
                                                                                                        @else
                                                                                                            <option selected value="{{ $guide->sex }}"><p>Male</p></option>
                                                                                                            <option value="f"><p>Female</p></option>
                                                                                                        @endif
                                                                                                    </select>
                                                                                                    @error('sex')
                                                                                                        <span class="invalid-feedback">
                                                                                                            <strong>{{ $message }}</strong>
                                                                                                        </span>
                                                                                                    @enderror
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="phone" class="form-label">Telephone </label>
                                                                                                    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Insert telephone number" value="{{ $guide->phone }}">
                                                                                                    @error('phone')
                                                                                                        <span class="invalid-feedback">
                                                                                                            <strong>{{ $message }}</strong>
                                                                                                        </span>
                                                                                                    @enderror
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="email" class="form-label">Email </label>
                                                                                                    <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Insert Email" value="{{ $guide->email }}">
                                                                                                    @error('email')
                                                                                                        <span class="invalid-feedback">
                                                                                                            <strong>{{ $message }}</strong>
                                                                                                        </span>
                                                                                                    @enderror
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="language" class="form-label">Language </label>
                                                                                                    <select name="language" id="language" class="custom-select @error('language') is-invalid @enderror">
                                                                                                        @if ($guide->language == "Mandarin")
                                                                                                            <option selected value="{{ $guide->language }}"><p>{{ $guide->language }}</p></option>
                                                                                                            <option value="Indonesia"><p>Indonesia</p></option>
                                                                                                            <option value="English"><p>English</p></option>
                                                                                                        @elseif ($guide->language == "English")
                                                                                                            <option selected value="{{ $guide->language }}"><p>{{ $guide->language }}</p></option>
                                                                                                            <option value="Indonesia"><p>Indonesia</p></option>
                                                                                                            <option value="Mandarin"><p>Mandarin</p></option>
                                                                                                        @else
                                                                                                            <option value="Indonesia"><p>Indonesia</p></option>
                                                                                                            <option value="Mandarin"><p>Mandarin</p></option>
                                                                                                            <option value="English"><p>English</p></option>
                                                                                                        @endif
                                                                                                    </select>
                                                                                                    @error('language')
                                                                                                        <span class="invalid-feedback">
                                                                                                            <strong>{{ $message }}</strong>
                                                                                                        </span>
                                                                                                    @enderror
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="country" class="form-label">Country </label>
                                                                                                    <input type="text" id="country" name="country" class="form-control @error('country') is-invalid @enderror" placeholder="Insert Country" value="{{ $guide->country }}">
                                                                                                    @error('country')
                                                                                                        <span class="invalid-feedback">
                                                                                                            <strong>{{ $message }}</strong>
                                                                                                        </span>
                                                                                                    @enderror
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-12">
                                                                                                <div class="form-group">
                                                                                                    <label for="address" class="form-label">Address </label>
                                                                                                    <textarea id="address" name="address" class="ckeditor form-control @error('address') is-invalid @enderror" placeholder="Insert Address">{{ $guide->address }}</textarea>
                                                                                                    @error('address')
                                                                                                        <span class="invalid-feedback">
                                                                                                            <strong>{{ $message }}</strong>
                                                                                                        </span>
                                                                                                    @enderror
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                                                                </div>
                                                                            </form>
                                                                            <div class="card-box-footer">
                                                                                <button type="submit" form="update-guide-{{ $guide->id }}" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Save</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                            </div>
                            @canany(['posDev','posAuthor'])
                                <div class="card-box-footer">
                                    <a href="modal" data-toggle="modal" data-target="#guide-add">
                                        <button class="btn btn-primary"><i class="icon-copy fa fa-plus"></i> Add Guide</button>
                                    </a>
                                </div>
                            
                                {{-- MODAL ADD GUIDE ----------------------------------------------------------------------------------------------------------- --}}
                                <div class="modal fade" id="guide-add" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="card-box">
                                                <div class="card-box-title">
                                                    <div class="title"><i class="fa fa-plus"></i>Add Guide</div>
                                                </div>
                                                <form id="add-guide" method="POST" action="{{ route('create-guide') }}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="name" class="form-label">Name </label>
                                                                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Insert Guide Name" required>
                                                                        @error('name')
                                                                            <span class="invalid-feedback">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="sex" class="form-label">Sex <span>*</span></label>
                                                                        <select name="sex" id="sex" class="custom-select @error('sex') is-invalid @enderror">
                                                                            <option value=""><p>Select Sex</p></option>
                                                                            <option value="m"><p>Male</p></option>
                                                                            <option value="f"><p>Female</p></option>
                                                                        </select>
                                                                        @error('sex')
                                                                            <span class="invalid-feedback">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="phone" class="form-label">Telephone </label>
                                                                        <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Insert telephone number" required>
                                                                        @error('phone')
                                                                            <span class="invalid-feedback">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="email" class="form-label">Email </label>
                                                                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Insert Email" required>
                                                                        @error('email')
                                                                            <span class="invalid-feedback">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="language" class="form-label">Language <span>*</span></label>
                                                                        <select name="language" id="language" class="custom-select @error('language') is-invalid @enderror">
                                                                            <option value="Mandarin"><p>Mandarin</p></option>
                                                                            <option value="English"><p>English</p></option>
                                                                        </select>
                                                                        @error('language')
                                                                            <span class="invalid-feedback">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="country" class="form-label">Country </label>
                                                                        <input type="text" id="country" name="country" class="form-control @error('country') is-invalid @enderror" placeholder="Insert Country" required>
                                                                        @error('country')
                                                                            <span class="invalid-feedback">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="address" class="form-label">Address <span>*</span></label>
                                                                        <textarea id="address" name="address" class="ckeditor form-control @error('address') is-invalid @enderror" placeholder="Insert Address"></textarea>
                                                                        @error('address')
                                                                            <span class="invalid-feedback">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input id="author" name="author" value="{{ Auth::user()->id }}" type="hidden">
                                                    </div>
                                                </form>
                                                <div class="card-box-footer">
                                                    <button type="submit" form="add-guide" class="btn btn-primary"><i class="icon-copy fa fa-check" aria-hidden="true"></i> Save</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan
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
<script>
    function searchGuideByName() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchGuideByName");
        filter = input.value.toUpperCase();
        table = document.getElementById("tbGuides");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
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
    function searchGuideByLanguage() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchGuideByLanguage");
        filter = input.value.toUpperCase();
        table = document.getElementById("tbGuides");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[3];
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