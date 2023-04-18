<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'quantity',
        'collection_id'
    ];

    /**
     * Get the collection that owns the Product
     *
     */
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
