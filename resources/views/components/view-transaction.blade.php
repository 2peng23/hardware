<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered m-auto" style="max-width: 90%; width:90%;max-height:700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Transaction Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body table-responsive ">
                <table class="table table-bordered ">
                    <thead>
                        <tr class="text-center fw-bold ">
                            <th scope="col">Request Date</th>
                            <th scope="col">Transaction ID</th>
                            <th scope="col">Requestor</th>
                            <th scope="col">Office</th>
                            <th scope="col">Status</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td id="date"></td>
                            <td id="trans_id"></td>
                            <td id="requestor"></td>
                            <td id="office"></td>
                            <td id="status"></td>
                            <td id="item_name"></td>
                            <td id="category"></td>
                            <td id="quantity"></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
