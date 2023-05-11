@extends('web/layout')

@section('css')
    <style>
        header {
            display: none;
        }
    </style>
@endsection

@section('content')
    <section class="container py-5">
        <div class="text-center mb-5">
            <img class="d-block mx-auto mt-4" src="{{ asset('front/img/cart.png') }}" alt="" width="72"
                height="72">
            <h2>Completando tu orden</h2>
            <p class="lead">
                Ingresa los datos de contacto y de envío.
            </p>
        </div>

        <div class="row">
            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Tu compra</span>
                    <span class="badge badge-secondary badge-pill">{{ session('cartQuantity') }}</span>
                </h4>

                <ul class="list-group mb-3">
                    @php
                        $total = 0;
                    @endphp
                    @if (session('cart'))
                        @foreach (session('cart') as $key => $product)
                            @php
                                $total += $product['price'] * $product['quantity'];
                            @endphp

                            <li class="list-group-item lh-condensed">
                                <div class="row">
                                    <div class="px-1 col-10">
                                        <h6 class="my-0 mr-auto">{{ $product['name'] }} <br>
                                            <small>({{ $product['quantity'] }} un.)</small></h6>
                                        <small class="text-muted">{{ $product['description'] }}</small>
                                    </div>
                                    <div class="px-1 col-2">
                                        <p class="text-right">${{ number_format($product['price'], 2, '.', ',') }}</p>
                                        <img src="{{ env('S3_BASE_URL') . '/' . $product['image'] }}" width="60"
                                            height="60" class="img-responsive my-0 float-right" alt="">
                                    </div>
                                </div>
                            </li>
                        @endforeach

                        <li class="list-group-item d-flex p-2 justify-content-between lh-condensed">
                            <div class="my-0 mr-auto px-1">
                                <h6 class="my-0 p-0">Subtotal:</h6>
                                <h6 class="mr-auto">Envío:</h6>
                                <h2 class="mr-auto">Total<small>(ARS)</small></h2>
                            </div>
                            <div class="ml-auto p-0">
                                <h6 class="ml-auto text-right">${{ session('totalPrice') }}</h6>
                                <h6 class="ml-auto text-right">${{ number_format(env('SHIPPING_COST'), 2, '.', ',') }}</h6>
                                <h2 class="ml-auto text-right">${{ session('totalPrice') + (int) env('SHIPPING_COST') }}
                                </h2>
                            </div>
                        </li>
                        <li class="list-group-item d-flex lh-condensed">
                            <button type="button"
                                class="btn btn-success ml-auto"
                                id="continue-checkout"><i class="fa fa-chevron-right"></i> Continuar</button>
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
                <h4 class="mb-3">Datos de envío</h4>
                <form class="needs-validation" novalidate="" method="POST" action="{{ route('customers.store') }}"
                    id="customer-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">Nombre</label>
                            <input type="text" class="form-control" name="firstname" id="firstName" value=""
                                required="">
                            @error('firstname')
                                <small class="text-danger">
                                    {{ $errors->first('firstname') }}
                                </small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastname">Apellido</label>
                            <input type="text" class="form-control" name="lastname" id="lastname" value=""
                                required="">
                            @error('lastname')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                            @error('email')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telephone">Teléfono</label>
                            <input type="text" class="form-control" id="telephone" name="telephone">
                            @error('telephone')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address">Dirección</label>
                        <input type="text" class="form-control" id="address" name="address" required="">
                        @error('address')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label for="postalCode">Cód. postal</label>
                            <input type="text" class="form-control" name="postal_code" id="postalCode"
                                required="">
                            @error('postal_code')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="province">Provincia</label>
                            <select class="custom-select d-block w-100" id="province" name="province" required="">
                                <option value="">Elegir...</option>
                                <option>Capital Federal</option>
                            </select>
                            @error('province')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="city">Localidad o Barrio</label>
                            <select class="custom-select d-block w-100" id="city" name="city" required="">
                                <option value="">Elegir...</option>
                                <option>San Nicolas</option>
                            </select>
                            @error('city')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <hr class="mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="same-billing-address"
                            name="same-billing-address">
                        <label class="custom-control-label" for="same-billing-address">
                            La dirección de facturación es la misma
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox d-none">
                        <input type="checkbox" class="custom-control-input" id="save-info" name="save-info">
                        <label class="custom-control-label" for="save-info">Guardar la información para la próxima
                            vez</label>
                    </div>
                    <hr>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        const continueBtn = document.getElementById('continue-checkout');
        const customerForm = document.getElementById('customer-form');

        continueBtn.addEventListener('click', function() {
            customerForm.submit();
        })
    </script>
@endsection
