@extends('layouts.admin')



@section('content')
    <h1>Inventory</h1>

    <div class="d-flex justify-content-between align-items-center mt-2  p-3">
        <a href="#">
            <i class="fa fa-print rounded-circle text-white bg-secondary p-3" id="print"></i>
        </a>
        <form id="dateForm" action="{{ url('inventory') }}" method="GET">
            @csrf
            <input type="text" name="daterange" id="daterange" class="form-control text-center" style="width: 300px"
                value="{{ $date_range }}">
        </form>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <form action="{{ url('inventory') }}" method="GET">
            @csrf
            <div class="input-group">
                <input type="text" id="searchInput" name="product_name" value="{{ $product_name }}" class="form-control"
                    placeholder='Search item' oninput="delayedSubmit(this)">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </form>
        <form action="{{ url('inventory') }}" method="GET">
            @csrf
            <select name="product_category" id="" class="form-select" oninput="delayedSubmit(this)">
                <option>Select Category</option>
                <option value="">All Products</option>
                @foreach ($category as $item)
                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- If product is empty it will show this --}}
    @if ($products->isEmpty())
        <p class="mt-5">No products available.</p>
    @else
        {{-- if there are products it will show this --}}
        <div class="table-responsive mt-1">
            <table class="table" id="inventoryTable">
                <thead>
                    <tr class="text-center">
                        <th scope="col" class="text-muted">Item Code</th>
                        <th scope="col" class="text-muted">Item</th>
                        <th scope="col" class="text-muted">Category</th>
                        <th scope="col" class="text-muted">Stocks</th>
                        <th scope="col" class="text-muted">Status</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @php
                        $id = 1;
                    @endphp
                    @foreach ($products as $item)
                        <tr class="text-center">
                            <td>{{ $item->item_id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                {{ $item->category }}
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td style="width: 120px">
                                @if ($item->quantity <= 0)
                                    <p style="background-color: rgb(231, 198, 191); font-size:12px;"
                                        class='rounded  mx-auto text-danger py-1'>
                                        Unavailable</p>
                                @elseif($item->quantity <= $item->critical_stock)
                                    <p style="background-color: rgb(231, 230, 191); font-size:12px;"
                                        class='rounded  mx-auto text-warning py-1'>Critical</p>
                                @else
                                    <p style="background-color: rgb(192, 231, 191); font-size:12px;"
                                        class='rounded  mx-auto text-success py-1'>
                                        Available</p>
                                @endif
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
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });

            $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                document.getElementById('dateForm').submit();
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
    </script>

    {{-- print --}}
    <script>
        document.getElementById('print').addEventListener('click', function() {
            let title = document.querySelector('h1').innerText;
            let printContents = '<h1>' + title + '</h1>' + document.getElementById('inventoryTable').outerHTML;
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
