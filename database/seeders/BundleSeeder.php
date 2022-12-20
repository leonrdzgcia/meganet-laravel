<?php

namespace Database\Seeders;

use App\Models\Bundle;
use Illuminate\Database\Seeder;

class BundleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bundle = Bundle::create([
            'title' => "10MB+TEL",
            'emit_invoice' => false,
            'service_description' => "10MB+TEL",
            'price' => "389",
            'tax_include' => true,
            'tax' => "16",
            'activation_fee' => "299",
            'get_activation_fee_when' => "Al crear el servicio",
            'contract_duration' => "18",
            'automatic_renewal' => true,
            'auto_reactivate'=> true,
            'cancellation_fee'=> "299",
            'prior_cancellation_fee'=> "299",
            'discount_period'=> "0",
            'discount_value'=> "0"
        ]);
        $bundle->billings()->attach(
            [1,2,3]
        );
        $bundle->planes_internet()->attach([1,2], ['cant' => 1]);
        $bundle->planes_voz()->attach([1,], ['cant' => 1]);
        $bundle->planes_custom()->attach([1,3], ['cant' => 1]);

        Bundle::create([
            'title' => "30+TEL+MOVIENET",
            'emit_invoice' => false,
            'service_description' => "30+TEL+MOVIENET",
            'price' => "520",
            'tax_include' => true,
            'tax' => "16",
            'activation_fee' => "0",
            'get_activation_fee_when' => "En facturación del primer servicio",
            'contract_duration' => "0",
            'automatic_renewal' => true,
            'auto_reactivate'=> true,
            'cancellation_fee'=> "0",
            'prior_cancellation_fee'=> "0",
            'discount_period'=> "0",
            'discount_value'=> "0"
        ]);
        Bundle::create([
            'title' => "20+TEL+MOVIENET",
            'emit_invoice' => false,
            'service_description' => "20+TEL+MOVIENET",
            'price' => "420",
            'tax_include' => true,
            'tax' => "16",
            'activation_fee' => "0",
            'get_activation_fee_when' => "En facturación del primer servicio",
            'contract_duration' => "6",
            'automatic_renewal' => true,
            'auto_reactivate'=> true,
            'cancellation_fee'=> "0",
            'prior_cancellation_fee'=> "0",
            'discount_period'=> "0",
            'discount_value'=> "0"
        ]);
        Bundle::create([
            'title' => "5MB+TEL",
            'emit_invoice' => false,
            'service_description' => "5MB+TEL",
            'price' => "309",
            'tax_include' => true,
            'tax' => "16",
            'activation_fee' => "0",
            'get_activation_fee_when' => "Al crear el servicio",
            'contract_duration' => "8",
            'automatic_renewal' => true,
            'auto_reactivate'=> true,
            'cancellation_fee'=> "299",
            'prior_cancellation_fee'=> "299",
            'discount_period'=> "0",
            'discount_value'=> "0"
        ]);
        Bundle::create([
            'title' => "50+TEL+MOVIENET",
            'emit_invoice' => false,
            'service_description' => "50+TEL+MOVIENET",
            'price' => "679",
            'tax_include' => true,
            'tax' => "16",
            'activation_fee' => "0",
            'get_activation_fee_when' => "En facturación del primer servicio",
            'contract_duration' => "0",
            'automatic_renewal' => false,
            'auto_reactivate'=> false,
            'cancellation_fee'=> "0",
            'prior_cancellation_fee'=> "0",
            'discount_period'=> "0",
            'discount_value'=> "0"
        ]);
        Bundle::create([
            'title' => "Internet Puro 20MB",
            'emit_invoice' => false,
            'service_description' => "Internet Puro 20MB",
            'price' => "349",
            'tax_include' => true,
            'tax' => "16",
            'activation_fee' => "299",
            'get_activation_fee_when' => "En facturación del primer servicio",
            'contract_duration' => "0",
            'automatic_renewal' => true,
            'auto_reactivate'=> true,
            'cancellation_fee'=> "299",
            'prior_cancellation_fee'=> "299",
            'discount_period'=> "0",
            'discount_value'=> "0"
        ]);
        Bundle::create([
            'title' => "20MB+TEL+EMPLEADOS",
            'emit_invoice' => false,
            'service_description' => "20MB+TEL+EMPLEADOS",
            'price' => "270",
            'tax_include' => true,
            'tax' => "16",
            'activation_fee' => "0",
            'get_activation_fee_when' => "En facturación del primer servicio",
            'contract_duration' => "0",
            'automatic_renewal' => false,
            'auto_reactivate'=> false,
            'cancellation_fee'=> "0",
            'prior_cancellation_fee'=> "0",
            'discount_period'=> "0",
            'discount_value'=> "0"
        ]);
        Bundle::create([
            'title' => "50MB+TEL+EMPLEADOS",
            'emit_invoice' => false,
            'service_description' => "250MB+TEL+EMPLEADOS",
            'price' => "420",
            'tax_include' => true,
            'tax' => "16",
            'activation_fee' => "0",
            'get_activation_fee_when' => "En facturación del primer servicio",
            'contract_duration' => "0",
            'automatic_renewal' => false,
            'auto_reactivate'=> false,
            'cancellation_fee'=> "0",
            'prior_cancellation_fee'=> "0",
            'discount_period'=> "0",
            'discount_value'=> "0"
        ]);
        Bundle::create([
            'title' => "preferentes",
            'emit_invoice' => false,
            'service_description' => "preferentes",
            'price' => "500",
            'tax_include' => true,
            'tax' => "16",
            'activation_fee' => "299",
            'get_activation_fee_when' => "En facturación del primer servicio",
            'contract_duration' => "0",
            'automatic_renewal' => true,
            'auto_reactivate'=> true,
            'cancellation_fee'=> "299",
            'prior_cancellation_fee'=> "299",
            'discount_period'=> "0",
            'discount_value'=> "0"
        ]);
    }
}
