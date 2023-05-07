@extends('dashboard/layout')

@section('content')
    
    <form id="delete-form" method="POST">
        <input type="hidden" name="_method" value="DELETE">
        @csrf
    </form>

    <h1>{{ $collection->name }}</h1>

    <div class="mb-3 card p-3" id="top-form">
        <form method="POST" action="{{ route('collections.update', $collection->id) }}" id="collection-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{ view('shared/messages') }}

            <input type="hidden" id="collection_id">
            <input type="hidden" id="delete_images" name="delete_images">
            <input type="hidden" id="imagesCount" name="imagesCount">
            <div class="row">
                <div class="col-sm-9">
                    <label for="collection_name">Nombre: <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="collection_name" name="name" value="{{ $collection->name }}">
                </div>

                <div class="col-sm-3">
                    <label for="price">Price: <span class="text-danger">*</span></label>
                    <input class="form-control" type="decimal" min="0" name="price" id="price" value="{{ $collection->price }}">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-9 my-2 mt-4">
                    <label for="description">Descripción:</label>
                    <textarea class="form-control" name="description" id="description" cols="30" rows="3">{{ $collection->description }}</textarea>
                </div>
                <div class="col-sm-3">
                    <div class="col-xs-12">
                        <label for="quantity">Quantity: <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" min="0" name="quantity" id="quantity" value="{{ $collection->quantity }}">
                    </div>
                    <div class="col-xs-12">
                        <label for="collection">Collection: <span class="text-danger">*</span></label>
                        <select class="form-control" name="collection_id" id="collection_id">
                            <option value="">Selecciona una colección</option>
                            @foreach ($collections as $collection)
                                <option value="{{ $collection->id }}" @if($collection->collection_id == $collection->id) selected @endif  >{{ $collection->name }}</option>
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
                <input class="d-none" type="hidden" id="photosCount" value="{{ $collection->images->count() }}">
                <div class="col-sm-12">
                    <table style="max-width=100%">
                        <thead>
                            <th class="col my-2 pl-0">Imágenes</th>
                            <th class="col my-2 p-2">Principal</th>
                            <th class="col my-2 p-2">Borrar</th>
                        </thead>
                        <tbody id="photos">
                            @foreach ($collection->images->reverse() as $image)
                                <tr id="row-{{ $image->id }}">
                                    <td class="border p-2">
                                        <img width="250px" src="{{ env('S3_BASE_URL') }}/{{ $image->path }}" alt="collection picture">
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
                <a href="{{ route('collections.index') }}" class="col-sm-2 mr-1 btn btn-secondary" id="Cancelar"><i
                        class="fa fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        const form = document.querySelector('#collection-form');
        const photos = document.getElementById('photos');

        // Number of collection's images
        var imagesCount = parseInt('{{ $collection->images->count() }}');

        function validate() {
            name = document.getElementById('collection_name').value
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

            // If there is no images attached to the collection
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
