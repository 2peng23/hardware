@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between ">
        <h3 class="text-muted">Users</h3>
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
    <x-ajax-message />
    <x-success-message />
    <x-error-message />
    <p id="active-user">There are no other users.</p>
    <div class="table-responsive mt-5">
        <table class="table" id="active-table">
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
            <tbody id="table-body">
            </tbody>
        </table>
    </div>

    <script>
        function fetchUsers() {
            $.ajax({
                url: "/fetch-users",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var users = data.users;
                    var activeUser = $('#active-user');
                    var activeTable = $('#active-table');

                    var users = data.users;
                    if (users.length <= 0) {
                        activeTable.hide();
                        activeUser.show();
                    } else {
                        activeTable.show();
                        activeUser.hide();
                    }
                    var tableBody = $('#table-body');
                    tableBody.empty(); // Clear existing data before appending new data
                    for (var i = 0; i < users.length; i++) {
                        var user = users[i];
                        var output = user.usertype == 1 ? 'admin' : 'staff';
                        var row = '<tr class="text-center">' +
                            '<th scope="row">' + (i + 1) + '</th>' +
                            '<td>' + user.name + '</td>' +
                            '<td>' + user.email + '</td>' +
                            '<td>' + output + '</td>' +
                            '<td>' + user.designation + '</td>' +
                            '<td> <p style="background-color: greenyellow" class="px-1 rounded-lg fs-6">' +
                            user.status + ' </p></td>' +
                            '<td><div class="d-flex justify-content-center gap-2 align-items-center">' +
                            '<button value="' + user.id +
                            '" class="bg-success btn px-2 py-1 rounded-lg text-decoration-none text-white editbtn">' +
                            '<i class="fa fa-pencil"></i>' +
                            '</button>' +
                            '<a href="#" data-route="/deact-user/' + user.id +
                            '" class="bg-warning btn px-2 py-1 rounded-lg text-decoration-none text-white deact-btn" data-item-id="' +
                            user.id + '">' +
                            '<i class="fa fa-archive"></i>' +
                            '</a>' +
                            '<a href="#" data-route="/delete-user/' + user.id +
                            '" onclick="return(\'Delete this user?\')" class="bg-danger btn px-2 py-1 rounded-lg text-decoration-none text-white delete-btn" data-item-id="' +
                            user.id + '">' +
                            '<i class="fa fa-trash"></i>' +
                            '</a>' +
                            '</div></td>' +
                            '</tr>';
                        tableBody.append(row);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }


        // Call the function fetchUsers when the document is ready
        $(document).ready(function() {
            fetchUsers();
        });
        // create user
        $(document).ready(function() {
            fetchUsers();
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
                        $('#exampleModal').modal('hide');
                        fetchUsers();
                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#success-message').fadeOut('slow');
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

        // // update-user

        $(document).ready(function() {
            fetchUsers();
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
                        $('#update-user')[0].reset();
                        $('#editModal').modal('hide');
                        fetchUsers();
                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#success-message').fadeOut('slow');
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
        // deactivate
        $(document).ready(function() {
            fetchUsers();

            $(document).on('click', '.deact-btn', function(e) {
                e.preventDefault();
                var route = $(this).data('route');
                var itemId = $(this).data('item-id');

                $.ajax({
                    url: route,
                    type: 'GET', // Assuming you want to delete, use the appropriate HTTP method
                    success: function(data) {
                        $('#error-message')
                            .hide(); // Use .hide() instead of .css('display', 'none')
                        $('#ajax-error').show().html(data
                            .success); // Use .show() instead of .css('display', 'block')
                        fetchUsers();
                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#ajax-error').fadeOut('slow');
                        }, 1500);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        var errors = jqXHR.responseJSON.errors;
                        $('#success-message')
                            .hide(); // Use .hide() instead of .css('display', 'none')
                        $('#error-message').show().html(
                            errors); // Use .show() instead of .css('display', 'block')
                    }
                });
            });
        });
        // delete
        $(document).ready(function() {
            fetchUsers();

            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var route = $(this).data('route');
                var itemId = $(this).data('item-id');

                if (confirm('Delete this user?')) {
                    $.ajax({
                        url: route,
                        type: 'DELETE', // Use the appropriate HTTP method for deletion
                        success: function(data) {
                            $('#error-message').hide();
                            $('#success-message').show().html(data.success);
                            fetchUsers();
                            // Hide success message after 1.5 seconds
                            setTimeout(function() {
                                $('#success-message').fadeOut('slow');
                            }, 1500);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            var errors = jqXHR.responseJSON.errors;
                            $('#success-message').hide();
                            $('#error-message').show().html(errors);
                        }
                    });
                }
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
