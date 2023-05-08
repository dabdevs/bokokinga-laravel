<!DOCTYPE html>
<html lang="es">

    <head>
        {{ view('web/head_tags') }}
    </head>

    <body>
        @php
            $collections = App\Models\Collection::orderBy('name')->get();
        @endphp

        {{ view('web/header', ['collections' => $collections]) }}
        
        <main class="container-fluid">
            @yield('content')
        </main>
        
        {{ view('web/footer', ['collections' => $collections]) }}

        <script>
            function toast(message) {
                Toastify({
                    text: message,
                    style: {
                        background: "#198754",
                    }
                }).showToast();
            }

            function addToCart(productId) {
                url = "{{ route('web.add_to_cart', ':id') }}";

                $.ajax({
                    url: url.replace(':id', productId),
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        console.log(response)
                        toast(response.success)
                    }
                });
            }
        </script>

        @yield('js')
    </body>

</html>