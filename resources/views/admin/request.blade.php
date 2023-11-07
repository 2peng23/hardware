@extends('layouts.admin')
@section('content')
    <h3 class="text-muted">Request</h3>
    <div class="d-flex my-3 justify-content-end ">
        <div>
            <input type="text" class="form-control" placeholder="search" name="name_request" id="name_request">
        </div>
    </div>
    <div id="data-table">
        @if ($transaction->isEmpty())
            <p>No transaction available</p>
        @else
            <div class="table-responsive mt-3">
                <table class="table  ">
                    <thead>
                        <tr class="text-center">
                            <th></th>
                            <th>Request Date</th>
                            <th>Transaction ID</th>
                            <th>Requestor</th>
                            <th>Office</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="request-body">
                        @foreach ($transaction as $item)
                            @php
                                $user = App\Models\User::where('id', $item->user_id)->first();
                                $product = App\Models\Product::where('id', $item->item_id)->first();
                            @endphp
                            <tr class="text-center">
                                <td><i class="fa fa-angle-right show-info"></i></td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}</td>
                                <td>{{ $item->transaction_id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->designation }}</td>
                                <td>
                                    @if ($item->status == 'pending')
                                        <button value="{{ $item->id }}" class="btn text-success btn-accept">
                                            <i class="fa fa-thumbs-up text-success"></i> accept
                                        </button>
                                        <button value="{{ $item->id }}" class="btn text-danger btn-decline">
                                            <i class="fa fa-thumbs-down text-danger"></i> decline
                                        </button>
                                    @elseif($item->status == 'approved')
                                        <p style="color: green" class="fw-bold">processing</p>
                                    @elseif($item->status == 'declined')
                                        <p class="fw-bold text-danger">declined</p>
                                    @else
                                        <p class="fw-bold text-primary">released</p>
                                    @endif
                                </td>
                            </tr>
                            <tr style="display: none" class="infoTable">
                                <td colspan="6" style="border: 2px solid rgba(82, 73, 73, 0.21)">
                                    <h4 class="text-center bg-secondary text-white py-2">Transaction Details</h4>
                                    <table class="table table-bordered ">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th>Quantity</th>
                                                @if ($item->status == 'approved')
                                                    <th>Action</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($product)
                                                <tr class="text-center">
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    @if ($item->status == 'approved')
                                                        <td><button value="{{ $item->id }}"
                                                                class="btn btn-primary btn-release">release</button></td>
                                                    @endif
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $transaction->links('vendor.pagination.bootstrap-5') }}
        @endif
    </div>
    <x-ajax-message />
@endsection
@section('scripts')
    <script>
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


            $('#data-table').on('click', '.btn-accept', function() {
                id = $(this).val();
                $.ajax({
                    url: "{{ route('accept') }}",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(result) {
                        $('#ajax-success').css('display', 'block');
                        $('#ajax-success').html(result.success);
                        $('#data-table').load(location.href + ' #data-table', function() {
                            // Call the function on document ready
                            handleToggleInfo();
                        });

                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#ajax-success').fadeOut('slow');
                        }, 1500);
                    }
                })
            })

            $('#data-table').on('click', '.btn-decline', function() {
                id = $(this).val();
                $.ajax({
                    url: "{{ route('decline') }}",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(result) {
                        $('#ajax-error').css('display', 'block');
                        $('#ajax-error').html(result.success);
                        $('#data-table').load(location.href + ' #data-table', function() {
                            // Call the function on document ready
                            handleToggleInfo();
                        });

                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#ajax-error').fadeOut('slow');
                        }, 1500);
                    }
                })
            })

            $('#data-table').on('click', '.btn-release', function() {
                id = $(this).val();
                $.ajax({
                    url: "{{ route('release') }}",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(result) {
                        $('#ajax-success').css('display', 'block');
                        $('#ajax-success').html(result.success);
                        $('#data-table').load(location.href + ' #data-table', function() {
                            // Call the function on document ready
                            handleToggleInfo();
                        });

                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#ajax-success').fadeOut('slow');
                        }, 1500);
                    }
                })
            })
        })
    </script>
@endsection
