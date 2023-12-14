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
            <div class="table-responsive p-4" id="cart-data">

                <i class="fa fa-refresh fs-3 text-danger float-end mb-2" style="cursor: pointer" id="reset-cart"></i>
                <table class="table table-hover " style="max-height: 500px">
                    <thead>
                        <tr class="text-center">
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price(per piece)</th>
                            <th>Total Price</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $cart)
                            <tr class="text-center">
                                @php
                                    $product = App\Models\Product::where('id', $cart->item_id)->first();
                                @endphp
                                <td>{{ $product->name }}</td>
                                <td>
                                    <button class="btn btn-sm px-2 btn-secondary btn-add"
                                        value="{{ $cart->id }}">{{ $cart->quantity }}</button>
                                </td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->price * $cart->quantity }}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm btn-remove" value="{{ $cart->id }}">
                                        <i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <form action="{{ route('proceed-purchase') }}" method="post">
                    @csrf
                    @if ($carts->count() > 0)
                        <div class="d-flex mt-5 mb-2 justify-content-end ">
                            <div>
                                <p>Payment: <span> <input type="number" name="product_payment" id="payment"
                                            required></span></p>
                                <p>Total Amount: <span id="total_price">{{ number_format($total_price, 2) }}</span></p>
                                <p>Change: <span id="change"></span></p>
                                <button class="btn btn-primary" type="submit" id="proceed-cart"><i
                                        class="fa fa-shopping-cart"></i>
                                    Proceed</button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
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
    <x-cart-quantity />
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
        $(document).on('click', '#proceed-cart', function() {
            console.log('click');
        })

        // add quantity
        $(document).on('click', '.btn-add', function(e) {
            e.preventDefault();
            var id = $(this).val();
            $('#cart-modal').modal('show');
            $.ajax({
                url: "{{ route('cart-quantity') }}",
                data: {
                    id: id
                },
                type: 'GET',
                success: function(res) {
                    var item = res.item;
                    var quantity = res.quantity;
                    $('#item_id').val(id);
                    $('#item_name').val(item.name);
                    $('#item_quantity').val(quantity);
                }
            })
        })
        // submit quantity
        $(document).on('submit', '#update-quantity-form', function(e) {
            e.preventDefault();
            var form = $(this).serialize();
            $.ajax({
                url: "{{ route('update-cart-quantity') }}",
                data: form,
                type: 'POST',
                success: function(res) {
                    $('#cart-modal').modal('hide');
                    $('#cart-data').load(location.href + ' #cart-data');
                    $('#added').html(res.success);
                    $('#added').fadeIn();
                    $('#update-quantity-form')[0].reset();


                    // Hide success message after 1.5 seconds
                    setTimeout(function() {
                        $('#added').fadeOut('slow');
                    }, 1000); //2 seconds
                }
            })
        })
        $(document).on('click', '.btn-remove', function(e) {
            e.preventDefault();
            var id = $(this).val();
            if (confirm('Remove this item>')) {
                $.ajax({
                    url: "{{ route('remove-cart') }}",
                    data: {
                        id: id
                    },
                    type: 'get',
                    success: function(res) {
                        $('#cart-data').load(location.href + ' #cart-data');
                        $('#notFound').html(res.error);
                        $('#notFound').fadeIn();

                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#notFound').fadeOut('slow');
                        }, 1000); //2 seconds
                    }
                })
            }
        })
        $(document).on('input', '#payment', function() {
            var payment = $(this).val();
            var total_price = parseFloat($('#total_price').text().replace('P', '').replace(',', ''));

            // Check if payment is a valid number
            if (!isNaN(payment)) {
                var change = payment - total_price;
                $('#change').text(change.toFixed(2));
            } else {
                // Handle invalid input, e.g., non-numeric input
                $('#change').text('Invalid input');
            }
        });
    </script>
@endsection
