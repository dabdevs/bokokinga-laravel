<!DOCTYPE html>
<html lang="es">

<head>
    {{ view('web/head_tags') }}
    <title>{{ env('APP_NAME') }} | @yield('title')</title>
    @yield('css')
</head>

<body>
    <!-- ***** Quick view modal ***** -->
    <div class="modal" id="product-quickview">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="left-images">
                                <section id="modal-main-carousel" class="splide main-carousel" aria-label="My Awesome Gallery">
                                    <div class="splide__track">
                                        <ul class="splide__list" id="modal-carousel-items">
                                        </ul>
                                    </div>
                                </section>

                                <ul id="modal-thumbnails" class="thumbnails">
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="right-content">
                                <h4 id="product-name"></h4>
                                <span class="price" id="product-price"></span>

                                <span id="product-description"></span>

                                <div class="quantity-content my-1 border-top-0">
                                    <div class="left-content">
                                        <h6>Cantidad</h6>
                                    </div>
                                    <div class="right-content">
                                        <div class="quantity buttons_added">
                                            <input type="number" step="1" min="1" max=""
                                                name="quantity" value="1" title="Quantidad"
                                                class="input-text qty text" size="4" pattern="" inputmode=""
                                                id="modal-quantity">
                                        </div>
                                    </div>
                                </div>

                                <div class="total py-2">
                                    <h4 id="modal-total"></h4>
                                    <div class="main-border-button w-100">
                                        <a href="#" id="product-add-to-cart" class="w-100 text-center">
                                            <i class="fa fa-shopping-cart"></i> Añadir al carrito
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $collections = App\Models\Collection::has('products')
            ->orderBy('name')
            ->get();
    @endphp

    @include('web/header', ['collections' => $collections])

    <main class="container-fluid">
        @yield('content')
    </main>

    @include('web/footer', ['collections' => $collections])

    <script>
        function addToCart(productId, modal=false) {
            inputQuantity = modal? document.getElementById('modal-quantity') : document.getElementById('quantity');
            url = "{{ route('web.add_to_cart', ':id') }}";
            
            quantity = inputQuantity == null ? 1 : inputQuantity.value;

            $.ajax({
                url: url.replace(':id', productId),
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    quantity: quantity
                },
                success: function(response) {
                    toast(response.success);
                    $('#cart-count').text(response.cartQuantity)
                }
            });
        }

        function quickView(id) {
            url = "{{ route('web.product.quick_view', ':id') }}";

            $.ajax({
                url: url.replace(':id', id),
                method: "GET",
                data: {},
                success: function(product) {
                    if (product != null) {
                        $('#product-name').text(product.name)
                        $('#product-description').text(product.description)
                        $('input[name="product-quantity"]').attr('max', product.quantity);

                        minusInput = document.createElement('input')
                        minusInput.type = 'button'
                        minusInput.value = '-'
                        minusInput.classList.add('minus')

                        minusInput.addEventListener('click', function() {
                            subtract(product.id, product.price, product.quantity, true)
                        })

                        plusInput = document.createElement('input')
                        plusInput.type = 'button'
                        plusInput.value = '+'
                        plusInput.classList.add('plus')

                        plusInput.addEventListener('click', function() {
                            add(product.id, product.price, product.quantity, true)
                        })

                        inputQuantity = document.getElementById('modal-quantity')

                        inputQuantity.before(minusInput)
                        inputQuantity.after(plusInput)

                        cartIcon = document.querySelector('#product-add-to-cart')
                        cartIcon.addEventListener('click', function() {
                            addToCart(product.id, true)
                        })

                        product.images.forEach(image => {
                            // Carousel
                            const carouselItem = document.createElement('li')
                            carouselItem.classList.add('splide__slide')

                            const img = document.createElement('img')
                            img.src = image.path

                            carouselItem.append(img)

                            $('#modal-carousel-items').append(carouselItem)

                            // thumbnails
                            const thumbnailsItem = document.createElement('li')
                            thumbnailsItem.classList.add('thumbnail')

                            const thumbnailsImg = document.createElement('img')
                            thumbnailsImg.src = image.path

                            thumbnailsItem.append(thumbnailsImg)

                            $('#modal-thumbnails').append(thumbnailsItem)
                        });

                        var splide = new Splide('#modal-main-carousel', {
                            pagination: false,
                        } );

                        splide.mount();

                        $modal = $('#product-quickview');
                        $modal.find('.modal-title').text(product.name)
                        $modal.modal('show')
                    }
                }
            });
        }


        $('.product-card').click(function(e) {
            element = e.target
            url = $(element).data('url')

            if (typeof url !== 'undefined') {
                window.location.href = url
            }
        })

        function add(id, price, productQuantity, modal=false) {
            // Current form quantity value
            inputQuantity = modal ? document.getElementById('modal-quantity') : document.getElementById('quantity');

            // If form quantity is equal to the product quantity
            if (inputQuantity.value >= productQuantity) {
                inputQuantity.value = inputQuantity.defaultValue
                return;
            }

            inputQuantity.value = parseInt(inputQuantity.value) + 1;

            total = modal ? document.getElementById('modal-total') : document.getElementById('total');

            total.innerText = 'Total: $' + (inputQuantity.value * price).toFixed(2);
        }

        function subtract(id, price, productQuantity, modal=false) {
            // Selected form quantity input
            inputQuantity = modal ? document.getElementById('modal-quantity') : document.getElementById('quantity');

            // If input value is equal to 1 return
            if (inputQuantity.value == 1) return;

            total = modal ? document.getElementById('modal-total') : document.getElementById('total');

            inputQuantity.value = parseInt(inputQuantity.value) - 1;
            total.innerText = 'Total: $' + (inputQuantity.value * price).toFixed(2);
        }

        function update(id, productQuantity) {
            quantity = document.getElementById('quantity');
            price = $('input-data').data('price');


            if (quantity.value > productQuantity) {
                quantity.value = quantity.defaultValue;
                toast('Solo hay ' + productQuantity + ' disponibles', 'danger')
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
                    document.getElementById('total').innerText = 'Total: $' + (quantity.value * parseFloat(
                        price)).toFixed(2);
                }
            });
        }
    </script>

    @yield('js')
</body>

</html>
