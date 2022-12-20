<?php

namespace App\Observers;

use App\Models\CrmMainInformation;

class CrmMainInformationObserver
{
    public function creating(CrmMainInformation $crmMainInformation)
    {
        $crmMainInformation->high_date = date('Y-m-d');
    }
}
