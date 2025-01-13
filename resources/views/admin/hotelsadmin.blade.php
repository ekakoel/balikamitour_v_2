@section('content')
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    @can('isAdmin')
        <div class="main-container">
            <div class="pd-ltr-20">
                <div class="min-height-200px">
                    <div class="page-header">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="title">
                                    <i class="icon-copy fa fa-building" aria-hidden="true"></i> Hotels
                                </div>
                                <nav aria-label="breadcrumb" role="navigation">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/admin-panel">Admin Panel</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Hotels</li>
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
                        @if (count($cactivehotels)>0 or count($drafthotels)>0 or count($archivehotels)>0)
                            <div class="col-md-4 mobile">
                                <div class="counter-container">
                                    @if (count($cactivehotels)>0)
                                        <div class="widget">
                                            <a href="#activehotels">
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="chart-icon-active">
                                                        <i class="micon fa fa-building" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="widget-data">
                                                        <div class="widget-data-title">{{ $cactivehotels->count() }} Hotels</div>
                                                        <div class="widget-data-subtitle">Active</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                    @if (count($drafthotels)>0)
                                        <div class="widget">
                                            <a href="#drafthotels">
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="chart-icon-draft">
                                                        <i class="micon fa fa-building" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="widget-data">
                                                        <div class="widget-data-title">{{ $drafthotels->count() }} Hotels</div>
                                                        <div class="widget-data-subtitle">Draft</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                    @if (count($archivehotels)>0)
                                        <div class="widget">
                                            <a href="#archivehotels">
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="chart-icon-archive">
                                                        <i class="micon fa fa-building" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="widget-data">
                                                        <div class="widget-data-title">{{ $archivehotels->count() }} Hotels</div>
                                                        <div class="widget-data-subtitle">Archived</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="col-md-8">
                            <div class="card-box">
                                <div class="card-box-title">
                                    <div class="title">All Hotels</div>
                                </div>
                                <div class="input-container">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
                                        <input id="searchHotelByName" type="text" onkeyup="searchHotelByName()" class="form-control" name="search-hotel-byname" placeholder="Search by name...">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
                                        <input id="searchHotelByLocation" type="text" onkeyup="searchHotelByLocation()" class="form-control" name="search-hotel-location" placeholder="Search by location...">
                                    </div>
                                    <div class="input-group">
                                        <a href="/download-data-hotel">
                                            <div class="btn btn-primary">
                                            <i class="icon-copy fa fa-file-pdf-o" aria-hidden="true"></i> Download PDF</i>
                                        </div>
                                        </a>
                                    </div>
                                </div>
                                @if (count($hotels)>0)
                                    <table id="tbHotels" class="data-table table stripe hover nowrap">
                                        <thead>
                                            <tr>
                                                <th data-priority="1" class="datatable-nosort" style="width: 10%;">No</th>
                                                <th data-priority="2" style="width: 20%;">Name</th>
                                                <th style="width: 10%;">Normal</th>
                                                <th style="width: 10%;">Promo</th>
                                                <th style="width: 10%;">Package</th>
                                                <th class="datatable-nosort" style="width: 10%;">Rooms</th>
                                                <th style="width: 10%;" class="datatable-nosort">Status</th>
                                                <th class="datatable-nosort" style="width: 10%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($hotels as $no=>$hotel)
                                                <tr>
                                                    <td>
                                                        {{ ++$no }}
                                                    </td>
                                                    <td>
                                                        <div class="table-service-name">{{ $hotel['name'] }}</div>
                                                    </td>
                                                    <td>
                                                        @if (isset($normal_prices))
                                                            @php
                                                                $n_price = $normal_prices->where('hotels_id',$hotel->id)->first();
                                                            @endphp
                                                            @if (isset($n_price))
                                                                <p>{{ dateFormat($n_price->end_date) }}</p>
                                                            @else
                                                                <p style="color:red;">-</p>
                                                            @endif
                                                        @else
                                                            <p style="color:red;">Expired</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (isset($promos))
                                                            @php
                                                                $promo_max_date = $promos->where('hotels_id',$hotel->id)->first();
                                                            @endphp
                                                            @if (isset($promo_max_date))
                                                                <p>{{ dateFormat($promo_max_date->book_periode_end) }}</p>
                                                            @else
                                                                <p style="color:red;">-</p>
                                                            @endif
                                                        @else
                                                            <p style="color:red;">Expired</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (isset($packages))
                                                            @php
                                                                $package_max_date = $packages->where('hotels_id',$hotel->id)->first();
                                                            @endphp
                                                            @if (isset($package_max_date))
                                                                <p>{{ dateFormat($package_max_date->stay_period_end) }}</p>
                                                            @else
                                                                <p style="color:red;">-</p>
                                                            @endif
                                                        @else
                                                            <p style="color:red;">Expired</p>
                                                        @endif
                                                        
                                                    </td>
                                                    <td>
                                                        <p>{{ $hotel->rooms->where('status','Active')->count() }} A , {{ $hotel->rooms->where('status','Draft')->count() }} D </p>
                                                    </td>
                                                    <td>
                                                        @if ($hotel->status == "Active")
                                                            <div class="status-active"></div>
                                                        @elseif ($hotel->status == "Draft")
                                                            <div class="status-draft"></div>
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        <div class="table-action">
                                                            <a href="/detail-hotel-{{ $hotel->id }}">
                                                                <button class="btn-view" data-toggle="tooltip" data-placement="top" title="Detail"><i class="dw dw-eye"></i></button>
                                                            </a>
                                                            @canany(['posDev','posAuthor'])
                                                                <a href="/edit-hotel-{{ $hotel->id }}">
                                                                    <button class="btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-copy fa fa-pencil"></i></button>
                                                                </a>
                                                                <form class="display-content" action="/remove-hotel/{{ $hotel->id }}" method="post">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <input type="hidden" name="author" value="{{ Auth::user()->id }}">
                                                                    <button class="btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="top" title="Delete"><i class="icon-copy fa fa-trash"></i></button>
                                                                </form>
                                                            @endcanany
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="col-xl-12">
                                        <div class="notification"><i class="icon-copy fa fa-info-circle" aria-hidden="true"></i> Tour packages are not yet available, please add some hotel packages!</div>
                                    </div>
                                @endif
                                @canany(['posDev','posAuthor'])
                                    <div class="card-box-footer">
                                        <a href="/add-hotel"><button class="btn btn-primary"><i class="ion-plus-round"></i> Add Hotel</button></a>
                                    </div>
                                @endcanany
                            </div>
                        </div>
                        @if (count($cactivehotels)>0 or count($drafthotels)>0 or count($archivehotels)>0)
                            <div class="col-md-4 desktop">
                                <div class="counter-container">
                                    @if (count($cactivehotels)>0)
                                        <div class="widget">
                                            <a href="#activehotels">
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="chart-icon-active">
                                                        <i class="micon fa fa-building" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="widget-data">
                                                        <div class="widget-data-title">{{ $cactivehotels->count() }} Hotels</div>
                                                        <div class="widget-data-subtitle">Active</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                    @if (count($drafthotels)>0)
                                        <div class="widget">
                                            <a href="#drafthotels">
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="chart-icon-draft">
                                                        <i class="micon fa fa-building" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="widget-data">
                                                        <div class="widget-data-title">{{ $drafthotels->count() }} Hotels</div>
                                                        <div class="widget-data-subtitle">Draft</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                    @if (count($archivehotels)>0)
                                        <div class="widget">
                                            <a href="#archivehotels">
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="chart-icon-archive">
                                                        <i class="micon fa fa-building" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="widget-data">
                                                        <div class="widget-data-title">{{ $archivehotels->count() }} Hotels</div>
                                                        <div class="widget-data-subtitle">Archived</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    @if (count($archivehotels)>0)
                        <div id="archivehotels" class="row">
                            <div class="col-md-8">
                                <div id="archivehotels" class="card-box mb-30">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="title">Archived Hotels</div>
                                        </div>
                                    </div>
                                    <table class="data-table table nowrap" >
                                        <thead>
                                            <tr>
                                                <th style="width: 15%;">Name</th>
                                                <th style="width: 10%;">Status</th>
                                                <th style="width: 10%;">Location</th>
                                                <th style="width: 10%;">Room & Suite</th>
                                                <th style="width: 10%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($archivehotels as $hotel)
                                                <tr>
                                                    <td>
                                                        <div class="table-service-name">{{ $hotel['name'] }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="status-archived"></div>
                                                    </td>
                                                    <td>
                                                        {{ $hotel->region }}
                                                    </td>
                                                    <td>
                                                        @if ($hotel->rooms->where('status','Active')->count() > 1)  
                                                            {{ $hotel->rooms->where('status','Active')->count() }} Rooms
                                                        @elseif ($hotel->rooms->where('status','Active')->count() == 1)  
                                                            {{ $hotel->rooms->where('status','Active')->count() }} Room
                                                        @else
                                                            0
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="/detail-hotel-{{ $hotel['id'] }}" data-toggle="tooltip" data-placement="top" title="View">
                                                            <button class="btn-view"><i class="dw dw-eye"></i></button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                    @include('layouts.footer')
                </div>
            </div>
        </div>
    @endcan
@endsection
<script>
    function searchHotelByName() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchHotelByName");
        filter = input.value.toUpperCase();
        table = document.getElementById("tbHotels");
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
</script>
<script>
    function searchHotelByLocation() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchHotelByLocation");
        filter = input.value.toUpperCase();
        table = document.getElementById("tbHotels");
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