<!DOCTYPE html>
<html>
<head>
    <title>Purchase Confirmation</title>
    <style>
        th, td {
            border: 1px solid #000;
            border: 1px solid #000;
            padding: 5px;
        }
        h6 {
            margin: 0;
            font-size: 16px;
            float: right;
        }
    </style>
</head>
<body>
    <h1>Thank you for your purchase!</h1>
    <table>
        <caption>Order #{{ $items[0]['id'] }}</caption>
        <thead>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
        </thead>
        <tbody>
            @foreach ($items[0]['items'] as $item)
                <tr>
                    <td>{{ $item['product']['name'] }}</td>
                    <td>${{ $item['product']['price'] }}</td>
                    <td>{{ $item['quantity'] }}(un.)</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Subtotal: <h6>${{ $items[0]['subtotal'] }}</h6></td>
            </tr>
            <tr>
                <td colspan="3">Shipping: <h6>${{ $items[0]['shipping_price'] }}</h6></td>
            </tr>
            <tr>
                <td colspan="3">Total: <h6>${{ $items[0]['total_price'] }}</h6></td>
            </tr>
        </tfoot>
    </table>
    <p>If you have any questions, please feel free to contact us.</p>
</body>
</html>
