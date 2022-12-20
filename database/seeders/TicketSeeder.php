<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //  Ticket::factory(3)->create();
      $input =  Ticket::create([
            'topic' => 'Este es el tema',
            'customer_lead' => 1,
            'client_id' => 1
        ]);

      $input->ticket_thread()->create([
          'edited_by' => 1,
          'client_id' => 1,
          'message' => 'Este es la respuesta del ticket',
      ]);

    }
}
