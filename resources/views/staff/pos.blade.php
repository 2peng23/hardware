@extends('layouts.staff')
@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-8 col-12 mb-5 shadow">
            <div class="p-4">
                <label for="item_barcode" class="form-label fw-bolder">Add Item's Barcode</label>
                <input type="text" class="form-control my-2 w-50" placeholder="Input barcode..." name="item_barcode"
                    id="item_barcode">
                <p class="text-danger mt-2" id="notFound" style="display: none;"></p>
                <p class="text-success mt-2" id="added" style="display: none;"></p>
            </div>
            <div class="table-responsive p-4">
                <i class="fa fa-refresh fs-3 text-danger float-end mb-2" style="cursor: pointer" id="reset-cart"></i>
                <table class="table" style="max-height: 500px" id="cart-data">
                    <thead>
                        <tr class="text-center">
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price(per piece)</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $cart)
                            <tr class="text-center">
                                @php
                                    $product = App\Models\Product::where('id', $cart->item_id)->first();
                                @endphp
                                <td>{{ $product->name }}</td>
                                <td>{{ $cart->quantity }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->price * $cart->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($carts->count() > 0)
                <div class="d-flex mt-5 mb-2 justify-content-end ">
                    <button class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Proceed</button>
                </div>
            @endif
        </div>
        <div class="col-lg-3 col-md-4 col-12 mb-5">
            <input type="text" class="form-control my-2" placeholder="Search Item" name="item" id="item">
            <div class="table-responsive" id="info-table">
                <table class="table" style="max-height: 500px">
                    <thead>
                        <tr class="text-center">
                            <th>Items</th>
                            {{-- <th>Barcode</th> --}}
                            {{-- <th>Image</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr class="text-center">
                                <td>{{ $item->name }}</td>
                                {{-- <td>{!! DNS1D::getBarcodeHTML($item->barcode, 'EAN13', 4, 50, 'black') !!}</td> --}}
                                {{-- <td>{!! DNS1D::getBarcodeSVG($item->barcode, 'EAN13', 4, 50, 'black') !!}</td> --}}
                                <td>
                                    <button value="{{ $item->id }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // scan barcode
        $(document).on('change', '#item_barcode', function() {
            var item_barcode = $('#item_barcode').val();
            console.log(item_barcode);
            $.ajax({
                url: "{{ route('get-barcode') }}",
                data: {
                    item_barcode: item_barcode
                },
                type: 'GET',
                success: function(res) {
                    if (res.error) {
                        $('#notFound').html(res.error);
                        $('#notFound').fadeIn();
                        $('#item_barcode').val('');
                    } else {
                        $('#added').html(res.added);
                        $('#added').fadeIn();
                        $('#item_barcode').val('');
                        $('#cart-data').load(location.href + ' #cart-data');
                    }

                    // Hide success message after 1.5 seconds
                    setTimeout(function() {
                        $('#notFound').fadeOut('slow');
                        $('#added').fadeOut('slow');
                    }, 1000); //2 seconds
                }
            })
        });
        // reset items
        $(document).on('click', '#reset-cart', function() {
            if (confirm('Reset Items?'))
                $.ajax({
                    url: "{{ route('reset-cart') }}",
                    type: "get",
                    success: function(res) {
                        $('#cart-data').load(location.href + ' #cart-data');
                    }
                })
        })
    </script>
@endsection
