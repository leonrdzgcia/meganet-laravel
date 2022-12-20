<?php

namespace Database\Seeders;

use App\Models\Router;
use Illuminate\Database\Seeder;

class RouterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $router = Router::create([
            'title' => "admin2",
            'type_of_nas' => "Mikrotik",
            'vendor_model' => "CR 1009",
            'physical_address' => "SANTA INES",
            'ip_host' => "149.14.76.99",
            'nas_ip' => "149.14.76.99",
            'secret_radius' => "Meganet",
            'authorization_accounting' => "PPP(Secrets)/API Acounting",
            'status' => "Pendiente",
        ]);

        $router->mikrotik()->create([
            'active' => true,
            'login_api' => 'admin',
            'password_api' => 'inving9378',
            'port_api' => '8771',
        ]);
    }
}
