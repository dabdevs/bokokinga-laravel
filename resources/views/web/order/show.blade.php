@extends('web/layout')

@section('content')
    @php
        // SDK Mercado Pago
        require base_path('vendor/autoload.php');
        // Credentials
        MercadoPago\SDK::setAccessToken(env('MERCADOPAGO_SECRET'));
        
        // Create a preference object
        $preference = new MercadoPago\Preference();
        $shipments = new MercadoPago\Shipments();
        $shipments->cost = (int) env('SHIPPING_COST');
        $shipments->mode = 'not_specified';
        
        $preference->shipments = $shipments;
    @endphp
    <section class="container">
        <div class="py-5 text-center">
            <h2>Checkout</h2>
            <p class="lead"></p>
        </div>

        <div class="row">
            <div class="col-md-12 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Tu compra</span>
                    <span class="badge badge-secondary badge-pill">{{ session('cartQuantity') }} productos</span>
                </h4>
                <ul class="list-group mb-3">
                    @php
                        $total = 0;
                    @endphp
                    @if (session('cart'))
                        @foreach (session('cart') as $key => $product)
                            @php
                                $total += $product['price'] * $product['quantity'];
                                
                                // Create an item for preference
                                $item = new MercadoPago\Item();
                                $item->title = $product['name'];
                                $item->quantity = $product['quantity'];
                                $item->unit_price = $product['price'];
                                $products_list[] = $item;
                            @endphp

                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div class="my-0 mr-auto">
                                    <h6 class="my-0 p-0">{{ $product['name'] }} <small
                                            class="px-1 bg-secondary text-white">{{ $product['quantity'] }}</small></h6>
                                    <small class="text-muted">{{ $product['description'] }}</small> <br>
                                    <img src="{{ env('S3_BASE_URL') . '/' . $product['image'] }}" width="60"
                                        height="60" class="img-responsive my-0" alt="">
                                </div>
                                <div class="ml-auto">
                                    {{ $product['price'] }}
                                </div>
                            </li>
                        @endforeach

                        @php
                            $preference->items = $products_list;
                            $preference->back_urls = [
                                'pending' => route('web.payment.webhook', $order->id),
                                'success' => route('web.payment.webhook', $order->id),
                                'failure' => route('web.payment.webhook', $order->id),
                            ];
                            $preference->auto_return = 'approved';
                            $preference->save();
                        @endphp

                         <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div class="my-0 mr-auto">
                                <h6 class="my-0 p-0">Subtotal:</h6>
                                <h6 class="mr-auto">Envío:</h6>
                                <h2 class="mr-auto">Total</h2>
                            </div>
                            <div class="ml-auto p-0">
                                <h6 class="ml-auto text-right">${{ session('totalPrice') }}</h6>
                                <h6 class="ml-auto text-right">${{ env('SHIPPING_COST') }}</h6>
                                <h2 class="ml-auto text-right">${{ session('totalPrice') + (int)env('SHIPPING_COST') }}</h2>
                            </div>
                        </li>
                        <li class="list-group-item d-flex lh-condensed">
                            <div id="wallet_container" class="ml-auto"></div>
                        </li>
                    @endif
                </ul>

                <form class="card p-2 d-none">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Promo code">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary">Redeem</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        const mp = new MercadoPago("{{ env('MERCADOPAGO_KEY') }}", {
            locale: 'es-AR'
        });

        const bricksBuilder = mp.bricks();

        mp.bricks().create("wallet", "wallet_container", {
            initialization: {
                preferenceId: "{{ $preference->id }}",
            },
        });
    </script>
@endsection
