<!DOCTYPE html>
<html>
<head>
    <title>Purchase Confirmation</title>
</head>
<body>
    <h1>Thank you for your purchase!</h1>
    <p>Your order details:</p>
    <ul>
        <li>Order ID: {{ $order->id }}</li>
        <li>Product: {{ $order->product->name }}</li>
        <li>Price: ${{ $order->product->price }}</li>
    </ul>
    <p>If you have any questions, please feel free to contact us.</p>
</body>
</html>
