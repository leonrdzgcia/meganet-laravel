<?php

namespace Database\Seeders;

use App\Models\Voise;
use App\Models\TypeBilling;
use Illuminate\Database\Seeder;

class VoiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //  Voise::factory(5)->create();
         $voise = Voise::create([
            'title' => "Gpon_voip",
            'service_name' => "VoIP",
            'update_description' => false,
            'price' => "160",
            'update_service' => false,
            'type' => "VoIP",
            'tax_include' => true,
            'tax' => "0",
            'prepaid_period' => null,
            'rates_to_change' =>null,
            'transaction_category' =>null,
            'transaction_category_for_calls' =>null,
            'transaction_category_for_messages' =>null,
            'transaction_category_for_data' =>null,
            'Available_in_self_registration' =>false,
            'Bandwidth' =>null,
            'Priority' =>'1'
        ]);
        $voise->billings()->attach(
            [1,2,3]
        );
        Voise::create([
            'title' => "Telefonia_en_paquete",
            'service_name' => "Telefonia_en_paquete",
            'update_description' => false,
            'price' => "160",
            'update_service' => false,
            'type' => "VoIp",
            'tax_include' => true,
            'tax' => "0",
            'prepaid_period' => "Mensual",
            'rates_to_change' =>null,
            'transaction_category' =>null,
            'transaction_category_for_calls' =>null,
            'transaction_category_for_messages' =>null,
            'transaction_category_for_data' =>null,
            'Available_in_self_registration' =>false,
            'Bandwidth' =>null,
            'Priority' =>'1'
        ]);
        $voise->billings()->attach(
            [1,2,3]
        );
        Voise::create([
            'title' => "Telefonia_en_paquete",
            'service_name' => "Telefonia_en_paquete",
            'update_description' => false,
            'price' => "70",
            'update_service' => false,
            'type' => "VoIp",
            'tax_include' => true,
            'tax' => "16",
            'prepaid_period' => "Mensual",
            'rates_to_change' =>null,
            'transaction_category' =>null,
            'transaction_category_for_calls' =>null,
            'transaction_category_for_messages' =>null,
            'transaction_category_for_data' =>null,
            'Available_in_self_registration' =>false,
            'Bandwidth' =>null,
            'Priority' =>'1'
        ]);
        $voise->billings()->attach(
            [1,2,3]
        );
        Voise::create([
            'title' => "Telefonia_Mundial",
            'service_name' => "Telefonia_Mundial",
            'update_description' => false,
            'price' => "70",
            'update_service' => false,
            'type' => "VoIp",
            'tax_include' => true,
            'tax' => "16",
            'prepaid_period' => "Mensual",
            'rates_to_change' =>null,
            'transaction_category' =>null,
            'transaction_category_for_calls' =>null,
            'transaction_category_for_messages' =>null,
            'transaction_category_for_data' =>null,
            'Available_in_self_registration' =>false,
            'Bandwidth' =>null,
            'Priority' =>'1'
        ]);
        $voise->billings()->attach(
            [1,2,3]
        );
        Voise::create([
            'title' => "voz_paquete",
            'service_name' => "voz_paquete",
            'update_description' => false,
            'price' => "70",
            'update_service' => false,
            'type' => "VoIp",
            'tax_include' => true,
            'tax' => "16",
            'prepaid_period' => "Mensual",
            'rates_to_change' =>null,
            'transaction_category' =>null,
            'transaction_category_for_calls' =>null,
            'transaction_category_for_messages' =>null,
            'transaction_category_for_data' =>null,
            'Available_in_self_registration' =>false,
            'Bandwidth' =>null,
            'Priority' =>'1'
        ]);
        $voise->billings()->attach(
            [1,2,3]
        );
    }
}
