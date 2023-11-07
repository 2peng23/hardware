@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between pt-4">
        <h3 class="text-muted">Unavailable Items</h3>
        <button class="btn btn-sm btn-success available">
            <i class="fa fa-recycle fs-2 text-white"></i>
        </button>
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
    <div class="table3" id="table-data">
        @if ($products->isEmpty())
            <p class="mt-5">No unavailable products.</p>
        @else
            {{-- if there are products it will show this --}}
            <div class="table-responsive mt-3">
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
                                    <p value="{{ $item->id }}" class="text-dark  rounded p-0 editbtn">
                                        {{ $item->quantity }}
                                    </p>
                                </td>

                                <td>
                                    <p value="{{ $item->id }}" class="text-dark  rounded p-0 criticalbtn">
                                        {{ $item->critical_stock }}
                                    </p>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                            data-bs-target="#available">
                                            <i class="fa fa-recycle text-white"></i>
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="available" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                            <i class="fa fa-recycle text-success"></i>
                                                        </h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="fs-3">Make this product available?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button value="{{ $item->id }}" type="button"
                                                            class="btn btn-success btn-available">Confirm</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
    </div>
    <x-ajax-message />
@endsection
@section('scripts')
    <script>
        // make product available
        $(document).ready(function() {
            $('#table-data').on('click', '.btn-available', function() {
                var item_id = $(this).val();
                $.ajax({
                    url: "{{ route('available') }}",
                    type: 'GET',
                    data: {
                        item_id: item_id
                    },
                    success: function(result) {
                        console.log(result.success);
                        $('#ajax-error').css('display', 'none');
                        $('#ajax-success').css('display', 'block');
                        $('#ajax-success').html(result.success);
                        $('#available').modal('hide');
                        $('#table-data').load(location.href + ' #table-data');
                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#ajax-success').fadeOut('slow');
                        }, 1500);
                    },
                });
            });

            $('.available').on('click', function(e) {
                e.preventDefault();
                window.location.href = "{{ route('items') }}"
            });
        });
    </script>
@endsection
