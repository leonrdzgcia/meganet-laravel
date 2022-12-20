<?php

namespace App\Jobs\Client;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeployServiceFirstTime implements ShouldQueue
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
        $this->clientService->update(['deployed' => true, 'estado' => 'Activado']);
        $this->clientService->client->client_main_information->update(['estado' => 'Activo']);

        if ($this->clientService->bundle_id) {
            foreach ($this->clientService->service_internet()->get() as $service){
                $service->update(['deployed' => 1, 'estado' => 'Activado']);
            }
        }
    }
}
