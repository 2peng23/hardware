@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between">
        <h3 class="text-muted">Categories</h3>
        <x-add-category />
        <x-success-message />
        <x-error-message />
    </div>
    <x-message />
    @if ($data->isEmpty())
        <p class="mt-5">No available category.</p>
    @else
        <div class="d-flex flex-wrap gap-3 mt-5 m-auto">
            @foreach ($data as $item)
                <div class="card" style="width: 10rem;">
                    <img src="/images/{{ $item->image }}" class="card-img-top" style="width: 150px; height: 150px"
                        alt="{{ $item->name }}">
                    <div class="d-flex justify-content-between w-75 mx-auto my-3">
                        <a href="http://127.0.0.1:8000/items?_token=TU88ZGtLucryXHLUecRYyuFyOy5K264LfT18A2Gh&product_category={{ $item->name }}"
                            class=" text-decoration-none text-dark">{{ $item->name }}</a>
                        <p class="card-text text-primary fs-4 fw-bolder">
                            {{ App\Models\Product::where('category', $item->name)->count() }}
                        </p>
                    </div>
                </div>
            @endforeach

        </div>
        <p class="mt-2">
            {{ $data->links('vendor.pagination.bootstrap-4') }}
        </p>
    @endif
@endsection
