<div>
    @dump($images)
    <button class="btn btn-primary my-2" type="button" wire:click.prevent="addImage"><i class="fa fa-photo"></i> Agregar fotos</button>

    @foreach ($images as $index => $image)
        @if(isset($images[$index]))
            <div class="my-2">
                <img src="{{ $image->temporaryUrl() }}" alt="Foto {{ $index + 1 }}">
                <button class="btn btn-danger ml-2" type="button" wire:click.prevent="removeImage({{ $index }})"><i class="fa fa-times"></i> </button>
            </div>
        @else 
            <div class="d-flex d-flex-row my-2">
                <input class="form-control" name="images[]" type="file" wire:model="images.{{ $index }}">
                <button class="btn btn-danger ml-2" type="button" wire:click.prevent="removeImage({{ $index }})"><i class="fa fa-times"></i> </button>
            </div>
        @endif

    @endforeach
    

    <div class="py-2">
        <button class="btn btn-secondary" type="button" wire:click.prevent="$set('images', [])">Clear Images</button>
        <button class="btn btn-success" type="submit">Save Images</button>
    </div>
</div>
