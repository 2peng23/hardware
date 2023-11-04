@extends('layouts.staff')
@section('content')
    @if (session('message'))
        <div style="top: 10px; right:0;"
            class="alert alert-success alert-dismissible mt-2 text-center animate__animated animate__bounceInDown position-absolute">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div style="top: 10px; right:0;"
            class="alert alert-warning alert-dismissible mt-2 text-center animate__animated animate__bounceInDown position-absolute">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif



    <div class="card" id="table-content">
        <div class="card-header">
            <div class="float-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#request-modal">
                    <i class="fa fa-arrow-up animate__animated animate__shakeY infinity"></i> Request Item
                </button>
            </div>
            <h5><strong>My Request</strong></h5>
        </div>
        <div class="card-body table-responsive">
            <table id="requests-table" class="table table-striped table2" style="width: 100%">
                <thead>
                    <tr>
                        <th></th>
                        <th class="text-center">Request Date</th>
                        <th class="text-center">Transaction ID</th>
                        <th class="text-center">Requestor</th>
                        <th class="text-center">Office</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction as $item)
                        <tr class="text-center">
                            <td><i class="fa fa-angle-right toggleInfo"></i></td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}</td>
                            <td class="text-muted">{{ $item->transaction_id }}</td>
                            <td class="text-muted">{{ Auth::user()->name }}</td>
                            <td class="text-muted">{{ Auth::user()->designation }}</td>

                        </tr>
                        <tr class="info-table" style="display: none">
                            <td colspan="5">
                                <table class="table table-bordered">
                                    <thead class="text-center ">
                                        <tr>
                                            <th>Item</th>
                                            <th>Category</th>
                                            <th>Quantity</th>
                                            <th class="text-center">Status</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center text-muted">
                                            @php
                                                $product = App\Models\Product::where('id', $item->item_id)->first();
                                            @endphp
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-muted">
                                                @if ($item->status == 'declined')
                                                    <p style="background-color: rgb(231, 198, 191); font-size:12px;"
                                                        class='rounded  mx-auto text-danger p-1'>
                                                        {{ $item->status }}</p>
                                                @elseif($item->status == 'pending')
                                                    <p style="background-color: rgb(231, 230, 191); font-size:12px;"
                                                        class='rounded  mx-auto text-warning p-1'>{{ $item->status }}</p>
                                                @elseif($item->status == 'approved')
                                                    <p style="background-color: rgb(192, 231, 191); font-size:12px;"
                                                        class='rounded  mx-auto text-success p-1'>
                                                        on-process</p>
                                                @else
                                                    <p style="background-color: rgb(191, 220, 231); font-size:12px;"
                                                        class='rounded  mx-auto text-primary p-1'>
                                                        {{ $item->status }}</p>
                                                @endif
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $transaction->links('vendor.pagination.bootstrap-5') }}
    </div>

    <x-add-request :category=$category :products=$products />
    {{-- <x-view-transaction /> --}}
    <x-ajax-message />
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Define a function for handling the toggle event
            function handleToggleInfo() {
                $('.toggleInfo').on('click', function() {
                    var info_table = $(this).closest('tr').next('.info-table');
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

            // // create-transaction
            // $('form[id^="add-request-"]').submit(function(e) {
            //     e.preventDefault();
            //     var form = $(this);
            //     submitForm(form);
            // });

            // Function to submit the form via AJAX
            function submitForm(form) {
                $.ajax({
                    url: form.attr('action'),
                    data: form.serialize(),
                    type: 'post',
                    success: function(result) {
                        if (result.success) {
                            $('#ajax-success').css('display', 'block');
                            $('#ajax-success').html(result.success);
                        } else {
                            $('#ajax-error').css('display', 'block');
                            $('#ajax-error').html(result.failed);
                        }
                        form[0].reset();
                        // $('#request-modal').modal('hide');
                        $('#table-content').load(window.location.href + ' #table-content',
                            function() {
                                // Call the function again to reattach the event listener
                                handleToggleInfo();
                            });

                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#ajax-success').fadeOut('slow');
                            $('#ajax-error').fadeOut('slow');
                        }, 2000); //2 seconds
                    },
                    error: function(xhr, status, error) {
                        // Handling errors and displaying error messages
                        var errors = xhr.responseJSON.errors;
                        var errorString = '';
                        $.each(errors, function(key, value) {
                            errorString += value + '<br>';
                        });
                        $('#ajax-success').hide();
                        $('#ajax-error').show();
                        $('#ajax-error').html(errorString);
                    }
                });
            }

            // Handle form submission on button click
            $(document).on('click', '.requestbutton', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                submitForm(form);
            });



            fetchProduct();

            function fetchProduct(product_name = '') {
                $.ajax({
                    url: '{{ route('fetch-product') }}',
                    type: 'get',
                    data: {
                        product_name: product_name
                    },
                    success: function(res) {
                        // console.log(res);
                        $('#tbody2').html(res.products);
                    }
                })
            };

            $('#product_name').on('keyup', function() {
                let product_name = $('#product_name').val();
                fetchProduct(product_name);
            })


        });
    </script>
@endsection
