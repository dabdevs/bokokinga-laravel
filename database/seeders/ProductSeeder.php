<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Seeder;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product1 = [
            'name' => 'Uñas acrílicas hojas verdes',
            'slug' => 'Uñas-acrílicas-hojas-verdes',
            'description' => 'Uñas de plástico...',
            'price' =>  12.9,
            'quantity' => 50,
            'collection_id' => 4,
            'images' => ["products/unas-hojas-verdes.jpg"]
        ];

        $this->createOne($product1);

        $product2 = [
            'name' => 'Uñas Spider Amarrillo',
            'slug' => 'Uñas-Spider-Amarrillo',
            'description' => 'Uñas Spider Amarrillo...',
            'price' =>  24.99,
            'quantity' => 20,
            'collection_id' => 4,
            'images' => ["products/unas-spider-amarrillo.jpg", "products/unas-spider-amarrillo-2.jpg", "products/unas-spider-amarrillo-3.jpg"]
        ];

        $this->createOne($product2);

        $product3 = [
            'name' => 'Jacket Verde Sun',
            'slug' => 'Jacket-Verde-Sun',
            'description' => 'Jacket Verde Sun...',
            'price' =>  99.99,
            'quantity' => 15,
            'collection_id' => 3,
            'images' => ["products/jacket-verde-sun.jpg"]
        ];

        $this->createOne($product3);

        $product4 = [
            'name' => 'Lentes Aviator Marron',
            'slug' => 'Lentes-Aviator-Marron',
            'description' => 'Lentes Aviator Marron...',
            'price' =>  12.99,
            'quantity' => 150,
            'collection_id' => 4,
            'images' => ["products/lentes-aviator-marron.jpg"]
        ];

        $this->createOne($product4);

        $product5 = [
            'name' => 'I dont smoke t-shirt',
            'slug' => 'I-dont-smoke-t-shirt',
            'description' => 'I dont smoke t-shirt...',
            'price' =>  24.99,
            'quantity' => 23,
            'collection_id' => 3,
            'images' => ["products/i-dont-smoke-tshirt.jpg"]
        ];

        $this->createOne($product5);

        $product5 = [
            'name' => 'Anillo redondo piedra azul',
            'slug' => 'Anillo-redondo-piedra-azul',
            'description' => 'Anillo redondo piedra azul...',
            'price' =>  24.99,
            'quantity' => 30,
            'collection_id' => 2,
            'images' => ["products/anillo-redondo-pidra-azul.jpg", "url2", "url3"]
        ];

        $this->createOne($product5);

        $product6 = [
            'name' => 'Antique painting plate',
            'slug' => 'Antique-painting-plate',
            'description' => '',
            'price' =>  9,
            'quantity' => 200,
            'collection_id' => 1,
            'images' => ["products/antique-painting-plate.jpg"]
        ];

        $this->createOne($product6);
    }

    function createOne(array $data)
    {
        $product = new Product;
        $product->name = $data["name"];
        $product->slug = $data["slug"];
        $product->description = $data["description"];
        $product->price = $data["price"];
        $product->quantity = $data["quantity"];
        $product->collection_id = $data["collection_id"];

        $product->save();

        if (count($data["images"]) > 0) {
            foreach ($data["images"] as $path) {
                $product->images()->create(
                    [
                        'product_id' => $product->id,
                        'path' => $path,
                        'is_primary' => true
                    ]
                );
            }
        }
    }
}
