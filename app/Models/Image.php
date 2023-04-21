<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'is_primary'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute() 
    {
        return Storage::disk('s3')->url($this->url);
    }
}
