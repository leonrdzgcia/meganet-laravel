<?php

namespace Database\Seeders;

use App\Http\Controllers\Module\Network\Ipv4CalculatorController;
use App\Jobs\CreateNetWorkIpRowsJob;
use Illuminate\Database\Seeder;
use App\Models\Network;

class NetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = Network::create([
            'network' => '192.168.1.0',
            'bm' => '28',
            'used' => '1',
            'title' => 'SECTOR1',
            'network_type' => 'EndNet',
            'network_category' => 'Test',
            'comment' => 'SECTOR1',
            'location_id' => '1',
            'type_of_use' => 'Estatico',
            'parent_id' => '1',
        ]);
        $Ipv4Calculator = new Ipv4CalculatorController();
        $ips = $Ipv4Calculator->createIpInNetwork('192.168.1.0', '28');

        //TODO agregar pasar a segundo plano.
        CreateNetWorkIpRowsJob::dispatchAfterResponse($model,$ips);
    }
}
