<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('producto')->insert([
            ['categoria_id' => 1, 'nombre' => 'Zapatilla Puma Ligth', 'descripcion' => 'Zapatos de marca', 'Precio' => 172.50, 'stock' => 20],
            ['categoria_id' => 2, 'nombre' => 'Polo Adidas x23', 'descripcion' => 'Polo de marca', 'Precio' => 72.50, 'stock' => 20],
            ['categoria_id' => 3, 'nombre' => 'Pantalon de escala', 'descripcion' => 'Pantalon Robusto', 'Precio' => 122.99, 'stock' => 20],
        ]);
    }
}
