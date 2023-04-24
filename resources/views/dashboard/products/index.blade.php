@extends('dashboard/layout')

@section('content')
    <div>
        <livewire:product-form />
        <hr>
        <livewire:product-list />
    </div>
@endsection
