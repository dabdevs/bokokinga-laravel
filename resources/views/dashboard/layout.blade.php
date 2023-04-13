<!DOCTYPE html>
<html lang="es">

    <head>
        {{ view('dashboard/head_tags') }}
    </head>

    <body>
        {{ view('dashboard/header') }}

        {{ view('dashboard/sidebar') }}
        
        <main class="container-fluid py-5">
            @yield('content')
        </main>

        {{ view('dashboard/footer') }}
    </body>

</html>