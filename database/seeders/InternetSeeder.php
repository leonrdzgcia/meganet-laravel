<?php

namespace Database\Seeders;

use App\Models\Internet;
use App\Models\TypeBilling;
use Illuminate\Database\Seeder;

class InternetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Internet::factory(5)->create();
        $internet = Internet::create([
            'title' => "Basico_20MB",
            'update_description' => false,
            'service_name' => "Basico_20MB",
            'update_service' => false,
            'price' => "349",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "20000",
            'upload_speed' => "5000",
            'guaranteed_speed_limit' => "11",
            'priority' => "Normal",
            'aggregation' => "8",
            'burst' => "100",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet->partners()->attach(
            [1,2]
        );

        $internet = Internet::create([
            'title' => "Social_10Mb",
            'update_description' => false,
            'service_name' => "Social_10Mb",
            'update_service' => false,
            'price' => "309",
            'tax_include' => true,
            'tax' => "0",
            'download_speed' => "10000",
            'upload_speed' => "2000",
            'guaranteed_speed_limit' => "100",
            'priority' => "Normal",
            'aggregation' => "10",
            'burst' => "200",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet->partners()->attach(
            [1,2]
        );

        $internet = Internet::create([
            'title' => "administracion",
            'update_description' => false,
            'service_name' => "administracion",
            'update_service' => false,
            'price' => "0",
            'tax_include' => false,
            'tax' => "16",
            'download_speed' => "10000",
            'upload_speed' => "1000",
            'guaranteed_speed_limit' => "5",
            'priority' => "Normal",
            'aggregation' => "50",
            'burst' => "200",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet->plan_internet_client()->attach(
            [1,2]
        );

        $internet = Internet::create([
            'title' => "Meganet_recurrente 20",
            'update_description' => false,
            'service_name' => "Meganet_recurrente 20",
            'update_service' => false,
            'price' => "349",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "20000",
            'upload_speed' => "5000",
            'guaranteed_speed_limit' => "5",
            'priority' => "Normal",
            'aggregation' => "10",
            'burst' => "200",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "25MB 399",
            'update_description' => false,
            'service_name' => "25MB 399",
            'update_service' => false,
            'price' => "399",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "25000",
            'upload_speed' => "4000",
            'guaranteed_speed_limit' => "2",
            'priority' => "Normal",
            'aggregation' => "20",
            'burst' => "200",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "Estudiante_30 MB",
            'update_description' => false,
            'service_name' => "Estudiante_30 MB",
            'update_service' => false,
            'price' => "499",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "50000",
            'upload_speed' => "3000",
            'guaranteed_speed_limit' => "2",
            'priority' => "Normal",
            'aggregation' => "8",
            'burst' => "200",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false

        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "3 MBPS",
            'update_description' => false,
            'service_name' => "3 MBPS",
            'update_service' => false,
            'price' => "249",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "1500",
            'upload_speed' => "1500",
            'guaranteed_speed_limit' => "100",
            'priority' => "Normal",
            'aggregation' => "20",
            'burst' => "0",
            'burt_umbral' => "0",
            'burt_time' => "0",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "5 Mb 15 Dias",
            'update_description' => false,
            'service_name' => "5 Mb 15 Dias",
            'update_service' => false,
            'price' => "238",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "2000",
            'upload_speed' => "200",
            'guaranteed_speed_limit' => "10",
            'priority' => "Normal",
            'aggregation' => "20",
            'burst' => "200",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "Conectate_5Mb",
            'update_description' => false,
            'service_name' => "Conectate_5Mb",
            'update_service' => false,
            'price' => "209",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "3000",
            'upload_speed' => "1000",
            'guaranteed_speed_limit' => "10",
            'priority' => "Normal",
            'aggregation' => "12",
            'burst' => "500",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "Plus 50 mbps",
            'update_description' => false,
            'service_name' => "Plus 50 mbps",
            'update_service' => false,
            'price' => "599",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "50000",
            'upload_speed' => "10000",
            'guaranteed_speed_limit' => "2",
            'priority' => "Alta",
            'aggregation' => "20",
            'burst' => "800",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "Meganet_40Mb",
            'update_description' => false,
            'service_name' => "Meganet_40Mb",
            'update_service' => false,
            'price' => "549",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "40000",
            'upload_speed' => "80000",
            'guaranteed_speed_limit' => "10",
            'priority' => "Normal",
            'aggregation' => "20",
            'burst' => "800",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "EMPLEADOS",
            'update_description' => false,
            'service_name' => "EMPLEADOS",
            'update_service' => false,
            'price' => "200",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "15000",
            'upload_speed' => "10000",
            'guaranteed_speed_limit' => "10",
            'priority' => "Alta",
            'aggregation' => "15",
            'burst' => "100",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "BARATO",
            'update_description' => false,
            'service_name' => "BARATO",
            'update_service' => false,
            'price' => "100",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "2000",
            'upload_speed' => "1000",
            'guaranteed_speed_limit' => "10",
            'priority' => "Normal",
            'aggregation' => "20",
            'burst' => "0",
            'burt_umbral' => "0",
            'burt_time' => "0",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "empleados 30mb",
            'update_description' => false,
            'service_name' => "empleados 30mb",
            'update_service' => false,
            'price' => "300",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "30000",
            'upload_speed' => "10000",
            'guaranteed_speed_limit' => "10",
            'priority' => "Normal",
            'aggregation' => "30",
            'burst' => "100",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "voz",
            'update_description' => false,
            'service_name' => "voz",
            'update_service' => false,
            'price' => "0",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "200",
            'upload_speed' => "200",
            'guaranteed_speed_limit' => "10",
            'priority' => "Normal",
            'aggregation' => "1",
            'burst' => "100",
            'burt_umbral' => "100",
            'burt_time' => "800",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "20 MB + TEL EMPLEADOS",
            'update_description' => false,
            'service_name' => "20 MB + TEL EMPLEADOS",
            'update_service' => false,
            'price' => "270",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "20000",
            'upload_speed' => "5000",
            'guaranteed_speed_limit' => "10",
            'priority' => "Normal",
            'aggregation' => "1",
            'burst' => "0",
            'burt_umbral' => "0",
            'burt_time' => "0",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
        $internet = Internet::create([
            'title' => "50MB + TEL EMPLEADOS",
            'update_description' => false,
            'service_name' => "50MB + TEL EMPLEADOS",
            'update_service' => false,
            'price' => "420",
            'tax_include' => true,
            'tax' => "16",
            'download_speed' => "50000",
            'upload_speed' => "10000",
            'guaranteed_speed_limit' => "10",
            'priority' => "Normal",
            'aggregation' => "1",
            'burst' => "0",
            'burt_umbral' => "0",
            'burt_time' => "0",
            'transaction_category' => null,
            'prepaid_period' => "Mensual",
            'available_when_register_by_social_network' => false
        ]);
        $internet->billings()->attach(
            [1,2,3]
        );
    }
}
