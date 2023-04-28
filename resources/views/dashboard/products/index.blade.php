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
        <form method="POST" id="product-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="method">
            <input type="hidden" id="product_id">
            <div class="row">
                <div class="col-sm-9">
                    <label for="product_name">Nombre: <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="product_name" name="name">
                </div>

                <div class="col-sm-3">
                    <label for="price">Price: <span class="text-danger">*</span></label>
                    <input class="form-control" type="decimal" min="0" name="price" id="price">
                </div>

                <div class="col-sm-3 d-none">
                    <label for="image">Imagen: <span class="text-danger">*</span></label>
                    <input class="form-control" type="file" accept=”image/*” name="images[]" id="image">
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
                <button class="btn btn-success my-3 float-right" onclick="agregar()"><i class="fa fa-plus"></i> Nuevo
                    producto</button>
            </div>

            <div class="col-xs-12">
                @if (!$products->isEmpty())
                    <table class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Imagen</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->description }}</td>
                                    <td>{{ $product->image }}</td>
                                    <td>
                                        <button type="button" class="btn btn-edit btn-warning"
                                            onclick="edit({{ $product->id }})"><i class="bx bx-pencil"></i></button>
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
            $("#product_id").val(id)

            $('#product-form').attr({
                'action': '/admin/products/' + id,
                'method': 'POST'
            })
            $('#method').val('PUT')

            $.ajax({
                type: "GET",
                url: "/admin/products/" + id,
                success: function(resultado) {
                    document.getElementById("product_name").value = resultado.product.name;
                    document.getElementById("description").value = resultado.product.description;
                    document.getElementById("price").value = resultado.product.price;
                    document.getElementById("quantity").value = resultado.product.quantity;
                    document.getElementById("collection_id").value = resultado.product.collection_id;
                    const photos = document.getElementById('photos');
                    photos.innerHTML = ''

                    var photosCount = resultado.images.length;
                    document.getElementById('photosCount').value = photosCount
                    
                    for (let index = 0; index < photosCount; index++) {
                        const element = resultado['images'][index];
                        console.log(element)
                        const imgDisplay = document.createElement('img');
                        const primaryPhotoInput = document.createElement('input');
                        primaryPhotoInput.name = 'primary_photo';
                        primaryPhotoInput.type = 'radio';
                        primaryPhotoInput.value = element.id;

                        if(resultado.image == element.path) primaryPhotoInput.checked = true

                        const primaryPhotolabel = document.createElement('label');
                        primaryPhotolabel.innerHTML = 'Foto principal'

                        var photoIds = '';

                        imgDisplay.src = "{{ env('S3_BASE_URL') }}/" + element.path;

                        // Create the delete button element
                        const deleteButton = document.createElement('button');
                        deleteButton.type = 'button';
                        deleteButton.innerHTML = '<i class="fa fa-times"></i>';
                        deleteButton.classList.add('btn', 'btn-danger', 'ml-2');

                        // Create a container element to hold the image input and delete button
                        const container = document.createElement('div');
                        container.classList.add('row')
                        
                        const primaryBox = document.createElement('div');
                        primaryBox.appendChild(primaryPhotolabel)
                        primaryBox.appendChild(primaryPhotoInput)
                        primaryBox.classList.add('col-sm-12')
                        
                        const imgLink = document.createElement('input');
                        imgLink.name = 'photos_to_delete';
                        imgLink.classList.add('d-none')

                        // Add an event listener to the delete button
                        deleteButton.addEventListener('click', function() {
                            // Remove the image input element when the delete button is clicked
                            photoIds += photoIds == '' ? element.id : '-' + element.id
                            imgLink.value = photoIds
                            container.appendChild(imgLink);
                            imgDisplay.remove();
                            primaryPhotoInput.remove();
                            primaryBox.remove();
                            this.remove()
                            photosCount--
                            document.getElementById('photosCount').value = photosCount
                        });

                        const imageBox = document.createElement('div');
                        imageBox.classList.add('col-sm-12', 'my-2')
                        imageBox.appendChild(imgDisplay)
                        imageBox.appendChild(deleteButton)

                        container.appendChild(imageBox);
                        container.appendChild(primaryBox);

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
            var data = {};
            data.name = document.getElementById('product_name').value
            data.price = document.getElementById('price').value
            data.quantity = document.getElementById('quantity').value
            data.collection_id = document.getElementById('collection_id').value
            data.product_id = document.getElementById('product_id').value

            if (name == "" || price == "" || quantity == "" || collection_id == "" || product_id == "") {
                Swal.fire(
                    'Alert',
                    'Faltan datos!',
                    'error'
                )
                return
            }

            var uploadedPhotos = [];

            // if (!$('input[name="primary_photo"]').is(':checked') && uploadedPhotos.length > 0) {
            //     Swal.fire(
            //         'Alert',
            //         'Selecciona una foto por defecto',
            //         'error'
            //     )
            //     return
            // }

            // Check inputs with value
            $("input[name='s']").map(function() {
                value = $(this).val()
                if (value != "") uploadedPhotos.push(value)
            }).get()

            // If there is no photos attached to the product
            if ($('#photosCount').val() == 0 && uploadedPhotos.length == 0) {
                Swal.fire(
                    'Alert',
                    'Debes cargar al menos una foto!',
                    'error'
                )
                return
            }

            var form = document.querySelector('#product-form')
            form.action = '{{ route("products.store") }}' + '/' + data.product_id;
            
            form.submit()
        }

        function createImageUploadInput() {
            var emptyInput = false;
            $("input[name='images[]']").map(function() {
                value = $(this).val()
                console.log($(this).val() == "")
                if (value == "") emptyInput = true;
            }).get()

            if(emptyInput) return;
            
            // create input element
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.name = 'images[]';
            input.classList.add('form-control', 'my-2')
            
            const previewDiv = document.createElement('div');
            // create image preview element
            const preview = document.createElement('img');
            previewDiv.style.display = 'none';
            
            const deleteButtonDiv = document.createElement('div');
            const deleteButton = document.createElement('button');
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
                    previewDiv.style.display = 'block';
                    deleteButtonDiv.style.display = 'block';
                };
            });
            
            deleteButton.addEventListener('click', () => {
                input.value = '';
                preview.src = '';
                previewDiv.style.display = 'none';
                deleteButtonDiv.style.display = 'none';
            });


            previewDiv.appendChild(preview);
            deleteButtonDiv.appendChild(deleteButton);
            
            // create container element
            const container = document.createElement('div');
            container.appendChild(input);
            container.classList.add('d-flex', 'justify-content-between', 'my-2')
            container.appendChild(previewDiv);
            container.appendChild(deleteButtonDiv);
            console.log(container)
            
            // return container element
            document.getElementById('photos').prepend(container);
        }

        // const form = document.querySelector('product-form');
        // const imageUploadInput = createImageUploadInput();
        // form.appendChild(imageUploadInput);


        function submitForm(formData) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route("products.store") }}');
                xhr.onload = () => {
                if (xhr.status === 200) {
                    resolve(xhr.response);
                } else {
                    reject(xhr.statusText);
                }
                };
                xhr.onerror = () => {
                reject('Network error');
                };
                xhr.send(formData);
            });
        }

        
        //const form = document.querySelector('#product-form');
        // const imageUploadInput = createImageUploadInput();
        // form.appendChild(imageUploadInput);

        // form.addEventListener('submit', async (event) => {
        //     event.preventDefault();
        //     validate();

        //     const formData = $('#product-form').serialize();
        //     const result = await submitForm(formData);
        // });



    </script>
@endsection
