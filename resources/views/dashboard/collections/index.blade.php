@extends('dashboard/layout')

@section('content')
    <!-- ***** New collection modal ***** -->
    <div class="modal fade" tabindex="-1" role="dialog" id="newCollectionModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header">
                    <h4 class="modal-title">Crear colección</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form method="POST" id="collection-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="collection_name">Nombre: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="collection_name" name="name">
                            </div>
                            <div class="col-sm-12 my-2 mt-4">
                                <label for="description">Descripción:</label>
                                <textarea class="form-control" name="description" id="description" cols="30" rows="3"></textarea>
                            </div>
                            <div class="col-sm-8">
                                <label for="image">Imagen: <span class="text-danger">*</span> <small class="text-info"> <br> Extensión: jpg, jpeg, png. Dimensión recomendada: 1600px x 500px</small></label>
                                <input class="form-control" type="file" id="image" name="image">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table>
                                    <tbody id="photos">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row p-3">
                            <button type="submit" class="col-sm-2 mr-1 btn btn-success" id="Guardar"><i
                                    class="fa fa-save"></i> Guardar</button>
                            <button type="button" class="col-sm-2 mr-1 btn btn-secondary" id="Cancelar" data-dismiss="modal"><i
                                    class="fa fa-times"></i> Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{ view('shared/messages') }}
    <form id="delete-form" method="POST">
        @method('DELETE')
        @csrf
    </form>

    <h1>Colecciones</h1>

    <div class="mb-3 card p-3">
        <div class="row table-responsive pl-3">
            <div class="col-xs-12">
                <a href="#newCollectionModal" data-toggle="modal" class="btn btn-success my-3 float-right"><i class="fa fa-plus"></i> Nueva
                    colección</a>
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
                                        <a href="{{ route('collections.edit', $collection->id) }}" class="btn btn-edit btn-warning"
                                            onclick="edit({{ $collection->id }})"><i class="bx bx-pencil"></i></a>
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
        const form = document.querySelector('#collection-form');
        const photos = document.getElementById('photos');
        const image = document.getElementById('image');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            validate();
        });

        function remove(id) {
            var form = $('#delete-form');

            form.attr({
                'action': 'collections/' + id,
                'method': 'POST'
            })

            Swal.fire({
                    title: "Alerta",
                    text: "Seguro quieres eliminar la colección?",
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

        function validate() {
            if (name.value == "" || image.value == "") {
                Swal.fire(
                    'Alert',
                    'Faltan datos!',
                    'error'
                )
                return
            }

            form.submit()
        }

        image.addEventListener('change', () => {
            const file = image.files[0];
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                preview = document.createElement('img');
                preview.classList.add('my-2');
                preview.style.maxWidth = '600px';
                preview.src = reader.result;
                photos.innerHTML = '';
                photos.append(preview);
            };
        });
    </script>
@endsection
