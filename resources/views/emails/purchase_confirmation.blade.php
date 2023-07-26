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
        <caption>Order #{{ $items[0]['order_id'] }}</caption>
        <thead>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['product']['name'] }}</td>
                    <td>${{ number_format($item['product']['price'], 2, '.', ',') }}</td>
                    <td>{{ $item['quantity'] }}(un.)</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Subtotal: <h6>${{ number_format($items[0]['order']['subtotal'], 2, '.', ',') }}</h6></td>
            </tr>
            <tr>
                <td colspan="3">Shipping: <h6>${{ number_format($items[0]['order']['shipping_price'], 2, '.', ',') }}</h6></td>
            </tr>
            <tr>
                <td colspan="3">Total: <h6>${{ number_format($items[0]['order']['total_price'], 2, '.', ',') }}</h6></td>
            </tr>
        </tfoot>
    </table>
    <p>If you have any questions, please feel free to contact us.</p> 
</body>
</html>
