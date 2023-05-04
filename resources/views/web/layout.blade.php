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
    </body>

</html>