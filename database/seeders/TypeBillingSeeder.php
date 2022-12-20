<?php

namespace Database\Seeders;

use App\Models\TypeBilling;
use Illuminate\Database\Seeder;

class TypeBillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //  Voise::factory(5)->create();

        TypeBilling::insert([
            [
                'type' => 'Pagos Recurrentes',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'Prepagos (Diarios)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'Prepagos (Personalizados)',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
