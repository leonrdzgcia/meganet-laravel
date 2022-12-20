<?php

namespace App\Http\Controllers;

use App\Http\Traits\RouterConnection;
use App\Jobs\Client\BillingService\RectifyBalanceAndCreateTransactionInClientServiceFirstTime;
use App\Jobs\Client\ClientServiceChargedJob;
use App\Jobs\CreateClientInRouterJob;
use App\Jobs\CreateClientWithServiceJob;
use App\Jobs\Mikrotik\CheckMikrotikConection;
use App\Jobs\Mikrotik\MikrotikCreateAddressList;
use App\Jobs\Client\BillingService\RectifyBalanceAndCreateTransactionInClientService;
use App\Jobs\Mikrotik\MikrotikRemoveClientServiceFromAddressList;
use App\Jobs\PupulateUserColumnsDatatableModuleDefaultsJob;
use App\Models\Balance;
use App\Models\Bundle;
use App\Models\Client;
use App\Models\ClientBundleService;
use App\Models\ClientGracePeriod;
use App\Models\ClientInternetService;
use App\Models\ClientInvoice;
use App\Models\ClientInvoiceService;
use App\Models\ClientVozService;
use App\Models\ClientMainInformation;
use App\Models\FieldModule;
use App\Models\Internet;
use App\Models\MethodOfPayment;
use App\Models\Mikrotik;
use App\Models\MikrotikClientPpoe;
use App\Models\Module;
use App\Models\NetworkIp;
use App\Models\Network;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\PaymentPromise;
use App\Models\Router;
use App\Models\ServiceInAddressList;
use App\Models\Ticket;
use App\Models\TypeBilling;
use App\Models\User;
use App\Models\ClientCustomService;
use App\Notifications\TicketOpen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use IPv4\SubnetCalculator;
use Nette\Utils\DateTime;
use PEAR2\Net\RouterOS\SocketException;
use App\Models\ClientUser;
use Spatie\Permission\Models\Permission;
use function GuzzleHttp\Promise\queue;
use App\Jobs\Client\DeployServiceFirstTime;
use App\Jobs\Client\BillingService\ClientSendReminderNotificationJob;
use App\Models\Transaction;
use Mockery\Undefined;
use Symfony\Component\VarDumper\Server\DumpServer;
use App\Models\MikrotikItemToExcecuteAction;
use App\Models\MikrotikTariffMainTail;
use App\Models\MikrotikTariffTargetTail;
use App\Jobs\DeletedClientInRouterJob;
use App\Models\ClientPaymentService;
use App\Http\Repository\ClientRepository;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;


class TestScriptController extends Controller
{
    use RouterConnection;

    private $clientRepository;

    public function script(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;

        $client = Client::find(1);
       // $clientday = $client->billing_configuration()->//first()->billing_date  <= \Carbon\Carbon::now()->format('d');
        $clientServices = $this->clientRepository->getServiceActive($client->id);

        $services = ['bundle_service','internet_service', 'voz_service','custom_service'];
        foreach ($services as $service)  {
            foreach ($clientServices->$service as $clientService ){
              $bundle = $clientService->service_internet()->get();
                dump($bundle);
            }
        }


     //        $balance = $client->balance()->first();
//        dump($balance->amount);

//        $this->clientRepository = $clientRepository;

//        $clientBundleServices = ClientBundleService::with('client.balance', 'client.client_main_information', 'client.billing_configuration', 'service_internet.service_in_address_list', 'client.payments.payment_promise')
//            ->leftJoin('clients', 'client_bundle_services.client_id', '=', 'clients.id')
//            ->leftJoin('billing_configurations', 'clients.id', '=', 'billing_configurations.client_id')
//            ->leftJoin('client_internet_services', 'client_bundle_services.id', '=', 'client_internet_services.client_bundle_service_id')
//            ->select('client_bundle_services.*')
//            ->bundleActive()
//            ->bundleCharged()
//            ->bundleDeployed()
//            ->whereDoesntHave('service_internet.service_in_address_list')
//            ->where(function ($query) {
//                //  Daily
//                $query->where(function ($query) {
//                    $query->isClientTypeOfBilling(TypeBilling::TYPE_OF_BILLING_PREPAID_DAILY)
//                        ->getClientDontHaveClientPaymentToday()
//                        ->getClientDontHaveTransactionToday();
//                })
//                    //Custom
//                    ->orWhere(function ($query) {
//                        $query->isClientTypeOfBilling(TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM)
//                            ->getServicePaymentAMonthAgo();
//                    })
//                    //Recurrent
//                    ->orWhere(function ($query) {
//                        $query->isClientTypeOfBilling(TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT)
//                            ->getClientActiveBillingToday()
//                            ->getClientDontHaveTransactionToday()
//                            ->getClientDontHaveTransactionAMonthAgo()
//                            ->where(function ($query) {
//                                $query->whereNull('billing_configurations.grace_period')
//                                    ->orWhereDoesntHave('client.client_grace_period')
//                                    ->getIsGracePeriodExpired();
//                            });
//                    });
//            })
//            ->orWhere(function ($query) {
//                $query->getIsClientEstado("Bloqueado")
//                    ->whereHas('client.balance', function ($query) {
//                        $query->whereRaw('amount >= client_bundle_services.price');
//                    })
//                    ->whereHas('service_internet.service_in_address_list');
//            })
//            ->orWhere(function ($query) {
//                $query->isClientTypeOfBilling(TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT);
//                $query->whereHas('client.payments', function ($query) {
//                    $query->where('enabled_payment_promise', 1)
//                        ->whereHas('payment_promise', function ($query) {
//                            $query->whereDate('court_date', '=', \Carbon\Carbon::now()->format('Y-m-d'));
//                        });
//                });
//            })
//            ->get();
//
//        dump($clientBundleServices);

    }


}
