@extends('layouts.admin')



@section('content')
    <h3 class="text-muted">Inventory</h3>

    <div class="d-flex justify-content-between align-items-center mt-2  p-3">
        <a href="#">
            <i class="fa fa-print rounded-circle text-white bg-secondary p-3" id="print"></i>
        </a>
        <input type="text" name="daterange" id="daterange" class="form-control text-center" style="width: 300px"
            value="{{ $date_range }}">


    </div>

    <div class="d-flex justify-content-between mt-4">
        <div>
            <div class="input-group">
                <input type="text" value="{{ $product_name }}" name="product_name" class="form-control"
                    placeholder='Search item or code' id="searchProduct">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
        <div>
            <select value="{{ $product_category }}" name="product_category" id="productCategory" class="form-select">
                <option>Select Category</option>
                <option value="">All Products</option>
                @foreach ($category as $item)
                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- If product is empty it will show this --}}
    @if ($products->isEmpty())
        <p class="mt-5">No products available.</p>
    @else
        {{-- if there are products it will show this --}}
        <div class="table-responsive mt-1" id="table-data2">
            <table class="table" id="inventoryTable">
                <thead>
                    <tr class="text-center">
                        <th></th>
                        <th scope="col" class="text-muted">Item Code</th>
                        <th scope="col" class="text-muted">Item</th>
                        <th scope="col" class="text-muted">Category</th>
                        <th scope="col" class="text-muted">Stocks</th>
                        <th scope="col" class="text-muted">Status</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @foreach ($products as $item)
                        <tr class="text-center">
                            <td><i class="fa fa-angle-right show-info"></i></td>
                            <td>{{ $item->item_id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td style="width: 120px">
                                @if ($item->quantity <= 0)
                                    <p style="background-color: rgb(231, 198, 191); font-size:12px;"
                                        class='rounded  mx-auto text-danger py-1'>Unavailable</p>
                                @elseif($item->quantity <= $item->critical_stock)
                                    <p style="background-color: rgb(231, 230, 191); font-size:12px;"
                                        class='rounded  mx-auto text-warning py-1'>Critical</p>
                                @else
                                    <p style="background-color: rgb(192, 231, 191); font-size:12px;"
                                        class='rounded  mx-auto text-success py-1'>Available</p>
                                @endif
                            </td>
                        </tr>
                        <tr style="display: none" class="infoTable">
                            <td colspan="6" style="border: 2px solid rgba(82, 73, 73, 0.21)">
                                <h4 class="text-center bg-secondary text-white py-2">Stock Card</h4>


                                <table class="table">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Date</th>
                                            <th>Item</th>
                                            <th>Quantity Delivered</th>
                                            <th>Beginning Balance</th>
                                            <th>Issued</th>
                                            <th>Ending Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $stocks = App\Models\Stock::where('item_id', $item->id)
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                        @endphp
                                        @foreach ($stocks as $stock)
                                            <tr class="text-center">
                                                <td class="text-muted">
                                                    {{ \Carbon\Carbon::parse($stock->created_at)->format('M d, Y') }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $stock->quantity }}</td>
                                                <td>{{ $stock->beginning_balance }}</td>
                                                <td>{{ $stock->issued }}</td>
                                                <td>{{ $stock->ending_balance }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </td>
                        </tr>
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

            // Define a function for handling the toggle event
            function handleToggleInfo() {
                $('.show-info').on('click', function() {
                    var info_table = $(this).closest('tr').next('.infoTable');
                    var toggle_icon = $(this);
                    if (info_table.css('display') == 'none') {
                        info_table.fadeIn();
                        toggle_icon.removeClass('fa-angle-right').addClass('fa-angle-down');
                    } else {
                        info_table.hide();
                        toggle_icon.removeClass('fa-angle-down').addClass('fa-angle-right');
                    }
                });
            }

            // Call the function on document ready
            handleToggleInfo();
            // by name
            $(document).on('keyup', '#searchProduct', function(e) {
                e.preventDefault();
                let product_name = $('#searchProduct').val();
                console.log(product_name);
                $.ajax({
                    url: '{{ route('inventory') }}',
                    method: 'GET',
                    data: {
                        product_name: product_name
                    },
                    success: function(response) {
                        console.log(response);
                        $('#table-data2').html($(response).find('#table-data2')
                            .html()); // Replace content of #table-data2
                        handleToggleInfo();
                        if (response.status === 'Nothing found.') {
                            $('#table-data2').html('<p class="mt-2 text-danger">' + response
                                .status +
                                '</p>')
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
                    url: '{{ route('inventory') }}',
                    method: 'GET',
                    data: {
                        product_category: product_category
                    },
                    success: function(response) {
                        console.log(response);
                        $('#table-data2').html($(response).find('#table-data2')
                            .html()); // Replace content of #table-data2
                        if (response.status === 'Nothing found.') {
                            $('#table-data2').html('<p class="mt-2 text-danger">' + response
                                .status +
                                '</p>')
                        }
                    }
                })
            })
            // by date
            $(document).on('change', '#daterange', function(e) {
                e.preventDefault();
                let date_range = $('#daterange').val();
                console.log(date_range);
                $.ajax({
                    url: '{{ route('inventory') }}',
                    method: 'GET',
                    data: {
                        date_range: date_range
                    },
                    success: function(response) {
                        console.log(response);
                        $('#table-data2').html($(response).find('#table-data2')
                            .html()); // Replace content of #table-data2
                        if (response.status === 'Nothing found.') {
                            $('#table-data2').html('<p class="mt-2 text-danger">' + response
                                .status +
                                '</p>')
                        }
                    }
                })
            })
        })

        // date
        $('input[name="daterange"]').daterangepicker({
            opens: 'left'
        });

        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {});
    </script>

    {{-- print --}}
    <script>
        document.getElementById('print').addEventListener('click', function() {
            let title = document.querySelector('h3').innerText;
            let printContents = '<h3>' + title + '</h3>' + document.getElementById('inventoryTable').outerHTML;
            let originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        });
        window.onafterprint = function() {
            location.reload();
        };
    </script>
@endsection
