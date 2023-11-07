<div id="request-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <i class="uil-plus"></i> Request Item</h5>
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pb-3">
                {{-- <div class="row mb-3">
                    <label class="col-3">Choose Category</label>
                    <div class="col-9">
                        <select name="type" class="form-select">
                            @foreach ($category as $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
                <div class="alert alert-info">
                    <i class="fa fa-info-circle text-dark"></i> Input quantity of item(s) you want to request.
                </div>
                <div class="alert alert-danger" id="failed" style="display: none;">
                </div>
                <div class="alert alert-success" id="success" style="display: none;">
                </div>
                <div class="d-flex justify-content-end py-2">
                    <div>
                        <input type="text" id="product_name" name="product_name" class="form-control"
                            placeholder="Search item">
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 300px">
                    <table id="items-table" class="table table-striped" style="width: 100%">
                        <thead>
                            <tr class="text-center">
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Available Stock</th>
                                <th>Quantity</th>
                            </tr>

                        </thead>
                        <tbody id="tbody2">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
