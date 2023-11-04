<div id="request-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <i class="uil-plus"></i> Request Item</h5>
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pb-3">
                <div class="row mb-3">
                    <label class="col-3">Choose Category</label>
                    <div class="col-9">
                        <select name="type" class="form-select">
                            @foreach ($category as $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="uil-info-circle"></i> Input quantity of item(s) you want to request.
                </div>
                <div class="table-responsive" style="max-height: 300px">
                    <table id="items-table" class="table table-striped" style="width: 100%">
                        <thead>
                            <tr class="text-center">
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $item)
                                <tr class="text-center">
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        <form id="add-request-{{ $item->id }}" action="{{ route('add-request') }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <input required type="number" name="quantity" class="form-control"
                                                min="1" max="{{ $item->quantity }}"
                                                @if ($item->quantity <= 0) disabled @endif>
                                    </td>
                                    <td>
                                        @if ($item->quantity <= 0)
                                            <p class="text-danger">unavailable</p>
                                        @else
                                            <button type="submit" class="btn-sm btn btn-success">request</button>
                                        @endif
                                    </td>
                                    </form>
                                </tr>
                            @endforeach
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
