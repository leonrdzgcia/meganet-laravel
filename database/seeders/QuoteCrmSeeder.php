<?php

namespace Database\Seeders;

use App\Models\QuoteCrm;
use Illuminate\Database\Seeder;

class QuoteCrmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //  Crm::factory(3)->create();
        QuoteCrm::create([
            'status'=>'nuevo',
            'name'=>'Pepe',
            'date'=>'2021-05-27 14:05:58',
            'total'=>'0',
            'valid_till'=>'2021-05-27 14:05:58',
            'lead_id'=>'1',
            'last_update'=>'2021-05-27 14:05:58',
            'date_of_decision'=>'2021-05-27 14:05:58',
            'invoice_id'=>'1',
            'request_id'=>'1',
            'is_sent'=>true,
            'note'=>'Esto es una nota completa ',
            'memo'=>'Esto es lo que estaa en el memo',
            'customers_quote'=>'10',
            'connected_deal_id'=>'1',
            ]);

        QuoteCrm::create([
            'status'=>'sent',
            'name'=>'Cuco',
            'date'=>'2021-05-27 14:05:58',
            'total'=>'1',
            'valid_till'=>'2021-05-27 14:05:58',
            'lead_id'=>'2',
            'last_update'=>'2021-05-27 14:05:58',
            'date_of_decision'=>'2021-05-27 14:05:58',
            'invoice_id'=>'2',
            'request_id'=>'2',
            'is_sent'=>true,
            'note'=>'Esto es una nota completa ',
            'memo'=>'Esto es lo que estaa en el memo',
            'customers_quote'=>'20',
            'connected_deal_id'=>'2',
        ]);

    }
}
