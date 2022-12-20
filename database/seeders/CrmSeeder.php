<?php

namespace Database\Seeders;

use App\Models\Crm;
use App\Models\TypeBilling;
use Illuminate\Database\Seeder;

class CrmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $input = [
            'name' => 'usuario1',
            'user' => '123',
            'email' => 'pepe@gmail.com',
            'phone' => '124321',
            'location_id' => '1',
            'owner_id' => 1,
            'crm_status' => 'Nuevo',
            'source' => 'algun lugar',
            'high_date' => '2021-10-10'
        ];

        $eloquent_model = Crm::create();
        $key = collect(config('module.crm.constants.CrmMainInformation.FIELDS'))->keys()->toArray();
        $eloquent_model->crm_main_information()->create(\Illuminate\Support\Arr::only($input, $key));

        $key = collect(config('module.crm.constants.CrmLeadInformation.FIELDS'))->keys()->toArray();
        $eloquent_model->crm_lead_information()->create(\Illuminate\Support\Arr::only($input, $key));
    }
}
