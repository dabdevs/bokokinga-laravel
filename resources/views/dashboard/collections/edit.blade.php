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

            <div class="row">
                <div class="col-sm-12">
                    <label for="collection_name">Nombre: <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="collection_name" name="name" value="{{ $collection->name }}">
                </div>
                <div class="col-sm-12 my-2 mt-4">
                    <label for="description">Descripción:</label>
                    <textarea class="form-control" name="description" id="description" cols="30" rows="3">{{ $collection->description }}</textarea>
                </div>
                <div class="col-sm-8">
                    <label for="image">Imagen: <span class="text-danger">*</span> <small class="text-info"> <br> Extensión: jpg, jpeg, png. Dimensión recomendada: 1600px x 500px</small></label>
                    <input class="form-control" type="file" id="image" name="image">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="photos">
                    <img class="my-2" style="max-width:600px" src="{{ env('S3_BASE_URL'). "/" .$collection->image }}" alt="">
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
        const image = document.getElementById('image');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            validate();
        });

        function validate() {
            if (name.value == "") {
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
