<!DOCTYPE html>
<html lang="es">

    <head>
        {{ view('web/head_tags') }}
        <title>{{ env('APP_NAME') }} | @yield('title')</title>
        @yield('css')
    </head>

    <body>
        @php
            $collections = App\Models\Collection::has('products')->orderBy('name')->get(); 
        @endphp

        @include('web/header', ['collections' => $collections])
        
        <main class="container-fluid">
            @yield('content')
        </main>
        
        @include('web/footer', ['collections' => $collections])

        <script>
            $('.product-card').click(function() {
                card = $(this)
                url = card.data('url')
                window.location.href = url
            })

            function addToCart(productId) {
                inputQuantity = document.getElementById('quantity'); 
                url = "{{ route('web.add_to_cart', ':id') }}";

                $.ajax({
                    url: url.replace(':id', productId),
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: inputQuantity == null? 1 : inputQuantity.value
                    },
                    success: function (response) {
                        toast(response.success);
                        $('#cart-count').text(response.cartQuantity)
                    }
                });
            }
        </script>

        @yield('js')
    </body>

</html>