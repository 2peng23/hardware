<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="alert alert-success my-2" id="message" style="display: none"></p>
                <p class="alert alert-danger show my-2" id="error-message" style="display: none"></p>

                <form id="update-user" method="POST" action="{{ url('update-user') }}">
                    @csrf
                    @method('PUT')
                    <input type="text" hidden id="item_id" name="item_id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" placeholder="Enter employee name" name="name" class="form-control"
                            id="edit-name">
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Email address</label>
                        <input type="email" placeholder="Enter employee email" name="email" id="edit-email"
                            class="form-control" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="usertype" id="edit-usertype">
                            <option value="1">Admin</option>
                            <option value="0">Staff</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="designation" class="form-label">Designation</label>
                        <input type="text" placeholder="Enter employee designation" name="designation"
                            id="edit-designation" class="form-control" aria-describedby="emailHelp">
                    </div>

                    <div class="mb-3">
                        <label for="edit-password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="edit-password" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            id="edit-password_confirmation" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
