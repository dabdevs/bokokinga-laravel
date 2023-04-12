<!DOCTYPE html>
<html lang="es">

    <head>
        @include('front/head_tags')
    </head>

    <body>
        @include('front/header')
        
        <main class="container-fluid">
            @yield('content')
        </main>

        @include('front/footer')
    </body>

</html>