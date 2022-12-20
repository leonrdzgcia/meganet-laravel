<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Municipality;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Municipality::truncate();
        $data = [
            ['name' => 'Nextlalpan', 'state_id' => 7],
      
        ];

        Municipality::insert($data);
    }
}






























