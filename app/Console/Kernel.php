<?php

namespace App\Console;

use Egulias\EmailValidator\Exception\CommaInDomain;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Psy\Command\Command;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\BillingService::class,
        Commands\ClientServiceDeployedCommand::class,
        Commands\ClientServiceToChargeIfDeployedCommand::class,
        Commands\SuspendServiceRecurrentIfIsBillingExpirationCommand::class,
        Commands\ClientLastActivity::class,
        Commands\SendReminderEmailOrSmsForRecurringBilling::class,
        Commands\DeployClientsInAddressListCommand::class,
        Commands\CreatePoolInMikrotikCommand::class,
        Commands\ActionPoolInMikrotikCommand::class,
        Commands\RemoveClientInMikrotikIfDoesntHaveClientServiceCommand::class,
        Commands\RemoveServiceFromAddressListCommand::class,
        Commands\AddInvoiceDefaulterToClientsCommand::class,
        Commands\MakeTheClientInactiveCommand::class,
        Commands\MikrotikBackupCommand::class,
        Commands\CreateClientInvoiceCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('mikrotikBackup:process')->dailyAt('23:59');//->withoutOverlapping();
        $schedule->command('billingservice:process')->dailyAt('00:00');//->withoutOverlapping();
        $schedule->command('createinvoice:process')->dailyAt('00:02');
        $schedule->command('clientservicedeployed:process')->everyMinute();//->withoutOverlapping();
        $schedule->command('clientservicetochargeifdeployed:process')->everyMinute();//->withoutOverlapping();
        $schedule->command('suspendservicerecurrentifisbillingexpiration:process')->dailyAt('00:01');
        $schedule->command('addinvoicedefaultertoclients:process')->dailyAt('00:05');//->withoutOverlapping();
        $schedule->command('createpoolinmikrotik:process')->everyMinute();
        $schedule->command('removeinmikrotikwithoutclientservice:process')->everyMinute();
        $schedule->command('actionpoolinmikrotik:process')->everyMinute();
        $schedule->command('deployclientsinaddresslist:process')->everyMinute();
        $schedule->command('removeservicefromaddresslist:process')->everyMinute();
        $schedule->command('maketheclientinactive:process')->everyMinute();



//        $schedule->command('billingservice:process')->everyMinute()->withoutOverlapping();
//        $schedule->command('clientservicedeployed:process')->everyMinute()->withoutOverlapping();
//        $schedule->command('clientservicetochargeifdeployed:process')->everyMinute()->withoutOverlapping();
//        $schedule->command('suspendservicerecurrentifisbillingexpiration:process')->dailyAt('00:00');
//        $schedule->command('removeservicefromaddresslist:process')->everyMinute()->withoutOverlapping();
//        $schedule->command('process:client_last_activity')->everyMinute()->withoutOverlapping(15);
//        $schedule->command('deployclientsinaddresslist:process')->everyMinute()->withoutOverlapping();
//        $schedule->command('createpoolinmikrotik:process')->everyMinute();
//        $schedule->command('actionpoolinmikrotik:process')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
