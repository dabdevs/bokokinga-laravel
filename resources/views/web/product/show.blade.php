@extends('web/layout')

@section('content')
    <section class="section py-2" id="product">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="left-images">
                        <!-- Gallery -->
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner" style="max-height:500px">
                            @foreach ($product->images as $image)
                            <div class="carousel-item @if($product->primaryImage->id == $image->id) active @endif">
                                <img class="d-block w-100" src="{{ env('S3_BASE_URL') . '/' . $image->path }}" alt="product image">
                            </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="right-content">
                        <h4>{{ $product->name }}</h4>
                        <span class="price">${{ number_format($product->price, 2, '.', ',') }}</span>
                        <ul class="stars d-none">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>

                        @if($product->description != "")
                            <span>{{ $product->description }}</span>
                        @endif

                        <div class="quote d-none">
                            <i class="fa fa-quote-left"></i>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiuski smod.</p>
                        </div>

                        @if ($product->quantity > 0)
                            <div class="quantity-content my-1 border-top-0">
                                <div class="left-content">
                                    <h6>Cantidad</h6>
                                </div>
                                <div class="right-content">
                                    <div class="quantity buttons_added">
                                        <input type="button" value="-" class="minus" onclick="subtract()">
                                        <input type="number" step="1" min="1" max="{{ $product->quantity }}"
                                            name="quantity" value="1" title="Quantidad" class="input-text qty text"
                                            size="4" pattern="" inputmode="" id="quantity">
                                        <input type="button" value="+" class="plus" onclick="add()">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="total py-2">
                            <h4 id="total">Total: ${{ number_format($product->price, 2, '.', ',') }}</h4>
                            <div class="main-border-button">
                                <a href="#" onclick="addToCart('{{ $product->id }}')">
                                    <i class="fa fa-shopping-cart"></i> Añadir al carrito
                                </a>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($product->similarProducts()->count() > 1)
        <section class="section py-2" id="men">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="mt-5 mb-3">Productos similares</h2>
                    </div>
                    @foreach ($product->similarProducts() as $similar_product)
                        @if($similar_product->id != $product->id)
                            <div class="col-sm-6 col-lg-3">
                                <x-product
                                    :id="$similar_product->id"
                                    :slug="$similar_product->slug"
                                    :image="$similar_product->primaryImage->path"
                                    :name="$similar_product->name"
                                    :price="$similar_product->price"
                                    :description="$similar_product->description"
                                />
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <script>
        function add() {
            inputQuantity = document.getElementById('quantity'); 

            if (inputQuantity.value == {{ $product->quantity }}) return;

            inputQuantity.value = parseInt(inputQuantity.value) + 1;
            document.getElementById('total').innerText = 'Total: $' + (inputQuantity.value * parseFloat('{{ $product->price }}')).toFixed(2);
            
            update('{{ $product->id }}', inputQuantity.value);
        }

        function subtract() {
            inputQuantity = document.getElementById('quantity');

            if (inputQuantity.value == 1) return;

            inputQuantity.value = parseInt(inputQuantity.value) - 1;

            update('{{ $product->id }}', inputQuantity.value)
        }

        function update(id, productQuantity) {
            quantity = document.getElementById('quantity');
            
            if (quantity.value > productQuantity) {
                quantity.value = quantity.defaultValue;
                toast('Solo hay '+productQuantity+' disponibles', 'danger')
                return;
            }

            if (quantity.value == 0) {
                eliminar(id);
                return;
            }
            
            $.ajax({
                url: '{{ route('web.update_cart') }}',
                method: "PATCH",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    quantity: quantity.value
                },
                success: function(response) {
                    toast(response.success);
                    document.querySelector('#cart-count').innerText = response.cartQuantity
                    document.getElementById('total').innerText = 'Total: $' + (quantity.value * parseFloat('{{ $product->price }}')).toFixed(2);
                }
            });
        }
    </script>
@endsection
