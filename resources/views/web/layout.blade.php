<!DOCTYPE html>
<html lang="es">

    <head>
        {{ view('web/head_tags') }}
    </head>

    <body>
        {{ view('web/header') }}
        
        <main class="container-fluid">
            @yield('content')
        </main>
        
        {{ view('web/footer') }}
    </body>

</html>