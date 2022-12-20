<?php

namespace App\Jobs\Client\Invoice;

use App\Models\ClientInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Repository\ClientRepository;
use Illuminate\Support\Facades\Log;

class ClientInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clientRepository;
    protected $clientService;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($clientService)
    {
      $this->clientService = $clientService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ClientRepository $clientRepository)
    {
      $clientRepository->addInvoiceService($this->clientService);
    }
}
