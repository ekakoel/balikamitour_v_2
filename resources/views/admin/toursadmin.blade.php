
@section('content')
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    @can('isAdmin')
        <div class="main-container">
            <div class="pd-ltr-20">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="title">
                                <i class="icon-copy fa fa-briefcase" aria-hidden="true"></i> Tour Package
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/admin-panel">Admin Panel</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Tour Package</li>
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
                    @if (count($cactivetours)>0 or count($drafttours)>0 or count($archivetours)>0)
                        <div class="col-md-4 mobile">
                            <div class="counter-container">
                                @if (count($cactivetours)>0)
                                    <div class="widget">
                                        <a href="#activetours">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <div class="chart-icon">
                                                    <i class="micon fa fa-suitcase" aria-hidden="true"></i>
                                                </div>
                                                <div class="widget-data">
                                                    <div class="widget-data-title">{{ $cactivetours->count() }} Tour Package</div>
                                                    <div class="widget-data-subtitle">Active</div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @if (count($drafttours)>0)
                                <div class="widget">
                                    <a href="#drafttours">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="chart-icon">
                                                <i class="micon fa fa-suitcase" aria-hidden="true"></i>
                                            </div>
                                            <div class="widget-data">
                                                <div class="widget-data-title">{{ $drafttours->count() }} Tour Package</div>
                                                <div class="widget-data-subtitle">Draft</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                                @if (count($archivetours)>0)
                                    <div class="widget">
                                        <a href="#archivetours">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <div class="chart-icon">
                                                    <i class="micon fa fa-suitcase" aria-hidden="true"></i>
                                                </div>
                                                <div class="widget-data">
                                                    <div class="h4 mb-0">{{ $archivetours->count() }} Tour Package</div>
                                                    <div class="weight-600 font-14">Archived</div>
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
                                <div class="title"> Tour Package</div>
                            </div>
                            <div class="input-container">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
                                    <input id="searchTourByName" type="text" onkeyup="searchTourByName()" class="form-control" name="search-tour-byname" placeholder="Search by name...">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-copy fa fa-search" aria-hidden="true"></i></span>
                                    <input id="searchTourByLocation" type="text" onkeyup="searchTourByLocation()" class="form-control" name="search-tour-location" placeholder="Search by location...">
                                </div>
                                <div class="input-group">
                                    <a href="/download-data-tour">
                                        <div class="btn btn-primary">
                                            <i class="icon-copy fa fa-file-pdf-o" aria-hidden="true"></i> Download PDF</i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            @if (count($activetours)>0)
                                <table id="tbTours" class="data-table table stripe hover nowrap" >
                                    <thead>
                                        <tr>
                                            <th data-priority="1" class="datatable-nosort" style="width: 5%;">No</th>
                                            <th data-priority="2" style="width: 10%;">Partner</th>
                                            <th data-priority="2" style="width: 10%;">Name</th>
                                            <th data-priority="3" style="width: 10%;">Location</th>
                                            <th style="width: 10%;">Duration</th>
                                            <th style="width: 10%;">Status</th>
                                            <th class="datatable-nosort" style="width: 10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($activetours as $no=>$tour)
                                            @php
                                                $usd_rate = ceil($tour->contract_rate / $usdrates->rate);
                                                $usd_markup = $usd_rate + $tour->markup;
                                                $tax = $taxes->tax / 100;
                                                $pajak = ceil($tax * $usd_markup);
                                                $final_price = $usd_markup + $pajak;
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}<br>
                                                </td>
                                                <td>
                                                    @php
                                                        $partner = $partners->where('id', $tour->partners_id)->first();
                                                    @endphp
                                                    @if (isset($partner))
                                                        <div class="table-service-name">{!! $partner->name !!}</div>
                                                    @else
                                                        <div class="table-service-name">-</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="table-service-name">{{ $tour['name'] }}</div>
                                                </td>
                                                <td>
                                                    <p>{{ $tour->location }}</p>
                                                </td>
                                                <td>
                                                    <p>{{ $tour->duration }}</p>
                                                </td>
                                                {{-- <td>
                                                    <p>{{ "$ ". number_format($final_price, 0, ",", ".") }}<span>/Pax</span></p>
                                                </td> --}}
                                                <td>
                                                    @if ($tour->status == "Active")
                                                        <div class="status-active"></div>
                                                    @elseif ($tour->status == "Draft")
                                                        <div class="status-draft"></div>
                                                    @else
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    <div class="table-action">
                                                        <a href="/detail-tour-{{ $tour['id'] }}" data-toggle="tooltip" data-placement="top" title="View">
                                                            <button class="btn-view"><i class="dw dw-eye"></i></button>
                                                        </a>
                                                        @canany(['posDev','posAuthor'])
                                                            <a href="/edit-tour-{{ $tour['id'] }}" data-toggle="tooltip" data-placement="top" title="Edit">
                                                                <button class="btn-edit"><i class="icon-copy fa fa-edit"></i></button>
                                                            </a>
                                                            <form class="display-content" action="/remove-tour/{{ $tour['id'] }}" method="post">
                                                                <input type="hidden" name="author" value="{{ Auth::user()->id }}">
                                                                <button class="btn-delete" onclick="return confirm('Are you sure?');" type="submit" data-toggle="tooltip" data-placement="top" title="Delete">
                                                                    <i class="icon-copy fa fa-trash"></i></button>
                                                                @csrf
                                                                @method('delete')
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
                                    <div class="notification"><i class="icon-copy fa fa-info-circle" aria-hidden="true"></i> Tour packages are not yet available, please add some tour packages!</div>
                                </div>
                            @endif
                            @canany(['posDev','posAuthor'])
                                <div class="card-box-footer">
                                    <a href="/add-tour"><button class="btn btn-primary"><i class="ion-plus-round"></i> Add Tour Package</button></a>
                                </div>
                            @endcanany
                        </div>
                    </div>
                    @if (count($cactivetours)>0 or count($drafttours)>0 or count($archivetours)>0)
                        <div class="col-md-4 desktop">
                            <div class="counter-container">
                                @if (count($cactivetours)>0)
                                    <div class="widget">
                                        <a href="#activetours">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <div class="chart-icon">
                                                    <i class="micon fa fa-suitcase" aria-hidden="true"></i>
                                                </div>
                                                <div class="widget-data">
                                                    <div class="widget-data-title">{{ $cactivetours->count() }} Tour Package</div>
                                                    <div class="widget-data-subtitle">Active</div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @if (count($drafttours)>0)
                                <div class="widget">
                                    <a href="#drafttours">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="chart-icon">
                                                <i class="micon fa fa-suitcase" aria-hidden="true"></i>
                                            </div>
                                            <div class="widget-data">
                                                <div class="widget-data-title">{{ $drafttours->count() }} Tour Package</div>
                                                <div class="widget-data-subtitle">Draft</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                                @if (count($archivetours)>0)
                                    <div class="widget">
                                        <a href="#archivetours">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <div class="chart-icon">
                                                    <i class="micon fa fa-suitcase" aria-hidden="true"></i>
                                                </div>
                                                <div class="widget-data">
                                                    <div class="h4 mb-0">{{ $archivetours->count() }} Tour Package</div>
                                                    <div class="weight-600 font-14">Archived</div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                @if (count($archivetours)>0)
                    <div class="row">
                        <div class="col-md-8">
                            <div id="archivetours" class="card-box mb-30">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="subtitle">Archived Tour Package</div>
                                    </div>
                                </div>
                                <table class="data-table table nowrap" >
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;">Name</th>
                                            <th style="width: 5%;">Status</th>
                                            <th style="width: 5%;">Location</th>
                                            <th style="width: 10%;">Duration</th>
                                            <th style="width: 10%;">Price/Pax</th>
                                            <th style="width: 10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($archivetours as $tour)
                                            @php
                                                $usdrates = ceil($tour->contract_rate / $usdrates->rate);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="table-service-name">{{ $tour['name'] }}</div>
                                                </td>
                                                <td>
                                                    <div class="status-archived"></div>
                                                </td>
                                                <td>
                                                    <p>{{ $tour->location }}</p>
                                                </td>
                                                <td>
                                                    <p>{{ $tour->duration }}</p>
                                                </td>
                                                <td>
                                                    {{ "$ ".ceil(($usdrates * $tour->markup / 100) + $usdrates)." /pax" }}
                                                </td>
                                                <td>
                                                    <a href="/detail-tour-{{ $tour['id'] }}" data-toggle="tooltip" data-placement="top" title="View">
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
    @endcan
@endsection
<script>
    function searchTourByName() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchTourByName");
        filter = input.value.toUpperCase();
        table = document.getElementById("tbTours");
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
    function searchTourByLocation() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchTourByLocation");
        filter = input.value.toUpperCase();
        table = document.getElementById("tbTours");
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
