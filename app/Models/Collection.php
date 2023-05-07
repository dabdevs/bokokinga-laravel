<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'banner',
    ];

    /**
     * Get all of the products for the Collection
     *
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the latest products for the Collection
     *
     */
    public function latestProducts()
    {
        return $this->hasMany(Product::class)->orderBy('id', 'desc')->limit(9);
    }
}
