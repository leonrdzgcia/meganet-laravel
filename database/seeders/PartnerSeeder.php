<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Partner::insert([
            ['name' => 'Socio1'],
            ['name' => 'Socio2'],
            ['name' => 'Socio3']
        ]);
    }
}
