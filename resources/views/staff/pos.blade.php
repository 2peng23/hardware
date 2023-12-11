@extends('layouts.staff')
@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-6 col-12"></div>
        <div class="col-lg-12 col-md-6 col-12">
            <input type="text" class="form-control my-2" placeholder="Search Item" name="item" id="item">
            <div class="table-responsive">
                <table class="table" style="max-height: 500px">
                    <thead>
                        <tr class="text-center">
                            <th>Items</th>
                            <th>Barcode</th>
                            {{-- <th>Image</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr class="text-center">
                                <td>{{ $item->name }}</td>
                                <td>{!! DNS1D::getBarcodeHTML($item->barcode, 'EAN13', 4, 50, 'black') !!}</td>
                                {{-- <td>{!! DNS1D::getBarcodeSVG($item->barcode, 'EAN13', 4, 50, 'black') !!}</td> --}}
                                <td>
                                    <button value="{{ $item->id }}" class="btn btn-success btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
