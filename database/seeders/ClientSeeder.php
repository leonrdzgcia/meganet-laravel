<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientUser;
use App\Models\TypeBilling;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $input = [
            'name' => 'Yasma',
            'user' => 'usuario10000001',
            'password' => '123',
            'type_of_billing_id' => 1,
            'email' => 'pepe@gmail.com',
            'phone' => '124321',
            'owner' => 1,
            'status' => 'Nuevo',
        ];

        $eloquent_model = Client::create();
        $key = collect(config('module.client.constants.ClientMainInformation.FIELDS'))->keys()->toArray();
        $eloquent_model->client_main_information()->create(\Illuminate\Support\Arr::only($input, $key));

        $key = collect(config('module.client.constants.ClientAdditionalInformation.FIELDS'))->keys()->toArray();
        $eloquent_model->client_additional_information()->create(\Illuminate\Support\Arr::only($input, $key));

        $eloquent_model->user()->create([
            'user' => $input['user']
        ]);

        $eloquent_model->billing_configuration()->create([
            'type_billing_id' => '1'
        ]);
        $eloquent_model->reminder_configuration()->create();
        $eloquent_model->billing_address()->create();

        $eloquent_model->balance()->create(['amount' => 500]);
        $eloquent_model->internet_service()->create([
            'internet_id' => 1,
            'client_bundle_service_id' => null,
            'description' => 'Basico_20MB',
            'amount' => '1',
            'unity' => '1',
            'price' => '349',
            'pay_period' => 'Periodo 1',
            'start_date' => '2021-09-29T08:25',
            'finish_date' => null,
            'discount' => 0,
            'discount_percent' => null,
            'start_date_discount' => null,
            'end_date_discount' => null,
            'discount_message' => null,
            'estado' => 'Activado',
            'router_id' => 1,
            'client_name' => 'usuario10000001',
            'password' => 'TvoqpeeL',
            'ipv4_assignment' => 'IP Estatica',
            'ipv4' => '5',
            'additional_ipv4' => null,
            'ipv4_pool' => null,
            'ipv6' => null,
            'delegated_ipv6' => null,
            'mac' => null,
            'portid' => null,
            'deployed' => 1,
        ]);
    }
}
