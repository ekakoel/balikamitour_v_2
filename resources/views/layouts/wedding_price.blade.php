


<div id="wedding-price" class="card-box">
    <div class="card-box-title">
        <div class="subtitle"><i class="icon-copy fa fa-money"></i>Wedding Price</div>
    </div>
    <div class="row">
       
        <div class="col-sm-4">
           <p>Fixed Services</p>
        </div>
        <div class="col-sm-6">
            @if ($fixedService_price > 0)
                <p>{{ "$ ".number_format($fixedService_price, 0, ",", ".") }}</p>
            @else
                <p>-</p>
            @endif
        </div>
        <div class="col-sm-4">
           <p> Wedding Venue</p>
        </div>
        <div class="col-sm-6">
            @if ($ven_price > 0)
                <p>{{ "$ ".number_format($ven_price, 0, ",", ".") }}</p>
            @else
                <p>-</p>
            @endif
        </div>
        <div class="col-sm-4">
           <p>Suite and Villa</p>
        </div>
        <div class="col-sm-6">
            @if ($suite_and_villa_price > 0)
                <p>{{ "$ ".number_format($suite_and_villa_price, 0, ",", ".") }}</p>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="col-sm-4">
           <p>Decoration</p>
        </div>
        <div class="col-sm-6">
            @if ($decorationPrice > 0)
                <p>{{ "$ ".number_format($decorationPrice, 0, ",", ".") }}</p>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="col-sm-4">
           <p>Dinner Venue</p>
        </div>
        <div class="col-sm-6">
            @if ($dinnerVenuePrice > 0)
                <p>{{ "$ ".number_format($dinnerVenuePrice, 0, ",", ".") }}</p>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="col-sm-4">
           <p>Make-up</p>
        </div>
        <div class="col-sm-6">
            @if ($makeupPrice > 0)
                <p>{{ "$ ".number_format($makeupPrice, 0, ",", ".") }}</p>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="col-sm-4">
           <p>Entertainment</p>
        </div>
        <div class="col-sm-6">
            @if ($entertainmentPrice > 0)
                <p>{{ "$ ".number_format($entertainmentPrice, 0, ",", ".") }}</p>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="col-sm-4">
           <p>Documentation</p>
        </div>
        <div class="col-sm-6">
            @if ($documentationPrice > 0)
                <p>{{ "$ ".number_format($documentationPrice, 0, ",", ".") }}</p>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="col-sm-4">
            <p>Transport</p>
        </div>
        <div class="col-sm-6">
            @if ($wedding_transport_price > 0)
                <p>{{ "$ ".number_format($wedding_transport_price, 0, ",", ".") }}</p>
            @else
                <p>-</p>
            @endif
        </div>

        <div class="col-sm-4">
            <p>Other Service</p>
         </div>
         <div class="col-sm-6">
             @if ($otherPrice > 0)
                 <p>{{ "$ ".number_format($otherPrice, 0, ",", ".") }}</p>
             @else
                 <p>-</p>
             @endif
         </div>
    </div>
    <div class="price-container m-t-18">
        <div class="row">
            <div class="col-6 text-left">
                <div class="price-text">Total Services</div>
            </div>
            <div class="col-6 text-right">
                <div class="price-rate">{{ "$ ". number_format($total_service_price, 0, ",", ".") }}</div>
            </div>
            <div class="col-6 text-left">
                <div class="price-text">Markup</div>
            </div>
            <div class="col-6 text-right">
                <div class="price-rate">{{ "$ ". number_format($weddings->markup, 0, ",", ".") }}</div>
            </div>
            <div class="col-12">
                <hr class="form-hr">
            </div>
            <div class="col-6 text-left">
                <div class="price-text">Public price estimation</div>
            </div>
            <div class="col-6 text-right">
                <div class="price-rate">{{ "$ ". number_format($published_price, 0, ",", ".") }}</div>
            </div>
        </div>
    </div>
    @if ($weddings->status !== "Active")
        <div class="card-box-footer">
            @if ($total_service_price > $published_price)
                <form id="refreshPrice" action="/frefresh-wedding-price/{{ $weddings->id }}" method="post" enctype="multipart/form-data">
                    @method('put')
                    {{ csrf_field() }}
                    <input type="hidden" name="total_service" value="{{ $total_service_price }}">
                    <input type="hidden" name="markup" value="{{ $weddings->markup }}">
                    <button  type="submit" form="refreshPrice" class="btn btn-primary"><i class="icon-copy fa fa-refresh" aria-hidden="true"> </i> Refresh Price</button>
                </form>
            @endif
            <a href="modal" data-toggle="modal" data-target="#add-wedding-price">
                @if ($weddings->decorations_id != 'null')
                    <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i> Edit Markup</button>
                @else
                    <button type="button" class="btn btn-primary"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Add</button>
                @endif
            </a>

        </div>
        {{-- MODAL EDIT PRICE --------------------------------------------------------------------------------------------------------------- --}}
        <div class="modal fade" id="add-wedding-price" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content text-left">
                    <div class="card-box">
                        <div class="card-box-title">
                            <div class="subtitle"><i class="icon-copy fa fa-money"></i>Wedding Price</div>
                        </div>
                        <form id="addPrice" action="/fadd-wedding-price/{{ $weddings->id }}" method="post" enctype="multipart/form-data">
                            @method('put')
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="card-title">Total Services</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card-title">{{ ": $ ". number_format(($total_service_price), 0, ",", ".") }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card-title">Markup</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card-title">{{ ": $ ". number_format(($weddings->markup), 0, ",", ".") }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card-title">Publish Rate</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card-title">{{ ": $ ". number_format(($weddings->price), 0, ",", ".") }}</div>
                                </div>
                                <div class="col-12">
                                    <hr class="form-hr">
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="markup" class="form-label">Markup</label>
                                        <div class="btn-icon">
                                            <span>$</span>
                                            <input type="number" id="markup" name="markup" class="input-icon form-control @error('markup') is-invalid @enderror" placeholder="Insert wedding price" value="{{ $weddings->markup }}" required>
                                            @error('markup')
                                                <div class="alert-form alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-box-footer">
                                <input type="hidden" name="total_service" value="{{ $total_service_price }}">
                                <button type="submit" form="addPrice" class="btn btn-primary"><i class="icon-copy fa fa-save" aria-hidden="true"></i> Save</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-copy fa fa-close" aria-hidden="true"></i> @lang('messages.Close')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
