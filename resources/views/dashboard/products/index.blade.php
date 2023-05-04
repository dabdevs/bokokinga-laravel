@extends('dashboard/layout')

@section('content')
    <!-- ***** New product modal ***** -->
    <div class="modal fade" tabindex="-1" role="dialog" id="newProductModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header">
                    <h4 class="modal-title">Crear producto</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form method="POST" id="product-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="delete_images" name="delete_images">
                        <input type="hidden" id="imagesCount" name="imagesCount">
                        <div class="row">
                            <div class="col-sm-9">
                                <label for="product_name">Nombre: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="product_name" name="name">
                            </div>

                            <div class="col-sm-3">
                                <label for="price">Price: <span class="text-danger">*</span></label>
                                <input class="form-control" type="decimal" min="0" name="price" id="price">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-9 my-2 mt-4">
                                <label for="description">Descripción:</label>
                                <textarea class="form-control" name="description" id="description" cols="30" rows="3"></textarea>
                            </div>
                            <div class="col-sm-3">
                                <div class="col-xs-12">
                                    <label for="quantity">Quantity: <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" min="0" name="quantity" id="quantity">
                                </div>
                                <div class="col-xs-12">
                                    <label for="collection">Collection: <span class="text-danger">*</span></label>
                                    <select class="form-control" name="collection_id" id="collection_id">
                                        <option value="">Selecciona una colección</option>
                                        @foreach ($collections as $collection)
                                            <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <button type="button" class="ml-3 my-1 btn btn-primary" onclick="createImageUploadInput()"><i class="fa fa-photo"></i>
                                Agregar foto</button>
                        </div>
                        <div class="row">
                            <input class="d-none" type="hidden" id="photosCount">
                            <div class="col-sm-12">
                                <table>
                                    <thead class="d-none">
                                        <th class="col my-2 pl-0">Imágenes</th>
                                        <th class="col my-2 p-2">Principal</th>
                                        <th class="col my-2 p-2">Borrar</th>
                                    </thead>
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

    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <form id="delete-form" method="POST">
        <input type="hidden" name="_method" value="DELETE">
        @csrf
    </form>

    <h1>Productos</h1>

    <div class="mb-3 card p-3 d-none" id="top-form">
        <form method="POST" id="product-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="method">
            <input type="hidden" id="product_id">
            <input type="hidden" id="delete_images" name="delete_images">
            <div class="row">
                <div class="col-sm-9">
                    <label for="product_name">Nombre: <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="product_name" name="name">
                </div>

                <div class="col-sm-3">
                    <label for="price">Price: <span class="text-danger">*</span></label>
                    <input class="form-control" type="decimal" min="0" name="price" id="price">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-9 my-2 mt-4">
                    <label for="description">Descripción:</label>
                    <textarea class="form-control" name="description" id="description" cols="30" rows="3"></textarea>
                </div>
                <div class="col-sm-3">
                    <div class="col-xs-12">
                        <label for="quantity">Quantity: <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" min="0" name="quantity" id="quantity">
                    </div>
                    <div class="col-xs-12">
                        <label for="collection">Collection: <span class="text-danger">*</span></label>
                        <select class="form-control" name="collection_id" id="collection_id">
                            <option value="">Selecciona una colección</option>
                            @foreach ($collections as $collection)
                                <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <button type="button" class="ml-3 my-1 btn btn-primary" onclick="createImageUploadInput()"><i class="fa fa-photo"></i>
                    Agregar foto</button>
            </div>
            <div class="row">
                <input class="d-none" type="hidden" id="photosCount">
                <div class="col-sm-12">
                    <table>
                        <thead class="d-none">
                            <th class="col my-2 pl-0">Imágenes</th>
                            <th class="col my-2 p-2">Principal</th>
                            <th class="col my-2 p-2">Borrar</th>
                        </thead>
                        <tbody id="photos">
                            
                        </tbody>
                    </table>
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
                <a href="#newProductModal" data-toggle="modal" class="btn btn-success my-3 float-right"><i class="fa fa-plus"></i> Nuevo
                    producto</a>
            </div>

            <div class="col-xs-12">
                @if (!$products->isEmpty())
                    <table class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Colección</th>
                            <th>Imagenes</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->description }}</td>
                                    <td>{{ $product->collection->name }}</td>
                                    <td>{{ $product->primaryImage->path }}</td>
                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-edit btn-warning"><i class="bx bx-pencil"></i></a>
                                        <button type="button" class="btn btn-danger btn-delete ml-2"
                                            onclick="remove({{ $product->id }})"><i class="bx bx-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                        <tfoot>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Colección</th>
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
        const form = document.querySelector('#product-form');
        const photos = document.getElementById('photos');

        // String of images Ids to delete
        var deleteImages = '';

        // Number of product's images
        var imagesCount = 0;

        function remove(id) {
            var form = $('#delete-form');

            form.attr({
                'action': 'products/' + id,
                'method': 'POST'
            })

            Swal.fire({
                    title: "Alerta",
                    text: "Seguro quieres eliminar el producto!",
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
            name = document.getElementById('product_name').value
            price = document.getElementById('price').value
            quantity = document.getElementById('quantity').value
            collection_id = document.getElementById('collection_id').value
            document.getElementById('imagesCount').value = imagesCount

            if (name == "" || price == "" || quantity == "" || collection_id == "") {
                Swal.fire(
                    'Alert',
                    'Faltan datos!',
                    'error'
                )
                return
            }

            // If there is no images attached to the product
            var emptyInputs = 0;
            $("input[name='images[]']").map(function() {
                if ($(this).val() == "") emptyInputs++;
            }).get()

            if (imagesCount <= 0) {
                Swal.fire(
                    'Alert',
                    'Debes cargar al menos una foto!',
                    'error'
                )
                return
            }

            if (!$('input[name="primaryImage"]').is(':checked')) {
                Swal.fire(
                    'Alert',
                    'Seleccioná una imagen principal',
                    'error'
                )
                return
            }

            var form = document.querySelector('#product-form')
            form.submit()
        }

        function createImageUploadInput() {
            $('thead').removeClass('d-none')

            var emptyInputs = 0;
            $("input[name='images[]']").map(function() {
                if ($(this).val() == "") emptyInputs++;
            }).get()

            if(emptyInputs > 0) return;
            
            // create input element
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.name = 'images[]';
            input.classList.add('form-control', 'col-sm-6')
            
            // create image preview element
            const preview = document.createElement('img');
            preview.classList.add('my-2')
            preview.style.width = '250px';
            const deleteButtonDiv = document.createElement('div');
            const deleteButton = document.createElement('button');
            const inputPrimaryImg = document.createElement('input')
            inputPrimaryImg.type = 'radio';
            inputPrimaryImg.name = 'primaryImage';
            inputPrimaryImg.classList.add('form-control')

            deleteButton.innerHTML = '<i class="fa fa-times"></i>';
            deleteButtonDiv.style.display = 'none';
            deleteButton.type = 'button';
            deleteButton.classList.add('btn', 'btn-danger')
            
            // add event listeners
            input.addEventListener('change', () => {
                const file = input.files[0];
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => {
                    input.style.display = 'none';
                    preview.src = reader.result;
                    preview.style.display = 'block';
                    deleteButtonDiv.style.display = 'block';
                };
            });

            // When picture is selected as primary
            inputPrimaryImg.addEventListener('click', (e) => {
                if (input.value == '') {
                    e.preventDefault();
                    return
                }

                inputPrimaryImg.value = input.value;
            });

            const tr = document.createElement('tr');
            tr.classList.add('my-3');

            const td1 = document.createElement('td');
            const td2 = document.createElement('td');
            const td3 = document.createElement('td');

            td1.classList.add('border', 'p-2');
            td2.classList.add('border', 'p-2');
            td3.classList.add('border', 'p-2');

            td1.appendChild(input)
            td1.appendChild(preview)
            td2.appendChild(inputPrimaryImg)
            td3.appendChild(deleteButton)

            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);

            // Add an event listener to the delete button
            deleteButton.addEventListener('click', function() {
                // Remove the row
                tr.remove()
                imagesCount--
                console.log(imagesCount)
            });

            imagesCount++
            console.log(imagesCount)

            // Append input to parent
            photos.prepend(tr);
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            validate();
        });
    </script>
@endsection
