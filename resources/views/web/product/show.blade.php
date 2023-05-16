@extends('web/layout')

@section('title', $product->name)

@section('content')
    <section class="section py-5" id="product">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="left-images">
                        <section id="main-carousel" class="splide main-carousel">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    @foreach ($product->images as $image)
                                        <li class="splide__slide">
                                            <img src="{{ $image->path }}" alt="" style="height: 550px">
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </section>

                        <ul id="thumbnails" class="thumbnails mt-0">
                            @foreach ($product->images as $image)
                                <li class="thumbnail">
                                    <img src="{{ $image->path }}" alt="">
                                </li>
                            @endforeach
                        </ul>
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
                                        <input type="button" value="-" class="minus" onclick="subtract({{ $product->id }}, {{ number_format($product->price, 2, '.', ',') }}, {{ $product->quantity }})">
                                        <input type="number" step="1" min="1" max="{{ $product->quantity }}"
                                            name="quantity" value="1" title="Quantidad" class="input-text qty text"
                                            size="4" pattern="" inputmode="" id="quantity">
                                        <input type="button" value="+" class="plus" onclick="add({{ $product->id }}, {{ number_format($product->price, 2, '.', ',') }}, {{ $product->quantity }})">
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
@endsection
