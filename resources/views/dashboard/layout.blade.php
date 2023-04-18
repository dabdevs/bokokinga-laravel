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

        <script>
            $('.table').dataTable({
            "aProcessing": true, //Activamos el procesamiento del datatables
            "aServerSide": true, //Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip', //Definimos los elementos del control de tabla
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdf'
            ],
            "bDestroy": true,
            "iDisplayLength": 5, //Paginación
            "order": [
                [0, "asc"]
            ] //Ordenar (columna,orden)
        }).DataTable();
        </script>
    </body>

</html>