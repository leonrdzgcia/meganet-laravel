<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ClientInternetService;
use App\Models\Router;
use App\Models\MikrotikItemToExcecuteAction;
use App\Http\Traits\RouterConnection;
use Illuminate\Support\Facades\Log;

class UpdateClientWithServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,RouterConnection;

    protected $clientInternetService;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ClientInternetService $clientInternetService)
    {
        $this->clientInternetService = $clientInternetService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      MikrotikItemToExcecuteAction::create([
        'Model' => 'ClientInternetService',
        'place' => '/interface pppoe-client',
        'flag' => 'name',
        'value' => $this->clientInternetService,
        'action' => 'update',
    ]);
    }


 }



