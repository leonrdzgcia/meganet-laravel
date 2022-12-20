<?php

namespace App\Console\Commands;

use App\Jobs\Client\BillingService\ClientSendReminderNotificationJob;
use App\Models\ClientInternetService;
use App\Models\TypeBilling;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendReminderEmailOrSmsForRecurringBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendreminderemailorsmsforrecurringbilling:proccess';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $clientReminders = Client::with('client_main_information', 'billing_configuration', 'reminder_configuration')
            ->leftJoin('billing_configurations', 'clients.id', '=', 'billing_configurations.client_id')
            ->leftJoin('reminders_configurations', 'clients.id', '=', 'reminders_configurations.client_id')
            ->where(function ($query) {
                $query->whereHas('client_main_information', function ($query) {
                    $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT);
                });
            })   
            ->whereHas('reminder_configuration', function ($query) {
                $query->where('billing_activated', 1)
                ->where('activate_reminders',1)
                ->where('type_of_message','Mail + SMS')
                ->orWhere('type_of_message','Mail');       
            })
            ->whereHas('billing_configuration', function ($query) {
                $query->where('billing_activated', 1)
                    ->where(function ($query) {
                        $query->whereRaw('billing_date = DAY(DATE_ADD(now(), INTERVAL reminders_configurations.reminder_1_days DAY))')
                            ->orWhereRaw('billing_date = DAY(DATE_ADD(now(), INTERVAL reminders_configurations.reminder_2_days DAY))')
                            ->orWhereRaw('billing_date = DAY(DATE_ADD(now(), INTERVAL reminders_configurations.reminder_3_days DAY))');
                    })
                ;
            })
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereHas('internet_service', function ($query) {
                        $query->where('estado', 'Activado');
                        $query->where('deployed', '1');
                    });
                })->orWhere(function ($query) {
                    $query->whereHas('bundle_service', function ($query) {
                        $query->where('estado', 'Activado');
                        $query->where('deployed', '1');
                    });
                })->orWhere(function ($query) {
                    $query->whereHas('voz_service', function ($query) {
                        $query->where('estado', 'Activado');
                        $query->where('deployed', '1');
                    });
                })->orWhere(function ($query) {
                    $query->whereHas('custom_service', function ($query) {
                        $query->where('estado', 'Activado');
                        $query->where('deployed', '1');
                    });
                });
            })
            ->get();
       foreach ($clientReminders as $clientReminder) {
           ClientSendReminderNotificationJob::dispatch($clientReminder);
       }
    }
}
