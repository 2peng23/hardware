@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between ">
        <h1>Supplies</h1>
        <x-add-product :data="$data" />
    </div>
    <div class="d-flex justify-content-between mt-4">
        <div>
            <div class="input-group">
                <input type="text" name="product_name" class="form-control" placeholder='Search item or code'
                    id="searchProduct">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
        <div>
            <select name="product_category" id="productCategory" class="form-select">
                <option>Select Category</option>
                <option value="">All Products</option>
                @foreach ($data as $item)
                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <x-message />

    <x-ajax-message />
    <x-edit-product :data=$data />
    <x-edit-stock />
    <x-edit-critical />
    {{-- If product is empty it will show this --}}
    @if ($products->isEmpty())
        <p class="mt-5">No products available.</p>
    @else
        {{-- if there are products it will show this --}}
        <div class="table-responsive mt-5" id="table-data">
            <table class="table">
                <thead>
                    <tr class="text-center">
                        <th scope="col">Item Code</th>
                        <th scope="col">Item</th>
                        <th scope="col">Category</th>
                        <th scope="col">Price</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Critical Stock</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $id = 1;
                    @endphp
                    @foreach ($products as $item)
                        <tr class="text-center">
                            <th scope="row">{{ $item->item_id }}</th>
                            <td>{{ $item->name }}</td>
                            <td>
                                {{ $item->category }}
                            </td>
                            <td>{{ $item->price }}</td>
                            <td>
                                <button value="{{ $item->id }}" class="text-primary btn rounded p-0 editbtn">
                                    {{ $item->quantity }}
                                </button>
                            </td>

                            <td>
                                <button value="{{ $item->id }}" class="text-primary btn rounded p-0 criticalbtn">
                                    {{ $item->critical_stock }}
                                </button>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button value="{{ $item->id }}"
                                        class="btn-success btn-sm btn text-white rounded-lg editProduct">
                                        <i class="fa fa-pencil px-1 "></i>
                                    </button>
                                    <a href="{{ route('delete-product', $item->id) }}"
                                        onclick="return confirm('Are you sure you want to delete this product?')"
                                        class="bg-danger btn btn-danger btn-sm text-white rounded-lg">
                                        <i class="fa fa-trash px-1"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @php
                            $id++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
            {{ $products->links('vendor.pagination.bootstrap-5') }}
        </div>
    @endif
@endsection




