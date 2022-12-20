<?php

namespace App\Observers;

use App\Models\MikrotikConfig;
use App\Models\MikrotikItemToExcecuteAction;
use Illuminate\Support\Facades\Log;

class MikrotikConfigObserver
{
    /**
     * Handle the MikrotikConfig "updated" event.
     *
     * @param  \App\Models\MikrotikConfig  $mikrotikConfig
     * @return void
     */
    public function updating(MikrotikConfig $mikrotikConfig)
    {
       $origin = MikrotikConfig::find($mikrotikConfig->id);
        MikrotikItemToExcecuteAction::create([
            'Model' => 'MikrotikConfig',
            'place' => '/ip/firewall/filters/',
            'origin' => $origin,
            'flag' => 'name',
            'value' => $mikrotikConfig,
            'action' => 'update',
        ]);
    }

    /**
     * Handle the MikrotikConfig "deleted" event.
     *
     * @param  \App\Models\MikrotikConfig  $mikrotikConfig
     * @return void
     */
    public function deleted(MikrotikConfig $mikrotikConfig)
    {
        //
    }

    /**
     * Handle the MikrotikConfig "restored" event.
     *
     * @param  \App\Models\MikrotikConfig  $mikrotikConfig
     * @return void
     */
    public function restored(MikrotikConfig $mikrotikConfig)
    {
        //
    }

    /**
     * Handle the MikrotikConfig "force deleted" event.
     *
     * @param  \App\Models\MikrotikConfig  $mikrotikConfig
     * @return void
     */
    public function forceDeleted(MikrotikConfig $mikrotikConfig)
    {
        //
    }
}
