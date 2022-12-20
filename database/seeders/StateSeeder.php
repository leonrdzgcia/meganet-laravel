<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::truncate();
        $data = [
            ['name' => 'Aguascalientes'],
            ['name' => 'Baja California'],
            ['name' => 'Baja California Sur'],
            ['name' => 'Campeche'],
            ['name' => 'Chiapas'],
            ['name' => 'Chihuahua'],
            ['name' => 'Ciudad de México'],
            ['name' => 'Coahuila'],
            ['name' => 'Colima'],
            ['name' => 'Durango'],
            ['name' => 'Estado de México'],
            ['name' => 'Guanajuato'],
            ['name' => 'Guerrero'],
            ['name' => 'Hidalgo'],
            ['name' => 'Jalisco'],
            ['name' => 'Michoacán'],
            ['name' => 'Morelos'],
            ['name' => 'Nayarit'],
            ['name' => 'Nuevo León'],
            ['name' => 'Oaxaca'],
            ['name' => 'Puebla'],
            ['name' => 'Querétaro'],
            ['name' => 'Quintana Roo'],
            ['name' => 'San Luis Potosí'],
            ['name' => 'Sinaloa'],
            ['name' => 'Sonora'],
            ['name' => 'Tabasco'],
            ['name' => 'Tamaulipas'],
            ['name' => 'Tlaxcala'],
            ['name' => 'Veracruz'],
            ['name' => 'Yucatán'],
            ['name' => 'Zacatecas'],
        ];

         State::insert($data);

    }
}
