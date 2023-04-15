<!DOCTYPE html>
<html lang="es">

    <head>
        {{ view('dashboard/head_tags') }}
    </head>

    <body>
        {{ view('dashboard/header') }}

        <main class="container-fluid">
            <div class="row row-offcanvas row-offcanvas-left">
                {{ view('dashboard/sidebar') }}

                <div class="col main pt-5 mt-3">
                    @yield('content')
                </div>
            </div>
        </main>

        {{ view('dashboard/footer') }}
    </body>

</html>