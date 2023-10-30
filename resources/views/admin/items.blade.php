@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between ">
        <h1>Supplies</h1>
        <x-add-product :data="$data" />
    </div>
    <div class="d-flex justify-content-between mt-4">
        <form action="{{ url('items') }}" method="GET">
            @csrf
            <div class="input-group">
                <input type="text" name="product_name" class="form-control" placeholder='Search item'
                    oninput="delayedSubmit(this)">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </form>
        <form action="{{ url('items') }}" method="GET">
            @csrf
            <select name="product_category" id="" class="form-select" oninput="delayedSubmit(this)">
                <option>Select Category</option>
                <option value="">All Products</option>
                @foreach ($data as $item)
                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if (session('message'))
        <div class="alert alert-success alert-dismissible mt-2">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-warning alert-dismissible mt-2">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <x-success-message />
    <x-error-message />
    <x-edit-stock />
    <x-edit-critical />
    {{-- If product is empty it will show this --}}
    @if ($products->isEmpty())
        <p class="mt-5">No products available.</p>
    @else
        {{-- if there are products it will show this --}}
        <div class="table-responsive mt-5">
            <table class="table">
                <thead>
                    <tr class="text-center">
                        <th scope="col">#</th>
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
                            <th scope="row">{{ $id }}</th>
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
                                    <a href="" class="bg-success text-white rounded-lg">
                                        <i class="fa fa-pencil px-1"></i>
                                    </a>
                                    <a href="" class=" bg-warning text-white rounded-lg">
                                        <i class="fa fa-archive px-1"></i>
                                    </a>
                                    <a href="" class="bg-danger text-white rounded-lg">
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




    <script>
        // create product
        $(document).ready(function() {
            $('#add-product').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ url('add-product') }}',
                    data: $('#add-product').serialize(),
                    type: 'post',
                    success: function(result) {
                        $('#error-message').css('display', 'none');
                        $('#success-message').css('display', 'block');
                        $('#success-message').html(result.success);
                        $('#add-product')[0].reset();
                        window.location.href = '/items';
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
        // delay submission of form 
        let timeoutId;

        function delayedSubmit(input) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                input.form.submit();
            }, 500);
        }
        // edit stock
        $(document).ready(function() {
            $(document).on('click', '.editbtn', function() {
                var item_id = $(this).val();
                $('#editModal').modal('show');

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
                        $('#error-message').css('display', 'none');
                        $('#success-message').css('display', 'block');
                        $('#success-message').html(result.success);
                        $('#add-stock')[0].reset();
                        window.location.href = '/items';
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

        //add stock

        $(document).ready(function() {
            $('#update-critical').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ url('update-critical') }}',
                    data: $('#update-critical').serialize(),
                    type: 'put',
                    success: function(result) {
                        console.log(result.success);
                        $('#error-message').css('display', 'none');
                        $('#success-message').css('display', 'block');
                        $('#success-message').html(result.success);
                        $('#update-critical')[0].reset();
                        window.location.href = '/items';
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
