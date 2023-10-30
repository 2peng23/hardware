@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between ">
        <h3>Inactive Users</h3>
        <!-- Button trigger modal -->

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ url('admin-users') }}" class="btn btn-primary ">Active Users</a>
        </div>
    </div>
    <x-success-message />
    <x-error-message />

    @if ($inactive->isEmpty())
        <p>No deactivated users.</p>
    @else
        <div class="table-responsive mt-5">
            <table class="table">
                <thead>
                    <tr class="text-center">
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $id = 1;
                    @endphp
                    @foreach ($inactive as $item)
                        <tr class="text-center">
                            <th scope="row">{{ $id }}</th>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>
                                @if ($item->usertype == 1)
                                    <p>Admin</p>
                                @elseif ($item->usertype == 2)
                                    <p>Manager</p>
                                @elseif($item->usertype == 3)
                                    <p>Staff</p>
                                @else
                                @endif
                            </td>
                            <td>
                                <p class="px-1 rounded-lg fs-6 text-white  bg-warning"> {{ $item->status }}</p>
                            </td>
                            <td>
                                <div class="flex justify-content-evenly  align-items-center ">

                                    <a href="#" data-route="{{ route('activate-user', $item->id) }}"
                                        class="bg-success px-2 py-1 rounded-lg text-decoration-none text-white activate-btn"
                                        data-item-id="{{ $item->id }}">Activate</a>
                                </div>
                            </td>
                        </tr>
                        @php
                            $id++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(document).ready(function() {
                $('.activate-btn').on('click', function(e) {
                    e.preventDefault();
                    var route = $(this).data('route');
                    var itemId = $(this).data('item-id');
                    $.ajax({
                        url: route,
                        type: 'GET',
                        success: function(data) {
                            $('#error-message').css('display', 'none');
                            $('#success-message').css('display', 'block');
                            $('#success-message').html(data.success);
                            // Redirect to admin-users route
                            window.location.href = '/inactive-user';
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            var errors = jqXHR.responseJSON.errors;
                            $('#success-message').css('display', 'none');
                            $('#error-message').css('display', 'block');
                            $('#error-message').html(errors);
                        }
                    });
                });
            });
        </script>
    @endif
@endsection
