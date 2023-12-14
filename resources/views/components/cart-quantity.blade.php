<div class="modal fade" id="cart-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Item Quantity</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="alert alert-success my-2" id="message" style="display: none"></p>
                <p class="alert alert-danger show my-2" id="error-message" style="display: none"></p>

                <form id="update-quantity-form" method="POST" action="{{ url('update-quantity') }}">
                    @csrf
                    <input type="hidden" id="item_id" name="item_id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Item</label>
                        <input type="text" readonly name="name" id="item_name" class="form-control" required>

                    </div>
                    <div>
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" min="1" name="quantity" id="item_quantity" class="form-control"
                            required>
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
