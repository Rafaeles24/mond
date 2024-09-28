<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rol')->insert([
            ['nombre' => 'administrador', 'descripcion' => 'gestion de usuarios'],
            ['nombre' => 'empleado', 'descripcion' => 'gestion de pedidos'],
            ['nombre' => 'cliente', 'descripcion' => 'compras de productos.'],
        ]);
    }
}
