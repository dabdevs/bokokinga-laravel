<table id="cart" class="table table-hover table-condensed">
    <thead>
        <tr> 
            <th style="width:50%">Producto</th>
            <th style="width:10%">Precio</th>
            <th style="width:5%">Cantidad</th>
            <th style="width:25%" class="text-center">Subtotal</th>
            <th style="width:10%"></th>
        </tr>
    </thead>
    <tbody>
        @php 
            $total = 0;
        @endphp
        @if(session('cart'))
            @foreach(session('cart') as $key => $product)
                @php 
                    $total += $product['price'] * $product['quantity']; 
                @endphp

                <tr data-id="{{ $key }}" id="row-{{ $product['id'] }}">
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs"><img src="{{ env('S3_BASE_URL'). "/" .$product['image'] }}" width="100" height="100" class="img-responsive"/></div>
                            <div class="col-sm-9">
                                <h4 class="nomargin">{{ $product['name'] }}</h4>
                            </div>
                        </div>
                    </td>
                    <td data-th="Price">${{ number_format($product['price'], 2, '.', ','); }}</td>
                    <td data-th="Quantity">
                        <input id="quantity-{{ $product['id'] }}" type="number" value="{{ $product['quantity'] }}" class="form-control quantity" onchange="update({{ $product['id'] }})" />
                    </td>
                    <td data-th="Subtotal" class="text-center">${{ number_format($product['price'] * $product['quantity'], 2, '.', ','); }}</td>
                    <td class="actions" data-th="">
                        <button type="button" class="btn btn-danger btn-sm" onclick="remove({{ $product['id'] }})"><i class="fa fa-trash-o"></i></button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="text-right"><h3><strong>Total ${{ number_format($total, 2, '.', ','); }}</strong></h3></td>
        </tr>
        <tr>
            <td colspan="5" class="text-right">
                <a onclick="window.history.go(-1); return false;" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Seguir de compra</a>
                <button class="btn btn-success"><i class="fa fa-check"></i> Checkout</button>
            </td>
        </tr>
    </tfoot>
</table>