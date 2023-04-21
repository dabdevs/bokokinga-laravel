@extends('dashboard.layout')
@section('content')
<div class="container">
    <div class="card p-3">
        <form wire:submit.prevent="submit">
            <div>
                <label for="name">Name:</label>
                <input class="form-control" type="text" id="name" wire:model.defer="name">
                @error('name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" wire:model.defer="description"></textarea>
            </div>

            <div>
                <label for="price">Price:</label>
                <input class="form-control" type="text" id="price" wire:model.defer="price">
                @error('price') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="quantity">Quantity:</label>
                <input class="form-control" type="text" id="quantity" wire:model.defer="quantity">
                @error('quantity') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="images">Images*</label>
                <div wire:key="images">
                    @foreach ($images as $index => $image)
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="image-{{ $index }}" wire:model="images.{{ $index }}">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger" wire:click.prevent="removeImage({{ $index }})">Remove</button>
                            </div>
                        </div>
                        @error('images.' . $index) <span class="error">{{ $message }}</span> @enderror
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary" wire:click.prevent="addImage">Add Image</button>
                @error('images') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div>
                @dump($images)
                @foreach ($images as $image)
                    @if($image)
                        <img src="{{ $image->temporaryUrl() }}" alt="">
                    @else 
                        <input class="form-control" type="file" name="images[]">
                    @endif
                @endforeach
            </div>

            <button type="submit">Create Product</button>
        </form>
    </div>
</div>
@endsection