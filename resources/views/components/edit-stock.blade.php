<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Restock Item</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="alert alert-success my-2" id="success-message" style="display: none"></p>
                <p class="alert alert-danger show my-2" id="error-message" style="display: none"></p>

                <form id="add-stock" method="POST" action="{{ url('add-stock') }}">
                    @csrf
                    @method('PUT')
                    <input type="text" hidden id="item_id" name="item_id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Item Name</label>
                        <input readonly class="form-control" id="edit-name">
                    </div>
                    <div class="mb-3">
                        <label for="edit-quantity" class="form-label">Beginning Balance</label>
                        <input readonly name="beginning_balance" class="form-control" id="edit-quantity">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" min="1" placeholder="Enter quantity to restock" name="quantity"
                            id="quantity" class="form-control" aria-describedby="emailHelp" required>
                    </div>
                    <div class="mb-3">
                        <label for="ending_balance" class="form-label">Ending Balance</label>
                        <input readonly name="ending_balance" value="" class="form-control" id="ending_balance">
                    </div>

                    <div class="mb-3">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" name="supplier" placeholder="Enter supplier name" class="form-control"
                            id="supplier" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Restock </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
