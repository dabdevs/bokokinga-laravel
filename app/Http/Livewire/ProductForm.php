<?php

// app/Http/Livewire/ProductForm.php

namespace App\Http\Livewire;

use App\Models\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Storage;

class ProductForm extends Component
{
    use WithFileUploads;

    public $name;
    public $description;
    public $price;
    public $quantity;
    public $images = [];

    protected $rules = [
        'name' => 'required',
        'price' => 'required|numeric',
        'quantity' => 'required|integer',
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
    ];

    public function render()
    {
        return view('dashboard.livewire.product-form');
    }

    public function addImage()
    {
        $this->images[] = '';
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function updatedImages()
    {
        $this->validateOnly('images.*');
        foreach ($this->images as $index => $image) {
            $this->images[$index] = $image->store('public');
        }
    }

    public function createProduct()
    {
        $this->validate();

        if (empty($this->images)) {
            $this->addError('images', 'At least one image is required.');
            return;
        }

        $product = Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ]);

        foreach ($this->images as $imagePath) {
            $image = Storage::get($imagePath);
            list($width, $height) = getimagesizefromstring($image);
            $ratio = $width / $height;
            $resizedImage = imagecreatefromstring($image);
            $newWidth = 1000;
            $newHeight = 1000;
            if ($width > $height) {
                $newHeight = $newWidth / $ratio;
            } else {
                $newWidth = $newHeight * $ratio;
            }
            $resizedImage = imagescale($resizedImage, $newWidth, $newHeight);
            $resizedImagePath = str_replace('public', 'resized', $imagePath);
            Storage::put($resizedImagePath, $resizedImage);
            $product->images()->create([
                'url' => $resizedImagePath,
            ]);
        }

        $this->reset();
        session()->flash('success', 'Product created successfully.');
    }

    public function test()
    {
        dd('df');
        $validatedData = $this->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'images.*' => 'required|file|mimes:jpg,jpeg,png',
        ], [
            'images.*.required' => 'Please upload at least one image',
        ]);

        $product = Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
        ]);

        foreach ($validatedData['images'] as $image) {
            $imagePath = $image->store('public');
            $resizedImage = imagecreatefromstring(file_get_contents(storage_path('app/' . $imagePath)));
            $resizedImage = imagescale($resizedImage, 1000, 1000);
            $resizedImagePath = 'public/' . uniqid() . '.jpg';
            imagejpeg($resizedImage, storage_path('app/' . $resizedImagePath)); 
            $url = Storage::disk('s3')->putFile('', $resizedImagePath, 'public');
            Image::create([
                'product_id' => $product->id,
                'url' => $url,
            ]); 
            Storage::delete($imagePath);
            Storage::delete($resizedImagePath);
        }

        session()->flash('success', 'Product created successfully!');
        $this->reset(['name', 'description', 'price', 'quantity', 'images']);
        $this->emit('productAdded');
    }
}

