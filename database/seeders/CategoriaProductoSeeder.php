<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categoria_producto')->insert([
            ['nombre' => 'Calzado', 'descripcion' => 'Zapatos a tu medida'],
            ['nombre' => 'Polos', 'descripcion' => 'Fresco para tu look'],
            ['nombre' => 'Pantalones', 'descripcion' => 'Elegante que combina'],
        ]);
    }
}
