<?php

namespace Database\Seeders;

use App\Models\DealCrm;
use Illuminate\Database\Seeder;

class DealCrmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //  Crm::factory(3)->create();
        DealCrm::create([
            'status'=>'won',
            'name'=>'Pepe',
            'total'=>'0',
            'created'=>'2021-05-27 14:05:58',
            'expected_close'=>'2021-05-27 14:05:58',
            'type'=>'algo',
            'owner'=>'1',
            'lead_id'=>'1',
            'last_update_at'=>'2021-05-27 14:05:58',
            'last_update_by'=>'2021-05-27 14:05:58',
            'source'=>'algun lugar',
            'connected_quote_id'=>'1',
            'customers_deal'=>'alguien',
            ]);

        DealCrm::create([
            'status'=>'open',
            'name'=>'Antonio',
            'total'=>'10',
            'created'=>'2021-05-27 14:05:58',
            'expected_close'=>'2021-05-27 14:05:58',
            'type'=>'algo1',
            'owner'=>'2',
            'lead_id'=>'2',
            'last_update_at'=>'2021-05-27 14:05:58',
            'last_update_by'=>'2021-05-27 14:05:58',
            'source'=>'algun lugar',
            'connected_quote_id'=>'1',
            'customers_deal'=>'alguien mas',
        ]);

    }
}
