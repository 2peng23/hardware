{{-- <button type="button" style="width: 120px" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Product +
</button> --}}

<div class="modal fade" id="editProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add new product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="alert alert-success my-2" id="success-message" style="display: none"></p>
                <p class="alert alert-danger show my-2" id="error-message" style="display: none"></p>

                <form id="update-product" method="POST" action="{{ url('update-product') }}">
                    @csrf
                    @method('PUT')
                    <input type="text" name="item_id" id="item_id2" hidden>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="name2">

                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" min="0" class="form-control" id="price2">

                    </div>
                    {{-- <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" min="0" class="form-control">

                    </div> --}}
                    <div class="mb-2">
                        <label for="category" class="form-label"></label>
                        <select name="category" id="category2" class="form-select">
                            @foreach ($data as $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
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
