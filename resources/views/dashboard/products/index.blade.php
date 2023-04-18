@extends('dashboard/layout')

@section('content')
    {{ view('shared/messages') }}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="modal" id="collection-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="delete-form" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body" id="eliminar-body">
                        ¿Seguro deseas eliminar la collección?
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer" id="eliminar-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-chevron-left"></i> Salir</button>
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</button>
                    </div>
                    <input type="hidden" id="modal-collection-id">
                </form>
            </div>
        </div>
    </div>

    <h1>Colecciones</h1>

    <div class="mb-3 card p-3 d-none" id="top-form">
        <form action="" method="POST" id="collection-form" enctype="multipart/form-data">
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
            <button class="btn btn-success my-3 float-right" onclick="agregar()"><i class="fa fa-plus"></i> Agregar</button>

            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
                    <th>Opciones</th>
                </thead>
                <tbody>
                    @forelse ($collections as $collection)
                        <tr>
                            <td>{{ $collection->name }}</td>
                            <td>{{ $collection->description }}</td>
                            <td>{{ $collection->image }}</td>
                            <td>
                                <button type="button" class="btn btn-edit btn-warning" onclick="edit({{ $collection->id }})"><i class="bx bx-pencil"></i>&nbsp;Editar</button>
                                <button type="button" class="btn btn-danger btn-delete ml-2" onclick="remove({{ $collection->id }})"><i class="bx bx-trash"></i>&nbsp;Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        
                    @endforelse
                </tbody>
                <tfoot>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
                    <th>Opciones</th>
                </tfoot>
            </table>
        </div>
    </div>

    <script>
        list();

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
                'action': '/admin/collections/'+id,
                'method': 'POST'
            })
            $('#method').val('PUT')

            $.ajax({
                type: "GET",
                url: "/admin/collections/"+id,
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
            list()
        }


        function list() {
            document.getElementById("dataList").style.display = "block";
            $("#top-form").addClass('d-none');

            tabla = $('#tbllistado').dataTable({
                "aProcessing": true, 
                "aServerSide": true, 
                dom: 'Bfrtip', // Control elements
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdf'
                ],
                "ajax": {
                    url: "../ajax/collection.php?op=list",
                    type: "get",
                    dataType: "json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 5, // Pagination
                "order": [
                    [0, "asc"]
                ] // Order [column, (asc or desc)]
            }).DataTable();
        }

        function remove(id) {
            $('#delete-form').attr({
                'action': 'collections/'+id,
                'method': 'POST'
            })

            $('#collection-modal').modal('show')
        }
    </script>
@endsection
