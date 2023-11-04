<table class="table" id="inventoryTable">
    <thead>
        <tr class="text-center">
            <th scope="col" class="text-muted">Item Code</th>
            <th scope="col" class="text-muted">Item</th>
            <th scope="col" class="text-muted">Category</th>
            <th scope="col" class="text-muted">Stocks</th>
            <th scope="col" class="text-muted">Status</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        @php
            $id = 1;
        @endphp
        @foreach ($products as $item)
            <tr class="text-center">
                <td>{{ $item->item_id }}</td>
                <td>{{ $item->name }}</td>
                <td>
                    {{ $item->category }}
                </td>
                <td>{{ $item->quantity }}</td>
                <td style="width: 120px">
                    @if ($item->quantity <= 0)
                        <p style="background-color: rgb(231, 198, 191); font-size:12px;"
                            class='rounded  mx-auto text-danger py-1'>
                            Unavailable</p>
                    @elseif($item->quantity <= $item->critical_stock)
                        <p style="background-color: rgb(231, 230, 191); font-size:12px;"
                            class='rounded  mx-auto text-warning py-1'>Critical</p>
                    @else
                        <p style="background-color: rgb(192, 231, 191); font-size:12px;"
                            class='rounded  mx-auto text-success py-1'>
                            Available</p>
                    @endif
                </td>
            </tr>
            @php
                $id++;
            @endphp
        @endforeach
    </tbody>
</table>
{{ $products->links('vendor.pagination.bootstrap-5') }}
