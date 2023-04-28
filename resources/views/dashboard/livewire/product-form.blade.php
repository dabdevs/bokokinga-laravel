@extends('dashboard/layout')

@section('content')
    <div class="card p-3">

        <form wire:submit.prevent="test">
            <div class="row">
                <div class="col-sm-12">
                    <label for="name">Name:</label>
                    <input class="form-control" type="text" name="name" wire:model.lazy="name">
                    @error('name') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="col-sm-12">
                    <label for="description">Description:</label>
                    <textarea class="form-control" name="description" wire:model.lazy="description"></textarea>
                </div>

                <div class="col-sm-6">
                    <label for="price">Price:</label>
                    <input class="form-control" type="text" name="price" wire:model.lazy="price">
                    @error('price') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="col-sm-2">
                    <label for="quantity">Quantity:</label>
                    <input class="form-control" type="text" name="quantity" wire:model.lazy="quantity">
                    @error('quantity') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="col-sm-6 d-none">
                    <label for="images">Images*</label>
                    <div wire:key="images">
                        @foreach ($images as $index => $image)
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" name="image-{{ $index }}" wire:model="images.{{ $index }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger" wire:click.prevent="removeImage({{ $index }})">Remove</button>
                                </div>
                            </div>
                            @error('images.' . $index) <span class="error">{{ $message }}</span> @enderror
                        @endforeach
                    </div>
                    @error('images') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="col-sm-6">
                    <livewire:image-uploader />
                </div>

                <div class="col-sm-12">
                    <button class="btn btn-success" type="submit">Save</button>
                </div>

                <div class="d-none">
                    @foreach ($images as $image)
                        @if($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="">
                        @else 
                            <input class="form-control" type="file" name="images[]">
                        @endif
                    @endforeach
                </div>
            </div>
        </form>
    </div>  
@endsection
