<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add new user</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="alert alert-success my-2" id="message" style="display: none"></p>
                <p class="alert alert-danger show my-2" id="error-message" style="display: none"></p>

                <form id="add-user" method="POST" action="{{ url('add-user') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" placeholder="Enter employee name" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="designation" class="form-label">Designation</label>
                        <input type="text" placeholder="Enter employee designation" name="designation"
                            id="designation" class="form-control" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="usertype" id="usertype">
                            <option value="1">Admin</option>
                            <option value="0">Staff</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" placeholder="Enter employee email" name="email" id="email"
                            class="form-control" aria-describedby="emailHelp">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            id="password_confirmation">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
