<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('metodo_pago')->insert([
            ['nombre' => 'Saldo', 'descripcion' => 'Uso de dinero virtual'],
            ['nombre' => 'Efectivo', 'descripcion' => 'Pago en efectivo'],
            ['nombre' => 'Contraentrega', 'descripcion' => 'Pago cuando el producto llega al destino'],
        ]);
    }
}
