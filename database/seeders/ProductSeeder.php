<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::truncate();
        Gallery::truncate();

        /**
         * (
    'Uñas Spider Amarrillo',
    8.99,
    15,
    'unas-spider-amarrillo.jpg',
    4,
    'reciclable-eco'
  ),
  (
    'Uñas I love You Rojo',
    4.50,
    100,
    'unas-iloveu.jpg',
    4,
    ''
  ),
  (
    'Necklace Oro Africa',
    49.75,
    10,
    'necklace-oro-africa.jpg',
    2,
    'metal'
  ),
  (
    'Jacket Verde Sun',
    89.99,
    30,
    'jacket-verde-sun.jpg',
    3,
    'tela-arte'
  ),
  (
    'Lentes Aviator Marron',
    12.99,
    150,
    'lentes-aviator-marron.jpg',
    4,
    'importado'
  ),
  (
    'Pañuelo No time for fake people',
    4.99,
    150,
    'panuelo-no-time-for-fake-people.jpg',
    3,
    ''
  ),
  (
    'I dont smoke t-shirt',
    20,
    250,
    'i-dont-smoke-tshirt.jpg',
    3,
    ''
  ),
  (
    'Anillo redondo piedra azul',
    4.99,
    215,
    'anillo-redondo-piedra-azul.jpg',
    2,
    'metal-plata'
  ),
  (
    'Escalera apoya toalla',
    46.99,
    215,
    'escalera-apoya-toalla.jpg',
    1,
    'madera-eco'
  ),
  (
    'Antique painting plate',
    46.99,
    215,
    'antique-painting-plate.jpg',
    1,
    'metal'
  );
         */

        $product1 = [
            'name' => 'Uñas acrílicas hojas verdes',
            'slug' => 'Uñas-acrílicas-hojas-verdes',
            'description' => 'Uñas de plástico...',
            'price' =>  12.99,
            'quantity' => 50,
            'collection_id' => 4,
            'images' => ["url1", "url2"]
        ];

        $this->createOne($product1);

        $product2 = [
            'name' => 'Uñas Spider Amarrillo',
            'slug' => 'Uñas-Spider-Amarrillo',
            'description' => 'Uñas Spider Amarrillo...',
            'price' =>  24.99,
            'quantity' => 20,
            'collection_id' => 4,
            'images' => ["url1", "url2"]
        ];

        $this->createOne($product2);

        $product3 = [
            'name' => 'Jacket Verde Sun',
            'slug' => 'Jacket-Verde-Sun',
            'description' => 'Jacket Verde Sun...',
            'price' =>  99.99,
            'quantity' => 15,
            'collection_id' => 3,
            'images' => ["url1"]
        ];

        $this->createOne($product3);

        $product4 = [
            'name' => 'Lentes Aviator Marron',
            'slug' => 'Lentes-Aviator-Marron',
            'description' => 'Lentes Aviator Marron...',
            'price' =>  12.99,
            'quantity' => 150,
            'collection_id' => 4,
            'images' => ["url1"]
        ];

        $this->createOne($product4);

        $product5 = [
            'name' => 'I dont smoke t-shirt',
            'slug' => 'I-dont-smoke-t-shirt',
            'description' => 'I dont smoke t-shirt...',
            'price' =>  24.99,
            'quantity' => 23,
            'collection_id' => 3,
            'images' => ["url1"]
        ];

        $this->createOne($product5);

        $product5 = [
            'name' => 'Anillo redondo piedra azul',
            'slug' => 'Anillo-redondo-piedra-azul',
            'description' => 'Anillo redondo piedra azul...',
            'price' =>  24.99,
            'quantity' => 30,
            'collection_id' => 2,
            'images' => ["url1", "url2", "url3"]
        ];

        $this->createOne($product5);

        $product6 = [
            'name' => 'Antique painting plate',
            'slug' => 'Antique-painting-plate',
            'description' => '',
            'price' =>  9.99,
            'quantity' => 200,
            'collection_id' => 1,
            'images' => ["url1", "url2", "url3"]
        ];

        $this->createOne($product6);
    }

    function createOne(array $data) {
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
                Gallery::create([
                    'product_id' => $product->id,
                    'path' => $path
                ]);
            }
        }
    }
}