@section('scripts')
    <script>
        //search
        $(document).ready(function() {
            // by name
            $(document).on('keyup', '#searchProduct', function(e) {
                e.preventDefault();
                let product_name = $('#searchProduct').val();
                console.log(product_name);
                $.ajax({
                    url: '{{ route('search-product') }}',
                    method: 'GET',
                    data: {
                        product_name: product_name
                    },
                    success: function(res) {
                        console.log(res.status);
                        $('#table-data').html(res)
                        if (res.status === 'Nothing found.') {
                            $('#table-data').html('<p>' + res.status + '</p>')
                        }
                    }
                })
            })
            // by category
            $(document).on('change', '#productCategory', function(e) {
                e.preventDefault();
                let product_category = $('#productCategory').val();
                console.log(product_category);
                $.ajax({
                    url: '{{ route('search-product') }}',
                    method: 'GET',
                    data: {
                        product_category: product_category
                    },
                    success: function(res) {
                        console.log(res.status);
                        $('#table-data').html(res)
                        if (res.status === 'Nothing found.') {
                            $('#table-data').html('<p>' + res.status + '</p>')
                        }
                    }
                })
            })
        })
        // add product
        $(document).ready(function() {
            $(document).on('submit', '#add-product', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/add-product',
                    data: $('#add-product').serialize(),
                    type: 'post',
                    success: function(result) {
                        console.log(result.success);
                        $('#ajax-error').css('display', 'none');
                        $('#ajax-success').css('display', 'block');
                        $('#ajax-success').html(result.success);
                        $('#add-product')[0].reset();
                        $('#exampleModal').modal('hide');
                        $('.table').load(location.href + ' .table');

                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#ajax-success').fadeOut('slow');
                        }, 1500);
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        var errorString = '';
                        $.each(errors, function(key, value) {
                            errorString += value + '<br>';
                        });
                        $('#success-message').css('display', 'none');
                        $('#error-message').css('display', 'block');
                        $('#error-message').html(errorString);
                    }
                });
            });
        });
        let timeoutId;

        function delayedSubmit(input) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                input.form.submit();
            }, 500);
        }
        // edit product
        $(document).ready(function() {
            $(document).on('click', '.editProduct', function() {
                var item_id = $(this).val();
                $('#editProduct').modal('show');

                $.ajax({
                    url: '/edit-product/' + item_id,
                    type: 'GET',
                    success: function(response) {
                        console.log(response);
                        $('#item_id2').val(item_id);
                        $('#name2').val(response.product.name);
                        $('#price2').val(response.product.price);
                        $('#category2').val(response.product.category);
                    }
                });
            });
        });

        // update product
        $(document).ready(function() {
            $(document).on('submit', '#update-product', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/update-product',
                    data: $('#update-product').serialize(),
                    type: 'put',
                    success: function(result) {
                        console.log(result.success);
                        $('#ajax-error').css('display', 'none');
                        $('#ajax-success').css('display', 'block');
                        $('#ajax-success').html(result.success);
                        $('#update-product')[0].reset();
                        $('#editProduct').modal('hide');
                        $('.table').load(location.href + ' .table');
                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#ajax-success').fadeOut('slow');
                        }, 1500);
                    },
                });
            });
        });
        // edit stock
        $(document).ready(function() {
            $(document).on('click', '.editbtn', function() {
                var item_id = $(this).val();
                $('#editStock').modal('show');

                $.ajax({
                    url: '/edit-stock/' + item_id,
                    type: 'GET',
                    success: function(response) {
                        $('#item_id').val(item_id);
                        $('#edit-name').val(response.product.name);
                        var beginningBalance = parseFloat(response.product
                            .quantity); // Assuming quantity is a number
                        $('#edit-quantity').val(
                            beginningBalance); // Update the beginning balance field

                        var quantityInput = $('#quantity');
                        quantityInput.val(""); // Clear the quantity field
                        $('#ending_balance').val(
                            beginningBalance); // Set the initial ending balance

                        quantityInput.on('input', function() {
                            var quantity = parseFloat($(this).val() ||
                                0); // If no value is input, default to 0
                            var endingBalance = beginningBalance + quantity;
                            $('#ending_balance').val(endingBalance);
                        });
                    }
                });
            });
        });

        //add stock

        $(document).ready(function() {
            $('#add-stock').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ url('add-stock') }}',
                    data: $('#add-stock').serialize(),
                    type: 'put',
                    success: function(result) {
                        console.log(result.success);
                        $('#ajax-error').css('display', 'none');
                        $('#ajax-success').css('display', 'block');
                        $('#ajax-success').html(result.success);
                        $('#add-stock')[0].reset();
                        $('#editStock').modal('hide');
                        $('.table').load(location.href + ' .table');
                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#ajax-success').fadeOut('slow');
                        }, 1500);
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        var errorString = '';
                        $.each(errors, function(key, value) {
                            errorString += value + '<br>';
                        });
                        $('#success-message').css('display', 'none');
                        $('#error-message').css('display', 'block');
                        $('#error-message').html(errorString);
                    }
                });
            });
        });

        // edit critical stock
        $(document).ready(function() {
            $(document).on('click', '.criticalbtn', function() {
                var item_id = $(this).val();
                $('#criticalModal').modal('show');

                $.ajax({
                    url: '/edit-critical/' + item_id,
                    type: 'GET',
                    success: function(response) {
                        console.log(response.product);
                        $('#critical_id').val(item_id);
                        $('#critical_name').val(response.product.name);
                        $('#critical_stock').val(response.product.critical_stock);
                    }
                });
            });
        });

        //add critical-stock

        $(document).ready(function() {
            $('#update-critical').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ url('update-critical') }}',
                    data: $('#update-critical').serialize(),
                    type: 'put',
                    success: function(result) {
                        console.log(result.success);
                        $('#ajax-error').css('display', 'none');
                        $('#ajax-success').css('display', 'block');
                        $('#ajax-success').html(result.success);
                        $('#update-critical')[0].reset();
                        $('#criticalModal').modal('hide');
                        $('.table').load(location.href + ' .table');
                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#ajax-success').fadeOut('slow');
                        }, 1500);
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        var errorString = '';
                        $.each(errors, function(key, value) {
                            errorString += value + '<br>';
                        });
                        $('#success-message').css('display', 'none');
                        $('#error-message').css('display', 'block');
                        $('#error-message').html(errorString);
                    }
                });
            });
        });
    </script>
@endsection
