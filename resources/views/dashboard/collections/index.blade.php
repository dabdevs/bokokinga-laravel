@extends('dashboard/layout')

@section('content')
    {{ view('shared/messages') }}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <form id="delete-form" method="POST">
        <input type="hidden" name="_method" value="DELETE">
        @csrf
    </form>

    <h1>Colecciones</h1>

    <div class="mb-3 card p-3 d-none" id="top-form">
        <form action="" method="POST" id="collection-form" enctype="multipart/form-data"
            onsubmit="return validate(event)">
            @csrf
            <input type="hidden" name="_method" id="method">
            <div class="row">
                <div class="col-sm-6">
                    <label for="collection_name">Nombre:</label>
                    <input class="form-control" type="text" id="collection_name" name="name">
                </div>

                <div class="col-sm-3">
                    <label for="image">Imagen:</label>
                    <input class="form-control" type="file" accept=”image/*” name="image" id="image">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-9 my-2">
                    <label for="description">Descripción:</label>
                    <textarea class="form-control" name="description" id="description" cols="30" rows="3"></textarea>
                    <input type="hidden" id="collection_id" name="id">
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
                    colección</button>
            </div>

            <div class="col-xs-12">
                @if (!$collections->isEmpty())
                    <table class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Imagen</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse ($collections as $collection)
                                <tr>
                                    <td>{{ $collection->name }}</td>
                                    <td>{{ $collection->description }}</td>
                                    <td>{{ $collection->image }}</td>
                                    <td>
                                        <button type="button" class="btn btn-edit btn-warning"
                                            onclick="edit({{ $collection->id }})"><i class="bx bx-pencil"></i></button>
                                        <button type="button" class="btn btn-danger btn-delete ml-2"
                                            onclick="remove({{ $collection->id }})"><i class="bx bx-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                        <tfoot>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Imagen</th>
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
            $("#collection_id").val("")
            $("#collection_name").val("")

            $('#collection-form').attr({
                'action': '/admin/collections',
                'method': 'POST'
            })
            $('#method').val('POST')
        }

        function edit(id) {
            habilitar_botones();
            document.getElementById("dataList").style.display = "none";
            $("#top-form").removeClass('d-none');
            $("#collection_id").val(id)
            $('#collection-form').attr({
                'action': '/admin/collections/' + id,
                'method': 'POST'
            })
            $('#method').val('PUT')

            $.ajax({
                type: "GET",
                url: "/admin/collections/" + id,
                success: function(resultado) {
                    document.getElementById("collection_name").value = resultado['name'];
                    document.getElementById("description").value = resultado['description'];
                }
            });
        }

        function cancelar() {
            document.getElementById("collection_name").value = "";
            document.getElementById("description").value = "";
            document.getElementById("image").value = "";
            desabilitar_botones();
            $("#top-form").addClass('d-none');
            document.getElementById("dataList").style.display = "block";
        }

        function remove(id) {
            var form = $('#delete-form');

            form.attr({
                'action': 'collections/' + id,
                'method': 'POST'
            })

            Swal.fire({
                    title: "Alerta",
                    text: "Seguro quieres eliminar la colección!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
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

            name = document.getElementById('collection_name').value
            if (name == "") {
                Swal.fire(
                    'Alert',
                    'Ingresá un nombre!',
                    'error'
                )
                return
            }

            document.getElementById('collection-form').submit();
        }
    </script>
@endsection
