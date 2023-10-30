<div class="modal fade" id="criticalModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Critical Stock Value</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="alert alert-success my-2" id="success-message" style="display: none"></p>
                <p class="alert alert-danger show my-2" id="error-message" style="display: none"></p>

                <form id="update-critical" method="POST" action="{{ url('update-critical') }}">
                    @csrf
                    @method('PUT')
                    <input type="text" hidden id="critical_id" name="item_id">
                    <div class="mb-3">
                        <label for="critical_name" class="form-label">Item Name</label>
                        <input readonly class="form-control" id="critical_name">
                    </div>
                    <div class="mb-3">
                        <label for="critical_stock" class="form-label">Critical Stock</label>
                        <input type="number" min="1" placeholder="Enter critical stock" name="critical_stock"
                            id="critical_stock" class="form-control" aria-describedby="emailHelp">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Set Critical Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
