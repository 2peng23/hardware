@extends('layouts.staff')
@section('content')
    <div class="container" id="print-container">

        <div class="d-flex justify-content-between " id="title">
            <div>
                <h4>ABC Hardware</h4>
                <p>Poblacion Bansud, Oriental Mindoro</p>
                <p>09876543212</p>
            </div>
            <div>
                <h4>Sales Invoice</h4>
                <p>Invoice No. <span>ABC09876656</span></p>
                <p>Date: {{ date('F j, Y') }}</p>
            </div>
        </div>
        <div class="table-responsive mt-5">
            <table class="table">
                <thead>
                    <tr class="text-center">
                        <th>Product Code</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price(per piece)</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_price = 0; // Initialize total price
                    @endphp

                    @foreach ($data->product_id as $key => $productId)
                        @php
                            $item = App\Models\Product::where('id', $productId)->first();
                            $total_price += $item->price * $data->product_quantity[$key]; // Add to total price
                        @endphp
                        <tr class="text-center">
                            <td>{{ $item->barcode }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $data->product_quantity[$key] }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->price * $data->product_quantity[$key] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <div id="print-div">
                <button class="btn btn-primary" id="print-btn">
                    <i class="fa fa-print"></i>
                    Print
                </button>
            </div>
            <div>
                <p class="fw-bold">Payment: <span>&#8369;
                        {{ number_format($data->payment, 2) }}</span></p>
                <p class="fw-bold">Total Amount: <span>&#8369;
                        {{ number_format($total_price, 2) }}</span></p>
                <p class="fw-bold">Change: <span>&#8369;
                        {{ number_format($data->payment - $total_price, 2) }}</span></p>
            </div>
        </div>
        <p class=" font-italic text-center">
            <small>Thank you for choosing our service.</small>
        </p>

    </div>
@endsection
@section('scripts')
    <script>
        document.getElementById('print-btn').addEventListener('click', function() {
            // Hide the print-div
            document.getElementById('print-div').style.display = 'none';

            // Get the HTML content to print
            let printContents = document.getElementById('print-container').outerHTML;

            // Store the original body content
            let originalContents = document.body.innerHTML;

            // Set the body content to the content to be printed
            document.body.innerHTML = printContents;

            // Print the page
            window.print();

            // Restore the original body content
            document.body.innerHTML = originalContents;

            // Show the print-div again
            document.getElementById('print-div').style.display = 'block';
        });

        window.onafterprint = function() {
            // Reload the page after printing is done
            location.reload();
            // location.href = '/point-of-sale';
        };
    </script>
@endsection
