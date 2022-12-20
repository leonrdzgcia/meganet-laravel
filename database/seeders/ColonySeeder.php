<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Colony;

class ColonySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Colony::truncate();
        $data = [
            ['name' => 'Santiago Atocan', 'municipality_id' => 1],
            ['name' => 'Ejido de Santana' , 'municipality_id' => 1],
            ['name' => 'San Mateo Acuitlapilco' , 'municipality_id' => 1],
            ['name' => 'Colonia Pozos y Vías'  , 'municipality_id' => 1],
            ['name' => 'Compuerta Oriente ' , 'municipality_id' => 1],
            ['name' => 'Cuatro Caballerías' , 'municipality_id' => 1],
            ['name' => 'Ejido Tenopalco' , 'municipality_id' => 1],
            ['name' => 'Ejido Visitación' , 'municipality_id' => 1],
            ['name' => 'Ejido Jaltenco' , 'municipality_id' => 1],
            ['name' => 'Ex-Hacienda Santa Inés' , 'municipality_id' => 1], 
            ['name' => 'Granja El Gato Gordo' , 'municipality_id' => 1],
            ['name' => 'Granja Alférez' , 'municipality_id' => 1],
            ['name' => 'Granja Laguna' , 'municipality_id' => 1],
            ['name' => 'Los Pastales' , 'municipality_id' => 1],
            ['name' => 'Barrio San Francisco Molonco'  , 'municipality_id' => 1],
            ['name' => 'Paseos del Valle' , 'municipality_id' => 1],
            ['name' => 'Prados San Francisco' , 'municipality_id' => 1],
            ['name' => 'Rancho Guadalupe Palo Grande' , 'municipality_id' => 1],
            ['name' => 'Colonia los Aguiluchos' , 'municipality_id' =>1],
            ['name' => 'Rancho los Pirules' , 'municipality_id' => 1],
            ['name' => 'Rancho San Antonio (El Oasis)'  , 'municipality_id' => 1],
            ['name' => 'Rancho Macan' , 'municipality_id' => 1],
            ['name' => 'Granja Real' , 'municipality_id' => 1],
            ['name' => 'Rancho Labra' , 'municipality_id' => 1],
            ['name' => 'Santa Ana Nextlalpan' , 'municipality_id' => 1],
            ['name' => 'Santa Inés' , 'municipality_id' => 1],
            ['name' => 'Terrenos Comunales Xaltocan' , 'municipality_id' => 1],
            ['name' => 'Tierra de Santa Inés' , 'municipality_id' => 1],
            ['name' => 'San Miguel Jaltocan' , 'municipality_id' => 1],
        ];

        Colony::insert($data);
    }
}
