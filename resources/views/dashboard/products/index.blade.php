@extends('dashboard/layout')

@section('content')
    {{ view('shared/messages') }}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <form id="delete-form" method="POST">
        <input type="hidden" name="_method" value="DELETE">
        @csrf
    </form>

    <h1>Productos</h1>

    <div class="mb-3 card p-3 d-none" id="top-form">
        <form action="" method="POST" id="product-form" enctype="multipart/form-data" onsubmit="return validate(event)">
            @csrf
            <input type="hidden" name="_method" id="method">
            <div class="row">
                <div class="col-sm-9">
                    <label for="product_name">Nombre:</label>
                    <input class="form-control" type="text" id="product_name" name="name">
                </div>

                <div class="col-sm-3">
                    <label for="price">Price:</label>
                    <input class="form-control" type="decimal" min="0" name="price" id="price">
                </div>

                <div class="col-sm-3 d-none">
                    <label for="image">Imagen:</label>
                    <input class="form-control" type="file" accept=”image/*” name="image" id="image">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-9 my-2 mt-4">
                    <label for="description">Descripción:</label>
                    <textarea class="form-control" name="description" id="description" cols="30" rows="3"></textarea>
                </div>
                <div class="col-sm-3">
                    <div class="col-xs-12">
                        <label for="quantity">Quantity:</label>
                        <input class="form-control" type="number" min="0" name="quantity" id="quantity">
                    </div>
                    <div class="col-xs-12">
                        <label for="collection">Collection:</label>
                        <select class="form-control" name="collection_id" id="collection_id">
                            @forelse ($collections as $collection)
                                <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <button type="button" class="ml-3 btn btn-primary" onclick="createInputImage()"><i class="fa fa-photo"></i> Agregar foto</button>
            </div>

            <div class="row">
                <input type="hidden" id="photosCount">
                <div class="col-sm-6" id="photos">
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
                <button class="btn btn-success my-3 float-right" onclick="agregar()"><i class="fa fa-plus"></i> Agregar</button>
            </div>  

            <div class="col-xs-12">
                @if(!$products->isEmpty())
                    <table class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Imagen</th>
                            <th>Opciones</th>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->description }}</td>
                                    <td>{{ $product->image }}</td>
                                    <td>
                                        <button type="button" class="btn btn-edit btn-warning" onclick="edit({{ $product->id }})"><i class="bx bx-pencil"></i>&nbsp;Editar</button>
                                        <button type="button" class="btn btn-danger btn-delete ml-2" onclick="remove({{ $product->id }})"><i class="bx bx-trash"></i>&nbsp;Eliminar</button>
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
            $("#product_id").val("")
            $("#product_name").val("")

            $('#product-form').attr({
                'action': '/admin/products',
                'method': 'POST'
            })
            $('#method').val('POST')
        }

        function edit(id) {
            habilitar_botones();
            document.getElementById("dataList").style.display = "none";
            $("#top-form").removeClass('d-none');

            $('#product-form').attr({
                'action': '/admin/products/'+id,
                'method': 'POST'
            })
            $('#method').val('PUT')

            $.ajax({
                type: "GET",
                url: "/admin/products/"+id,
                success: function(resultado) {
                    document.getElementById("product_name").value = resultado['name'];
                    document.getElementById("description").value = resultado['description'];
                    document.getElementById("price").value = resultado['price'];
                    document.getElementById("quantity").value = resultado['quantity'];
                    document.getElementById("collection_id").value = resultado['collection_id'];
                    const photos = document.getElementById('photos');
                    photos.innerHTML = ''
                    var photosCount = resultado['photos'].length;
                    document.getElementById('photosCount').value = photosCount
                    
                    for (let index = 0; index < resultado['photos'].length; index++) {
                        const element = resultado['photos'][index];
                        const imgDisplay = document.createElement('img');
                        var galleryIds = '';

                        imgDisplay.src = "{{ env('S3_BASE_URL') }}/"+element.path;

                        // Create the delete button element
                        const deleteButton = document.createElement('button');
                        deleteButton.type = 'button';
                        deleteButton.innerHTML = '<i class="fa fa-times"></i>';
                        deleteButton.classList.add('btn', 'btn-danger', 'ml-2');

                        // Create a container element to hold the image input and delete button
                        const container = document.createElement('div');
                        const imgLink = document.createElement('input');
                        imgLink.name = 'photos_to_delete';
                        imgLink.classList.add('d-none')
                        
                        // Add an event listener to the delete button
                        deleteButton.addEventListener('click', function() {
                            // Remove the image input element when the delete button is clicked
                            galleryIds += galleryIds == '' ? element.id : '-'+element.id
                            imgLink.value = galleryIds
                            container.appendChild(imgLink);
                            imgDisplay.remove();
                            this.remove()
                            photosCount--
                            document.getElementById('photosCount').value = photosCount
                        });

                        container.appendChild(imgDisplay);
                        container.appendChild(deleteButton);
                        container.classList.add('d-flex', 'd-flex-row','my-2')
                        // Append input to parent
                        photos.append(container);
                    }
                }
            });
        }

        function cancelar() {
            document.getElementById("product_name").value = "";
            document.getElementById("description").value = "";
            document.getElementById("image").value = "";
            desabilitar_botones();
            $("#top-form").addClass('d-none');
            document.getElementById("dataList").style.display = "block";
        }

        function remove(id) {
            var form = $('#delete-form');

            form.attr({
                'action': 'products/'+id,
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

        function validate(e) {
            e.preventDefault()
            name = document.getElementById('product_name').value
            price = document.getElementById('price').value
            quantity = document.getElementById('quantity').value
            collection_id = document.getElementById('collection_id').value
            
            if(name == "" || price == "" || quantity == "" || collection_id == "") {
                Swal.fire(
                    'Alert',
                    'Faltan datos!',
                    'error'
                )
                return
            }

            var uploadedPhotos = [];

            // Check inputs with value
            $("input[name='image[]']").map(function(){
                value = $(this).val()
                if(value != "") uploadedPhotos.push(value)
            }).get()

            console.log($('#photosCount').val())

            // If there is no photos attached to the product
            if($('#photosCount').val() == 0 && uploadedPhotos.length == 0) {
                Swal.fire(
                    'Alert',
                    'Debes cargar al menos una foto!',
                    'error'
                )
                return
            }

            document.getElementById('product-form').submit();
        }

        function createInputImage() {
            // Create the image input element
            const imageInput = document.createElement('input');
            imageInput.type = 'file';
            imageInput.name = 'image[]';
            imageInput.classList.add('form-control');

            // Create the delete button element
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.textContent = 'Borrar';
            deleteButton.classList.add('btn', 'btn-danger', 'ml-2');

            // Add an event listener to the delete button
            deleteButton.addEventListener('click', function() {
                // Remove the image input element when the delete button is clicked
                imageInput.remove();
                this.remove()
            });

            // Create a container element to hold the image input and delete button
            const container = document.createElement('div');
            container.appendChild(imageInput);
            container.appendChild(deleteButton);
            container.classList.add('d-flex', 'd-flex-row','my-2')

            // Append input to parent
            document.getElementById('photos').append(container);
        }

    </script>
@endsection
