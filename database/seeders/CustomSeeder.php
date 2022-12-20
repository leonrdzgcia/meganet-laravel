<?php

namespace Database\Seeders;

use App\Models\Custom;
use Illuminate\Database\Seeder;

class CustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $custom = Custom::create([
            'title' => 'Kit Instalacion',
            'service_name' => 'Kit Instalacion',
            'update_description' => false,
            'price' => '2500',
            'update_service' => false,
            'partners' => null,
            'tax_include' => true,
            'tax' => '0',
            'prepaid_period' => 'Mensual',
            'rates_to_change' =>null,
            'transaction_category' =>null,
            'Available_in_self_registration' =>false,
            'Bandwidth' =>null,
            'Priority' =>'1'
        ]);
        $custom->billings()->attach(
            [1,2,3]
        );
        $custom->plan_custom_client()->attach(
            [1]
        );

        $custom = Custom::create([
            'title' => 'Gpon_TV',
            'service_name' => 'TV',
            'update_description' => false,
            'price' => '199',
            'update_service' => false,
            'partners' => null,
            'tax_include' => true,
            'tax' => '16',
            'prepaid_period' => 'Mensual',
            'rates_to_change' =>null,
            'transaction_category' =>null,
            'Available_in_self_registration' =>false,
            'Bandwidth' =>'20000',
            'Priority' =>'1'
        ]);
        $custom->billings()->attach(
            [1,2,3]
        );

        $custom = Custom::create([
            'title' => 'MovieNet',
            'service_name' => 'MovieNet',
            'update_description' => false,
            'price' => '125',
            'update_service' => false,
            'partners' => null,
            'tax_include' => true,
            'tax' => '0',
            'prepaid_period' => null,
            'rates_to_change' =>null,
            'transaction_category' =>'Servicio',
            'Available_in_self_registration' =>true,
            'Bandwidth' =>'5',
            'Priority' =>'1'
        ]);
        $custom->billings()->attach(
         [1,2,3]
         );

        $custom = Custom::create([
            'title' => 'modem',
            'service_name' => 'Modem',
            'update_description' => false,
            'price' => '99',
            'update_service' => false,
            'partners' => null,
            'tax_include' => true,
            'tax' => '16',
            'prepaid_period' => 'Mensual',
            'rates_to_change' =>null,
            'transaction_category' =>'Servicio',
            'Available_in_self_registration' =>true,
            'Bandwidth' =>'5',
            'Priority' =>'1'
        ]);
        $custom->billings()->attach(
        [1,2,3]
          );

    }
}
