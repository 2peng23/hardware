@extends('layouts.staff')
@section('content')
    <h1>staff only</h1>
    @if (session('message'))
        <div style="top: 10px; right:0;"
            class="alert alert-success alert-dismissible mt-2 text-center animate__animated animate__bounceInDown position-absolute">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div style="top: 10px; right:0;"
            class="alert alert-warning alert-dismissible mt-2 text-center animate__animated animate__bounceInDown position-absolute">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endsection
