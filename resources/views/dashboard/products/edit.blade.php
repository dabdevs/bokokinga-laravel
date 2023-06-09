@extends('dashboard/layout')

@section('content')
    
    <form id="delete-form" method="POST">
        <input type="hidden" name="_method" value="DELETE">
        @csrf
    </form>

    <h1>{{ $product->name }}</h1>

    <div class="mb-3 card p-3" id="top-form">
        <form method="POST" action="{{ route('products.update', $product->id) }}" id="product-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{ view('shared/messages') }}

            <input type="hidden" id="product_id">
            <input type="hidden" id="delete_images" name="delete_images">
            <input type="hidden" id="imagesCount" name="imagesCount">
            <div class="row">
                <div class="col-sm-9">
                    <label for="product_name">Nombre: <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="product_name" name="name" value="{{ $product->name }}">
                </div>

                <div class="col-sm-3">
                    <label for="price">Price: <span class="text-danger">*</span></label>
                    <input class="form-control" type="decimal" min="0" name="price" id="price" value="{{ $product->price }}">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-9 my-2 mt-4">
                    <label for="description">Descripción:</label>
                    <textarea class="form-control" name="description" id="description" cols="30" rows="3">{{ $product->description }}</textarea>
                </div>
                <div class="col-sm-3">
                    <div class="col-xs-12">
                        <label for="quantity">Quantity: <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" min="0" name="quantity" id="quantity" value="{{ $product->quantity }}">
                    </div>
                    <div class="col-xs-12">
                        <label for="collection">Collection: <span class="text-danger">*</span></label>
                        <select class="form-control" name="collection_id" id="collection_id">
                            <option value="">Selecciona una colección</option>
                            @foreach ($collections as $collection)
                                <option value="{{ $collection->id }}" @if($product->collection_id == $collection->id) selected @endif  >{{ $collection->name }}</option>
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
                <input class="d-none" type="hidden" id="photosCount" value="{{ $product->images->count() }}">
                <div class="col-sm-12">
                    <table style="max-width=100%">
                        <thead>
                            <th class="col my-2 pl-0">Imágenes</th>
                            <th class="col my-2 p-2">Principal</th>
                            <th class="col my-2 p-2">Borrar</th>
                        </thead>
                        <tbody id="photos">
                            @foreach ($product->images->reverse() as $image)
                                <tr id="row-{{ $image->id }}">
                                    <td class="border p-2">
                                        <img width="250px" src="{{ env('S3_BASE_URL') }}/{{ $image->path }}" alt="product picture">
                                    </td>
                                    <td class="border p-2"><input type="radio" name="primaryImage" value="{{ $image->id }}" @if($image->is_primary) checked @endif></td>
                                    <td class="border p-2">
                                        <button class="btn btn-danger" onclick="deleteRow('{{ $image->id }}')"> <i class="fa fa-times"></i> </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row p-3">
                <button type="submit" class="col-sm-2 mr-1 btn btn-success" id="Guardar"><i
                        class="fa fa-save"></i> Guardar</button>
                <a href="{{ route('products.index') }}" class="col-sm-2 mr-1 btn btn-secondary" id="Cancelar"><i
                        class="fa fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        const form = document.querySelector('#product-form');
        const photos = document.getElementById('photos');

        // String of images Ids to delete
        var deleteImages = '';

        // Number of product's images
        var imagesCount = parseInt('{{ $product->images->count() }}');

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
            $('thead').removeClass('d-none')
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
                    photos.innerHTML = ''

                    var photosCount = resultado.images.length;
                    document.getElementById('photosCount').value = photosCount
                    
                    // Printing images
                    for (let index = 0; index < photosCount; index++) {
                        const element = resultado['images'][index];
                        const imgDisplay = document.createElement('img');
                        const inputPrimaryImg = document.createElement('input')
                        const tr = document.createElement('tr');
                        const td1 = document.createElement('td');
                        const td2 = document.createElement('td');
                        const td3 = document.createElement('td');

                        inputPrimaryImg.type = 'radio';
                        inputPrimaryImg.name = 'primaryImage';
                        inputPrimaryImg.classList.add('form-control')
                        inputPrimaryImg.value = element.id;

                        tr.classList.add('my-3');
                        imgDisplay.classList.add('cursor-pointer')
                        imgDisplay.src = "{{ env('S3_BASE_URL') }}/" + element.path;
                        
                        if(element.is_primary) {
                            imgDisplay.classList.add('img-thumbnail', 'border-success')
                            imgDisplay.value = element.id
                            inputPrimaryImg.checked = true;
                        }

                        // Select primary image
                        imgDisplay.addEventListener('click', function() {
                            // Remove the image input element when the delete button is clicked
                            $('img').removeClass('border-success img-thumbnail')
                            this.classList.add('border-success', 'img-thumbnail')
                            primaryImage.value = element.id
                        });

                        // Create the delete button element
                        const deleteButton = document.createElement('button');
                        deleteButton.type = 'button';
                        deleteButton.innerHTML = '<i class="fa fa-times"></i>';
                        deleteButton.classList.add('btn', 'btn-danger', 'ml-2');

                        var deleteImages = '';

                        // Add an event listener to the delete button
                        deleteButton.addEventListener('click', function() {
                            // Remove the image input element when the delete button is clicked
                            deleteImages += deleteImages == '' ? element.id : '-' + element.id
                            imgDisplay.remove();
                            inputPrimaryImg.remove();
                            this.remove()
                            photosCount--
                            document.getElementById('photosCount').value = photosCount
                            document.querySelector('#delete_images').value = deleteImages
                        });

                        td1.appendChild(imgDisplay)
                        td2.appendChild(inputPrimaryImg)
                        td3.appendChild(deleteButton)

                        tr.appendChild(td1);
                        tr.appendChild(td2);
                        tr.appendChild(td3);

                        // Append input to parent
                        photos.append(tr);
                    }
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


        function deleteRow(id) {
            var row = document.getElementById('row-'+id)
            deleteImages += deleteImages == '' ? id : '-' + id
            document.querySelector('#delete_images').value = deleteImages
            imagesCount--
            console.log(imagesCount)
            row.remove()
        }
    </script>
@endsection
