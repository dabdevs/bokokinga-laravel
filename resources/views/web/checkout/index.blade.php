@extends('web/layout')

@section('content')
    <section class="container">
        <div class="py-5 text-center">
            <h2>Checkout</h2>
            <p class="lead"></p>
        </div>

        <div class="row">
            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Tu carrito</span>
                    <span class="badge badge-secondary badge-pill">{{ session('cartQuantity') }}</span>
                </h4>
                <ul class="list-group mb-3">
                    @php 
                        $total = 0;
                    @endphp
                    @if(session('cart'))
                        @foreach(session('cart') as $key => $product)
                            @php 
                                $total += $product['price'] * $product['quantity']; 
                            @endphp

                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div class="my-0 mr-auto">
                                    <h6 class="my-0 p-0">{{ $product['name'] }} <small class="px-1 bg-secondary text-white">{{ $product['quantity'] }}</small></h6>
                                    <small class="text-muted">{{ $product['description'] }}</small> <br>
                                    <img src="{{ env('S3_BASE_URL'). "/" .$product['image'] }}" width="60" height="60" class="img-responsive my-0" alt="">
                                </div>
                                <div class="ml-auto">
                                    {{ $product['price'] }}
                                </div>
                            </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <h2 class="mr-auto">Total:</h2>
                            <h2 class="ml-auto">${{ session('totalPrice') }}</h2>
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

            <div class="col-md-8 order-md-1">
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <h4 class="mb-3">Billing address</h4>
                <form class="needs-validation" novalidate="" action="{{ route('web.checkout.pay') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstname">Nombre</label>
                            <input type="text" class="form-control" name="firstname" placeholder="" value=""
                                required="">
                            @error('firstname')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastname">Last name</label>
                            <input type="text" class="form-control" name="lastname" placeholder="" value=""
                                required="">
                            @error('lastname')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="email@ejemplo.com" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" name="address" required="">
                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- <div class="row d-none">
                        <div class="col-md-5 mb-3">
                            <label for="country">Country</label>
                            <select class="custom-select d-block w-100" name="country" required="">
                                <option value="">Choose...</option>
                                <option>United States</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a valid country.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="state">State</label>
                            <select class="custom-select d-block w-100" id="state" required="">
                                <option value="">Choose...</option>
                                <option>California</option>
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid state.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="zip">Zip</label>
                            <input type="text" class="form-control" id="zip" placeholder="" required="">
                            <div class="invalid-feedback">
                                Zip code required.
                            </div>
                        </div>
                    </div> --}}

                    <hr class="mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="same-address" id="same-address">
                        <label class="custom-control-label" for="same-address">
                            La dirección de entrega es la misma.
                        </label>
                        @error('same-address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="custom-control custom-checkbox d-none">
                        <input type="checkbox" class="custom-control-input" id="save-info">
                        <label class="custom-control-label" for="save-info">Save this information for next time</label>
                    </div>
                    <hr class="mb-4">

                    <h4 class="mb-3">Payment</h4>

                    <div class="d-block my-3">
                        <div class="custom-control custom-radio">
                            <input id="credit" name="payment-method" type="radio" class="custom-control-input"
                                checked="" required="">
                            <label class="custom-control-label" for="credit">Credit card</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input id="debit" name="payment-method" type="radio" class="custom-control-input"
                                required="">
                            <label class="custom-control-label" for="debit">Debit card</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input id="paypal" name="payment-method" type="radio" class="custom-control-input"
                                required="">
                            <label class="custom-control-label" for="paypal">Paypal</label>
                        </div>
                        @error('payment-method')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cc-name">Name on card</label>
                            <input type="text" class="form-control" name="cc-name" placeholder="" required="">
                            <small class="text-muted">Full name as displayed on card</small>
                            @error('cc-name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cc-number">Credit card number</label>
                            <input type="text" class="form-control" name="cc-number" placeholder="" required="">
                            @error('cc-number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="cc-expiration">Expiration</label>
                            <input type="text" class="form-control" name="cc-expiration" placeholder=""
                                required="">
                            @error('cc-expiration')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="cc-cvv">CVV</label>
                            <input type="text" class="form-control" name="cc-cvv" placeholder="" required="">
                            @error('cc-cvv')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <hr class="mb-4">
                    <button class="btn btn-primary btn-lg btn-block" type="submit" id="checkout-buttono">Continue to checkout</button>
                </form>
            </div>
        </div>
    </section>

    {{-- <section class="container py-5 my-5">
    <div class="row">
        <div class="col-md-9">
            {{ view('web.cart.includes.items') }}
        </div>

        <div class="col-md-3">
            <h2 class="my-5">Checkout</h2>
            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="payment-form" action="{{ route('web.checkout.pay') }}" method="POST">
                {{ csrf_field() }}

                <div class="form-group {{ $errors->has('name_on_card') ? ' has-error' : '' }}">
                    <label for="name_on_card">Name on Card</label>
                    <input type="text" id="name_on_card" name="name_on_card" class="form-control" required>

                    @if ($errors->has('name_on_card'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name_on_card') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="card-element">Credit or debit card</label>
                    <div id="card-element"></div>
                </div>

                <button type="submit" class="btn btn-success">Complete Order</button>
            </form>
        </div>
    </div>
    </section> --}}
@endsection
