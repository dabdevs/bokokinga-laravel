@extends('web/layout')

@section('content')
    <h1>Order Details</h1>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderItems as $orderItem)
                <tr>
                    <td>{{ $orderItem->product->name }}</td>
                    <td>{{ $orderItem->quantity }}</td>
                    <td>${{ $orderItem->price }}</td>
                    <td>${{ $orderItem->quantity * $orderItem->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Total: ${{ $order->total }}</p>

    <p>Customer: {{ $order->customer->name }}</p>
@endsection

