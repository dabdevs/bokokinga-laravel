<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic;

class ImageUploader extends Component
{
    use WithFileUploads;

    public $images = [];
    public $tempImages = [];

    protected $rules = [
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048' // Validations for allowed extensions and maximum size
    ];

    public function addImage()
    {
        $this->images[] = null;
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function saveImages()
    {
        // Iterate over the temporary image paths, resize the images to 1000x1000 and save them to your storage or database
        foreach ($this->tempImages as $tempImage) {
            $image = ImageManagerStatic::make(public_path('storage/' . $tempImage))->resize(1000, 1000)->encode();
            // ... Add code to save the resized image to your storage or database
        }

        $this->tempImages = []; // Clear the array of temporary files
    }

    public function render()
    {
        return view('livewire.image-uploader');
    }
}
