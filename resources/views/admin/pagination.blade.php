<table class="table">
    <thead>
        <tr class="text-center">
            <th scope="col">Item Code</th>
            <th scope="col">Item</th>
            <th scope="col">Category</th>
            <th scope="col">Price</th>
            <th scope="col">Stock</th>
            <th scope="col">Critical Stock</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $id = 1;
        @endphp
        @foreach ($products as $item)
            <tr class="text-center">
                <th scope="row">{{ $item->item_id }}</th>
                <td>{{ $item->name }}</td>
                <td>
                    {{ $item->category }}
                </td>
                <td>{{ $item->price }}</td>
                <td>
                    <button value="{{ $item->id }}" class="text-primary btn rounded p-0 editbtn">
                        {{ $item->quantity }}
                    </button>
                </td>

                <td>
                    <button value="{{ $item->id }}" class="text-primary btn rounded p-0 criticalbtn">
                        {{ $item->critical_stock }}
                    </button>
                </td>
                <td>
                    <div class="d-flex justify-content-center gap-1">
                        <button value="{{ $item->id }}"
                            class="btn-success btn-sm btn text-white rounded-lg editProduct">
                            <i class="fa fa-pencil px-1 "></i>
                        </button>
                        <a href="{{ route('delete-product', $item->id) }}"
                            onclick="return confirm('Are you sure you want to delete this product?')"
                            class="bg-danger btn btn-danger btn-sm text-white rounded-lg">
                            <i class="fa fa-trash px-1"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @php
                $id++;
            @endphp
        @endforeach
    </tbody>
</table>
{{ $products->links('vendor.pagination.bootstrap-5') }}
