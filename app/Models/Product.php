<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory;
    use Searchable;

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

    public function photos()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function searchableAs()
    {
        return 'product_index';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Define searchable fields here
        $array['name'] = $this->name;
        $array['description'] = $this->description;
        $array['price'] = $this->price;
        $array['collection_id'] = $this->collection_id;

        return $array;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->searchable();
        });

        static::updated(function ($model) {
            $model->searchable();
        });

        static::deleted(function ($model) {
            $model->unsearchable();
        });
    }
}
