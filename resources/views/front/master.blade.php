<!DOCTYPE html>
<html lang="es">

    <head>
        {{ view('front/head_tags') }}
    </head>

    <body>
        {{ view('front/header') }}
        
        <main class="container-fluid py-5">
            @yield('content')
        </main>

        {{ view('front/footer') }}
    </body>

</html>