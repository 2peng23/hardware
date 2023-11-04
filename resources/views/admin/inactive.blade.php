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

    <p id="inactive-user" style="display: none">There's no deactivated user.</p>
    <div class="table-responsive mt-5" id="inactive-table" style="display: none">
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
            <tbody id="table-body">

            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        function fetchInactiveUsers() {
            $.ajax({
                url: "/fetch-inactiveUsers",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log(data.users);
                    var inactiveUser = $('#inactive-user');
                    var inactiveTable = $('#inactive-table');

                    var users = data.users;
                    if (users.length <= 0) {
                        inactiveTable.hide();
                        inactiveUser.show();
                    } else {
                        inactiveTable.show();
                        inactiveUser.hide();
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
                            '<td> <p  class="px-1 bg-warning rounded-lg fs-6 text-white">' +
                            user.status + ' </p></td>' +
                            '<td><div class="d-flex justify-content-center gap-2 align-items-center">' +
                            '<a href="#" data-route="/activate-user/' + user.id +
                            '" class="bg-success btn px-2 py-1 rounded-lg text-decoration-none text-white activate-btn" data-item-id="' +
                            user.id + '">' +
                            'activate' +
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
            fetchInactiveUsers();
        });


        // activate
        $(document).ready(function() {
            fetchInactiveUsers();

            $(document).on('click', '.activate-btn', function(e) {
                e.preventDefault();
                var route = $(this).data('route');
                var itemId = $(this).data('item-id');

                $.ajax({
                    url: route,
                    type: 'GET', // Assuming you want to delete, use the appropriate HTTP method
                    success: function(data) {
                        $('#error-message')
                            .hide(); // Use .hide() instead of .css('display', 'none')
                        $('#success-message').show().html(data
                            .success); // Use .show() instead of .css('display', 'block')
                        fetchInactiveUsers();
                        // Hide success message after 1.5 seconds
                        setTimeout(function() {
                            $('#success-message').fadeOut('slow');
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
    </script>
@endsection
