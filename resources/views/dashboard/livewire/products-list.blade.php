@extends('dashboard.layout')

@section('content')
    <div>
        @foreach($products as $product)
            <div class="my-4">
                <h4>{{ $product->name }}</h4>
                @if($product->description)
                    <p>{{ $product->description }}</p>
                @endif
                <p>Price: ${{ $product->price }}</p>
                <p>Quantity: {{ $product->quantity }}</p>
                @foreach($product->images as $image)
                    <img src="{{ $image->url }}" alt="{{ $product->name }}" class="img-thumbnail mr-2 mb-2">
                @endforeach
            </div>
        @endforeach
    </div>
@endsection