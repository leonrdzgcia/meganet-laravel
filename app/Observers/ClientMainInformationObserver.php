<?php

namespace App\Observers;

use App\Jobs\PupulateUserColumnsDatatableModuleDefaultsJob;
use App\Models\ClientMainInformation;


class ClientMainInformationObserver
{
    public function created(ClientMainInformation $clientMainInformation)
    {
    //
    }

    public function updating(ClientMainInformation $clientMainInformation)
    {
//
    }

    public function deleting(ClientMainInformation $clientMainInformation)
    {
//
    }

}
