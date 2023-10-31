@extends('layouts.admin')
@section('content')
    <!-- Page Heading -->


    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">

            <div class="row">
                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="fa fa-refresh text-success animate__rotateIn animate__animated infinity "></i>
                            </div>
                            <h6 class="text-muted fw-normal mt-0" title="Number of Customers">On Process (Requests)</h6>
                            <h3 class="mt-3 mb-3">hello
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="fa fa-thumbs-up text-primary animate__animated animate__bounceIn "></i>
                            </div>
                            <h6 class="text-muted fw-normal mt-0" title="Number of Orders">New (Requests)</h6>
                            <h3 class="mt-3 mb-3">hello
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="fa fa-thumbs-down text-danger animate__animated animate__bounceIn "></i>
                            </div>
                            <h6 class="text-muted fw-normal mt-0" title="Average Revenue">Declined (Requests)</h6>
                            <h3 class="mt-3 mb-3">hello
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="fa fa-rocket text-warning animate__bounceIn animate__animated "></i>
                            </div>
                            <h6 class="text-muted fw-normal mt-0" title="Growth">Released (Requests)</h6>
                            <h3 class="mt-3 mb-3">
                                {{-- {{ count(\App\Models\Transaction::where('status', 'Released')->get()) }} --}}
                                hello
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card card-h-100">
                <div class="card-header">
                    <h4 class="header-titl">Request History</h4>
                </div>
                <div class="card-body" style="overflow-y: scroll; height: 200px">
                    <table class="table table-striped">
                        {{-- @foreach (\App\Models\Request::all()->sortByDesc('id') as $request)
                            <?php
                            $item = \App\Models\Product::where('id', $request->item_id)->first();
                            $transaction = \App\Models\Transaction::where('id', $request->transaction_id)->first();
                            $user = \App\Models\User::where('id', $transaction->user_id)->first();
                            ?>
                            <tr>
                                <td class="text-center">{{ \Carbon\Carbon::parse($request->created_at)->format('M d, Y') }}
                                </td>
                                <td class="text-center">{{ $user->name }}</td>
                                <td class="text-center">{{ $user->designation }}</td>
                                <td colspan="text-center">{{ $item->name ?? '' }}</td>
                                <td colspan="text-center">{{ $request->quantity }}</td>
                            </tr>
                        @endforeach --}}
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div class="row mt-5">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Products on Critical Stock</h5>
                </div>
                <div class="card-body" style="overflow-y: scroll; height: 200px">
                    <table class="table table-striped">
                        <tbody>
                            @foreach (\App\Models\Product::all() as $item)
                                @if ($item->quantity <= $item->critical_stock && $item->quantity > 0)
                                    <tr>
                                        <td class="text-center">{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->stock }}</td>
                                        <td class="text-center">
                                            <span style="color: rgb(255, 81, 0)"
                                                class=" animate__animated infinite animate__flash animated infinity">
                                                Critical
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Products Unavailable</h5>
                </div>
                <div class="card-body" style="overflow-y: scroll; height: 200px">
                    <table class="table table-striped">
                        <tbody>
                            @foreach (\App\Models\Product::all() as $item)
                                @if ($item->quantity == 0)
                                    <tr>
                                        <td class="text-center">{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->stock }}</td>
                                        <td class="text-center"><span class="text-danger">Unavailable</span></td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
