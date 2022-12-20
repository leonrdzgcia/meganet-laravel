<?php

namespace Database\Seeders;

use App\Models\DocumentCrm;
use Illuminate\Database\Seeder;

class DocumentCrmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DocumentCrm::create([
            'crm_id' => 1,
            'title' => 'Documento1',
            'description' => 'El superdocumento 1',
            'visible' => false
        ]);

    }
}
