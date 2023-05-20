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
                            <div class="col-sm-3 hidden-xs"><img src="{{ $product['image'] }}" width="100" height="100" class="img-responsive"/></div>
                            <div class="col-sm-9">
                                <h4 class="nomargin"><a href="{{ route('web.product.show', Str::lower($product['slug'])) }}" class="text-dark">{{ $product['name'] }}</a></h4>
                            </div>
                        </div>
                    </td>
                    <td data-th="Price">${{ number_format($product['price'], 2, '.', ','); }}</td>
                    <td data-th="Quantity">
                        <input id="quantity-{{ $product['id'] }}" type="number" value="{{ $product['quantity'] }}" class="form-control quantity" onchange="update({{ $product['id'] }}, {{ $product['product_quantity'] }})" />
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
        @if(session('cart') == null)
            <tr class="py-5">
                <td colspan="5">
                    <p class="py-5 text-center">Tu carrito está vacío. <a href="/">Ir a la tienda</a> </p>
                </td>
            </tr>
        @endif
        <tr>
            <input type="hidden" id="subtotal" value="{{ number_format($total, 2, '.', ','); }}">
            <td colspan="5" class="text-right"><h3><strong id="subtotal">Subtotal: ${{ number_format($total, 2, '.', ','); }}</strong></h3></td>
        </tr>
        <tr>
            <td colspan="5" class="text-right">
                <button onclick="window.history.go(-1); return false;" class="btn btn-warning"><i class="fa fa-chevron-left"></i> Seguir de compra</button>
                @if(session('cart'))
                    <a href="{{ route('web.order.checkout') }}" class="btn btn-success"><i class="fa fa-chevron-right"></i> Checkout</a>
                @endif
            </td>
        </tr>
    </tfoot>
</table>