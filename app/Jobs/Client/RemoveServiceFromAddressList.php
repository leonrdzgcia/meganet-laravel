<?php

namespace App\Jobs\Client;

use App\Jobs\Mikrotik\MikrotikRemoveClientServiceFromAddressList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveServiceFromAddressList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function handle()
    {
       if ($this->clientService){
           MikrotikRemoveClientServiceFromAddressList::dispatchAfterResponse($this->clientService);
       }
    }
}
