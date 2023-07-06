@extends('web/layout')

@section('title', 'Checkout')

@section('css')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <style>
        header {
            display: none;
        }

        .hover-address {
            -webkit-box-shadow: -2px 2px 10px 2px rgba(0,0,0,0.52);
            -moz-box-shadow: -2px 2px 10px 2px rgba(0,0,0,0.52);
            box-shadow: -2px 2px 10px 2px rgba(0,0,0,0.52);
        }

        .selected-address {
            -webkit-box-shadow: -2px 2px 10px 2px rgba(12, 224, 129, 0.902);
            -moz-box-shadow: -2px 2px 10px 2px rgba(12, 224, 129, 0.902);
            box-shadow: -2px 2px 10px 2px rgba(12, 224, 129, 0.902);
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
            <div class="col-md-8 mb-4">
                <form class="needs-validation" novalidate="" method="POST" action="{{ route('orders.store') }}"
                    id="customer-form">
                    @csrf
                    
                    @php
                        $customer = session('customer');
                    @endphp

                    <input type="hidden" name="total_price" value="{{ session('subtotal') + $shipping_cost }}">

                    @if (Auth::check() && $customer != null && $customer->addresses->count() > 0)
                        <h4 class="mb-3">Direcciones de envío</h4>
                        <input type="hidden" id="address_id" name="address_id">
                        
                        @foreach ($customer->addresses as $address)
                            <div data-address_id="{{ $address->id }}" class="border mb-1 p-2 shipping_address" style="cursor: pointer;">
                                <address>
                                    {{ $address->street }}, {{ $address->number }}<br>
                                    {{ $address->city->name }}, {{ $address->country->code }} <br>
                                    CP: {{ $address->postal_code }} <br>
                                </address>
                            </div>
                        @endforeach
                    @else
                        <h4 class="mb-3">Datos personales</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">Nombre</label>
                                <input type="text" class="form-control" name="firstname" id="firstName"
                                    value="{{ old('firstname') }}" required="">
                                @error('firstname')
                                    <small class="text-danger">
                                        {{ $errors->first('firstname') }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastname">Apellido</label>
                                <input type="text" class="form-control" name="lastname" id="lastname"
                                    value="{{ old('lastname') }}" required="">
                                @error('lastname')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cellphone">Celular</label>
                                <input type="text" class="form-control" id="cellphone" name="cellphone"
                                    value="{{ old('cellphone') }}">
                                @error('cellphone')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>


                        <h4 class="my-3">Datos de envío</h4>
                        <div class="row">
                            <div class="col-md-4 mb-3 d-none">
                                <label for="country">País</label>
                                <select class="custom-select d-block w-100" id="country" name="country_id" readonly>
                                    <option value="1" selected>Argentina</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="provincia">Provincia</label>
                                <select class="custom-select d-block w-100" id="provincia" name="province">
                                    <option value="">Seleccioná</option>
                                    @foreach ($provincias as $provincia)
                                        <option value="{{ $provincia['id'] }}" @if($provincia['id'] == old('province')) selected @endif>{{ $provincia['nombre'] }}</option>
                                    @endforeach
                                </select>
                                @error('provincia')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="city">Localidad</label>
                                <input type="text" class="form-control w-100" id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="postalCode">Cód. postal</label>
                                <input type="text" class="form-control" name="postal_code" id="postalCode"
                                    required="" value="{{ old('postal_code') }}">
                                @error('postal_code')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-10 mb-3">
                                <label for="street">Calle</label>
                                <input class="form-control" type="text" id="street" name="street"
                                    value="{{ old('street') }}">
                                @error('street')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-sm-2 mb-3">
                                <label for="number">Número</label>
                                <input type="number" class="form-control" id="number" name="number" required=""
                                    value="{{ old('number') }}">
                                @error('number')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-sm-2 mb-3">
                                <label for="apt">Apartamento</label>
                                <input type="text" class="form-control" id="apt" name="apt" required=""
                                    value="{{ old('apt') }}">
                                @error('apt')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>
                        <div class="custom-control custom-checkbox d-none">
                            <input type="checkbox" class="custom-control-input" id="save-info" name="save-info">
                            <label class="custom-control-label" for="save-info">Guardar la información para la próxima
                                vez</label>
                        </div>
                        <hr>
                    @endif
                </form>
            </div>
            <div class="col-md-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Tu compra</span>
                    <span class="badge badge-secondary badge-pill">{{ session('cartQuantity') }} items</span>
                </h4>

                <ul class="list-group mb-3">
                    @if (session('cart')['items']) 
                        @foreach (session('cart')['items'] as $key => $product)
                            <li class="list-group-item lh-condensed">
                                <div class="row">
                                    <div class="px-1 col-10">
                                        <h6 class="my-0 mr-auto">{{ $product['name'] }} <br>
                                            <small>({{ $product['quantity'] }} un.)</small>
                                        </h6>
                                        <small class="text-muted">{{ $product['description'] }}</small>
                                    </div>
                                    <div class="px-1 col-2">
                                        <p class="text-right">${{ number_format($product['price'], 2, '.', ',') }}</p>
                                        <img src="{{ $product['image'] }}" width="60" height="60"
                                            class="img-responsive my-0 float-right" alt="">
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
                                <h6 class="ml-auto text-right">${{ session('subtotal') }}</h6>
                                <h6 class="ml-auto text-right">${{ number_format($shipping_cost, 2, '.', ',') }}</h6>
                                <h2 class="ml-auto text-right">${{ session('subtotal') + $shipping_cost }}
                                </h2>
                            </div>
                        </li>
                        <li class="list-group-item d-flex lh-condensed">
                            <button type="button" class="btn btn-success ml-auto" id="continue-checkout"><i
                                    class="fa fa-chevron-right"></i> Continuar</button>
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
        const continueBtn = document.getElementById('continue-checkout');
        const customerForm = document.getElementById('customer-form');

        continueBtn.addEventListener('click', function() {
            customerForm.submit();
        })

        $('.shipping_address').hover(function() {
            $(this).toggleClass('hover-address')
        })

        $('.shipping_address').click(function() {
            $('.shipping_address').removeClass('hover-address selected-address')
            $(this).addClass('selected-address')
            $(this).removeClass('hover-address')

            addressInput = $('#address_id')
            selectedId = $(this).data('address_id')
            addressInput.val(selectedId)
        })
    </script>
@endsection
