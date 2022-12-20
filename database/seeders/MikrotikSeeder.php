<?php

namespace Database\Seeders;

use App\Models\Mikrotik;
use Illuminate\Database\Seeder;

class MikrotikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mikrotik::create([
            'router_id' => 1,
            'active' => true,
            'login_api' => "admin",
            'password_api' => "okk",
            'port_api' => "8730",
        ]);
    }
}
