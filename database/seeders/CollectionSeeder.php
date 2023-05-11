<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::insert("INSERT INTO 
                        collections (`id`,`name`, `slug`, `description`,`image`,`show_in_banner`)
                    VALUES
                        (1,'Decoración', 'decoracion', 'Decora tu hogar con productos eco friendly', 'collections/decoration.jpg', 1),
                        (2,'Joyería', 'joyeria', 'Joyas confeccionadas con productos reciclados', 'collections/jewelry.jpg', 1),
                        (3,'Ropa', 'ropa', 'Ropa pintada y/o diseñada a mano','collections/clothes.jpg', 1),
                        (4,'Accesorios', 'accesorios', 'Todo tipo de accesorios', 'collections/accessories.jpg', 1);");
    }
} 
