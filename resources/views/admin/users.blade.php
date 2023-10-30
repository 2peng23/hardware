@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between ">
        <h3>Users</h3>
        <!-- Button trigger modal -->
        <button type="button" style="width: 100px" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            <i class="fa fa-user-plus text-white"></i>
        </button>

        <!-- Modal -->
        <x-add-user />
        <x-edit-user />
    </div>
    <div class="d-flex justify-content-end mt-3">
        <a href="{{ url('inactive-user') }}" class="btn btn-warning " style="width: 100px">
            <i class="fa fa-archive text-white"></i>
        </a>
    </div>
    <x-success-message />
    <x-error-message />
    <div class="table-responsive mt-5">
        <table class="table">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Designation</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $id = 1;
                @endphp
                @foreach ($users as $item)
                    <tr class="text-center">
                        <th scope="row">{{ $id }}</th>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>
                            @if ($item->usertype == 0)
                                <p>Staff</p>
                            @else
                                <p>Admin</p>
                            @endif
                        </td>
                        <td>{{ $item->designation }}</td>
                        <td>
                            <p style="background-color: greenyellow" class="px-1 rounded-lg fs-6">{{ $item->status }}</p>
                        </td>
                        <td>
                            @if (Auth::user()->id == $item->id)
                                <p class="p-1">current user</p>
                            @else
                                <div class="d-flex justify-content-center gap-2 align-items-center">
                                    <button value="{{ $item->id }}"
                                        class="bg-success btn px-2 py-1 rounded-lg text-decoration-none text-white editbtn">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <a href="#" data-route="{{ route('deact-user', $item->id) }}"
                                        class="bg-warning btn px-2 py-1 rounded-lg text-decoration-none text-white deact-btn"
                                        data-item-id="{{ $item->id }}">
                                        <i class="fa fa-archive"></i>
                                    </a>


                                    <a href="#" data-route="{{ route('delete-user', $item->id) }}"
                                        onclick="return confirm('Delete this user?')"
                                        class="bg-danger btn px-2 py-1 rounded-lg text-decoration-none text-white delete-btn"
                                        data-item-id="{{ $item->id }}">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @php
                        $id++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        // create user
        $(document).ready(function() {
            $('#add-user').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ url('add-user') }}',
                    data: $('#add-user').serialize(),
                    type: 'post',
                    success: function(result) {
                        $('#error-message').css('display', 'none');
                        $('#success-message').css('display', 'block');
                        $('#success-message').html(result.success);
                        $('#add-user')[0].reset();
                        window.location.href = '/admin-users';
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

        // // update-user

        $(document).ready(function() {
            $('#update-user').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ url('update-user') }}',
                    data: $('#update-user').serialize(),
                    type: 'put',
                    success: function(result) {
                        $('#error-message').css('display', 'none');
                        $('#success-message').css('display', 'block');
                        $('#success-message').html(result.success);
                        $('#add-user')[0].reset();
                        window.location.href = '/admin-users';
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
        // deactivate
        $(document).ready(function() {
            $('.deact-btn').on('click', function(e) {
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
                        window.location.href = '/admin-users';
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
        // delete
        $(document).ready(function() {
            $('.delete-btn').on('click', function(e) {
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
                        window.location.href = '/admin-users';
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

        // edit
        $(document).ready(function() {
            $(document).on('click', '.editbtn', function() {
                var item_id = $(this).val();
                $('#editModal').modal('show');

                $.ajax({
                    url: '/edit-user/' + item_id,
                    type: 'GET',
                    success: function(response) {
                        $('#item_id').val(item_id);
                        $('#edit-name').val(response.user.name);
                        $('#edit-email').val(response.user.email);
                        $('#edit-usertype').val(response.user.usertype);
                        $('#edit-designation').val(response.user.designation);
                    }
                });
            });
        });
    </script>
@endsection
