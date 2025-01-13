@section('title','Send hotel promo')
@section('content')
    @extends('layouts.head')
    <div class="mobile-menu-overlay"></div>
    <div class="info-action">
        @if (\Session::has('error'))
            <div class="alert alert-danger">
                <ul>
                    <li>{!! \Session::get('error') !!}</li>
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
    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="title"><i class="icon-copy fa fa-building-o"></i> {{ $hotel->name }}</div>
                                <nav aria-label="breadcrumb" role="navigation">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/dashboard">@lang('messages.Dashboard')</a></li>
                                        <li class="breadcrumb-item"><a href="/hotels-admin">Hotels</a></li>
                                        <li class="breadcrumb-item"><a href="/detail-hotel-{{ $hotel->id }}">{{ $hotel->name }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Send Promo to Specific Agent</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-box">
                        <div class="card-box-title">
                            <div class="subtitle"><i class="icon-copy fa fa-building"></i> {{ $hotel->name }} PROMO</div>
                        </div>
                        <table id="activePromo" class="data-table table table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th>Booking Period</th>
                                    <th>Stay Period</th>
                                    <th>Promo</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($promos as $promo)
                                    <tr>
                                        <td>{{ dateFormat($promo->book_periode_start) }} - {{ dateFormat($promo->book_periode_end) }}</td>
                                        <td>{{ dateFormat($promo->periode_start) }} - {{ dateFormat($promo->periode_end) }}</td>
                                        <td>{{ $promo->name }}</td>
                                        <td>
                                            @if ($promo->send_to_specific_email == 1)
                                                <i class="icon-copy fa fa-check" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Email has been sent"></i>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($promo->send_to_specific_email == false)
                                                <a href="modal" data-toggle="modal" data-target="#send-email-{{ $promo->id }}" data-toggle="tooltip" data-placement="top" title="Send promo to Agents">
                                                    <button class="btn-search btn-primary"><i class="icon-copy fa fa-envelope" aria-hidden="true"></i> Send to Specific Agent</button>
                                                </a>
                                            @else
                                                <button class="btn-search btn-primary" data-toggle="tooltip" data-placement="right" title="Has ben send to {{ $promo->specific_email }}"><i class="icon-copy fa fa-envelope" aria-hidden="true"></i> Done</button>
                                            @endif
                                        </td>
                                    </tr>
                                    {{-- MODAL SEND EMAIL TO AGENT --}}
                                    <div class="modal fade" id="send-email-{{ $promo->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="card-box">
                                                    <div class="card-box-title">
                                                        <div class="title"><i class="icon-copy fa fa-envelope" aria-hidden="true"></i> Send Promo to Agents</div>
                                                    </div>
                                                    <form id="send-promo-email-{{ $promo->id }}" action="/fsend-promo-specific-email-to-agent-{{ $promo->id }}" method="post" enctype="multipart/form-data">
                                                        {{ csrf_field() }}
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="title">Title</label>
                                                                    <input type="text" name="title" id="title" wire:model="title" class="form-control  @error('title') is-invalid @enderror" placeholder="Insert Title" value="Don't miss out on our exciting promo, book now and enjoy special offers!">
                                                                    @error('title')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="bookingcode">Booking Code </label>
                                                                    <select id="bookingcode" name="bookingcode" class="form-control custom-select @error('bookingcode') is-invalid @enderror">
                                                                        <option value="">Select Booking Code</option>
                                                                        @foreach ($bcodes as $bcode)
                                                                            <option value="{{ $bcode->code }}"><b>{{ $bcode->code }}</b> | Discount ${{ $bcode->discounts }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('bookingcode')
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="emails">Insert Email List (Separate with a comma):</label><br>
                                                                    <textarea name="emails" id="emails" rows="5" style="width: 100%;" class="form-control" placeholder="ex: email_01@email.com, email_02@email.com, email_03@email.com"></textarea><br><br>
                                                                    @error('title')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="suggestion">suggestion</label>
                                                                    <textarea id="suggestion" name="suggestion"  class="ckeditor form-control border-radius-0 @error('suggestion') is-invalid @enderror" placeholder="Insert some text ...">We are thrilled to offer you an "{{ $promo->name }}" for your next stay at {{ $hotel->name }}. Don't miss out on these fantastic deals</textarea>
                                                                    @error('suggestion')
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="card-ptext-margin">
                                                                    <div class="card-ptext-content">
                                                                        <div class="ptext-title">Promo</div>
                                                                        <div class="ptext-value">{{ $promo->name }}</div>
                                                                        <div class="ptext-title">Room</div>
                                                                        <div class="ptext-value">{{ $promo->rooms->rooms }}</div>
                                                                        <div class="ptext-title">Minimum Stay</div>
                                                                        <div class="ptext-value">{{ $promo->minimum_stay }} Nights</div>
                                                                        <div class="ptext-title">Booking Period</div>
                                                                        <div class="ptext-value">{{ dateFormat($promo->book_periode_start) }} - {{ dateFormat($promo->book_periode_end) }}</div>
                                                                        <div class="ptext-title">Stay Period</div>
                                                                        <div class="ptext-value">{{ dateFormat($promo->periode_start) }} - {{ dateFormat($promo->periode_end) }}</div>
                                                                        <div class="ptext-title">Benefits</div>
                                                                        <div class="ptext-value">{!! $promo->benefits !!}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="link" value="https://online.balikamitour.com/hotel-{{ $hotel->code }}">
                                                    </form>
                                                    <div class="card-box-footer">
                                                        <button type="submit" form="send-promo-email-{{ $promo->id }}" class="btn btn-primary"><i class="icon-copy fa fa-envelope" aria-hidden="true"></i> Send Promo</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
@endsection
