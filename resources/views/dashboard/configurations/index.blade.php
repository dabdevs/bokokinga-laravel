@extends('dashboard/layout')

@section('content')
    {{ view('shared/messages') }}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <form id="delete-form" method="POST">
        <input type="hidden" name="_method" value="DELETE">
        @csrf
    </form>

    <h1>Configurations</h1>

    <div class="mb-3 card p-3 d-none" id="top-form">
        <form action="" method="POST" id="configuration-form" enctype="multipart/form-data"
            onsubmit="return validate(event)">
            @csrf
            <input type="hidden" name="_method" id="method">
            <div class="row">
                <div class="col-sm-6">
                    <label for="configuration_name">Nombre:</label>
                    <input class="form-control" type="text" id="configuration_name" name="name">
                </div>

                <div class="col-sm-6">
                    <label for="value">Valor:</label>
                    <input class="form-control" type="text" name="value" id="value">
                </div>
            </div>

            <div class="row p-3">
                <button type="submit" class="col-sm-2 mr-1 btn btn-success" id="Guardar" disabled><i
                        class="fa fa-save"></i> Guardar</button>
                <button type="button" class="col-sm-2 mr-1 btn btn-secondary" id="Cancelar" onclick="cancelar()"><i
                        class="fa fa-times"></i> Cancelar</button>
            </div>
        </form>
    </div>

    <div class="mb-3 card p-3" id="dataList">
        <div class="row table-responsive pl-3">
            <div class="col-xs-12">
                <button class="btn btn-success my-3 float-right" onclick="agregar()"><i class="fa fa-plus"></i> Nueva
                    configuración</button>
            </div>

            <div class="col-xs-12">
                @if (!$configurations->isEmpty())
                    <table class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>Nombre</th>
                            <th>Valor</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse ($configurations as $configuration)
                                <tr>
                                    <td>{{ $configuration->name }}</td>
                                    <td>{{ $configuration->value }}</td>
                                    <td>
                                        <button type="button" class="btn btn-edit btn-warning"
                                            onclick="edit({{ $configuration->id }})"><i class="bx bx-pencil"></i></button>
                                        <button type="button" class="btn btn-danger btn-delete ml-2"
                                            onclick="remove({{ $configuration->id }})"><i class="bx bx-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                        <tfoot>
                            <th>Nombre</th>
                            <th>Valor</th>
                            <th></th>
                        </tfoot>
                    </table>
                @else
                    <center>
                        <h5 class="my-5 py-5">No hay datos.</h5>
                    </center>
                @endif
            </div>
        </div>
    </div>

    <script>
        function habilitar_botones() {
            document.getElementById("Cancelar").disabled = false;
            document.getElementById("Guardar").disabled = false;
        }

        function desabilitar_botones() {
            document.getElementById("Cancelar").disabled = true;
            document.getElementById("Guardar").disabled = true;
        }

        function agregar() {
            $("#top-form").removeClass('d-none');
            document.getElementById("dataList").style.display = "none";
            habilitar_botones()
            $("#configuration_id").val("")
            $("#configuration_name").val("")

            $('#configuration-form').attr({
                'action': '/admin/configurations',
                'method': 'POST'
            })
            $('#method').val('POST')
        }

        function edit(id) {
            habilitar_botones();
            document.getElementById("dataList").style.display = "none";
            $("#top-form").removeClass('d-none');
            $("#configuration_id").val(id)
            $('#configuration-form').attr({
                'action': '/admin/configurations/' + id,
                'method': 'POST'
            })
            $('#method').val('PUT')

            $.ajax({
                type: "GET",
                url: "/admin/configurations/" + id,
                success: function(resultado) {
                    document.getElementById("configuration_name").value = resultado['name'];
                    document.getElementById("value").value = resultado['value'];
                }
            });
        }

        function cancelar() {
            document.getElementById("configuration_name").value = "";
            document.getElementById("value").value = "";
            desabilitar_botones();
            $("#top-form").addClass('d-none');
            document.getElementById("dataList").style.display = "block";
        }

        function remove(id) {
            var form = $('#delete-form');

            form.attr({
                'action': 'configurations/' + id,
                'method': 'POST'
            })

            Swal.fire({
                    title: "Atención",
                    text: "Seguro quieres eliminar la colección!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#ccc',
                    confirmButtonText: 'Confirmar!'
                })
                .then((result) => {
                    if (result.value) {
                        form.submit();
                    }
                });
        }

        function validate(e) {
            e.preventDefault()

            name = document.getElementById('configuration_name').value
            if (name == "") {
                Swal.fire(
                    'Alert',
                    'Ingresá un nombre!',
                    'error'
                )
                return
            }

            document.getElementById('configuration-form').submit();
        }
    </script>
@endsection
