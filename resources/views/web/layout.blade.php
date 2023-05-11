<!DOCTYPE html>
<html lang="es">

    <head>
        {{ view('web/head_tags') }}
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
            function addToCart(productId) {
                url = "{{ route('web.add_to_cart', ':id') }}";

                $.ajax({
                    url: url.replace(':id', productId),
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
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