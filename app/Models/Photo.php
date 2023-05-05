<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    public static function upload($image, $path, $new=true)
    {   
        if($new || $path == null) $path = $image->hashName($path); 
        
        // Upload the image to Amazon S3 storage
        Storage::disk('s3')->put($path, $image);
        
        // Return the URL of the uploaded image
        return $path;
    }

    public static function resizeAndUpload($image, $path, $width, $height, $new = true)
    { 
        // Resize the image using Intervention Image
        $resizedImage = Image::make($image)->fit($width, $height);

        // If uploading image for the first time
        if ($new) $path = $image->hashName($path);

        // Upload the resized image to Amazon S3 storage
        Storage::disk('s3')->put($path, $resizedImage->stream()->detach());

        // Return the URL of the uploaded image
        return $path;
    } 

    public static function remove($path)
    {
        // Delete image Amazon S3 storage
        return Storage::disk('s3')->delete($path);
    }
}

