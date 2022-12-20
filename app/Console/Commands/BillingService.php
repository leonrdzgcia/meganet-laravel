<?php

namespace App\Console\Commands;

use App\Http\Repository\ClientRepository;
use App\Jobs\Client\BillingService\RectifyBalanceAndCreateTransactionInClientService;
use App\Models\Client;
use App\Models\ClientBundleService;
use App\Models\ClientInternetService;
use App\Models\ClientVozService;
use App\Models\ClientCustomService;
use App\Models\TypeBilling;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillingService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billingservice:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realiza la logica de pago del servicio dependiendo del tipo de servicio
    - Si el servicio esta en estado "Activado"
    - Si esta desplegado
    - Si no esta en address list
    -- Si es de tipo Diario
     - No debe tener un pago de servicio para el dia de hoy
     - No debe tener una transaccion para el dia de hoy
    --Si es tipo Custom
     - Debe tener un pago de servicio del mes pasado
    --Si es de tipo Recurrente
     - Que el dia de facturacion sea el dia de hoy
     - Que no tenga transacciones para el dia de hoy
     - Que tenga transaccion en el periodo especificado anteriormente
     - Donde el periodo de gracia sea nulo o No tenga periodo de gracia, o se halla cumplido el periodo de gracia

    ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;

        $clientInternetServices = ClientInternetService::with('client.balance', 'client.client_main_information', 'network_ip.network', 'router.mikrotik', 'internet', 'client.billing_configuration', 'service_in_address_list', 'client.payments.payment_promise')
            ->leftJoin('clients', 'client_internet_services.client_id', '=', 'clients.id')
            ->leftJoin('billing_configurations', 'clients.id', '=', 'billing_configurations.client_id')
            ->select('client_internet_services.*')
            ->where('estado', '=', 'Activado')
            ->where('charged', '=', 1)
            ->where('deployed', '=', 1)
            ->whereDoesntHave('service_in_address_list')
            ->whereNull('client_bundle_service_id')
            ->where(function ($query) {
                //Daily
                $query->where(function ($query) {
                    $query->whereHas('client.client_main_information', function ($query) {
                        $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_DAILY);
                    })
                        ->whereDoesntHave('client_payment_service', function ($query) {
                            $query->whereDate('created_at', Carbon::now()->toDateString());
                        })
                        ->whereDoesntHave('transactions', function ($query) {
                            $query->whereDate('created_at', Carbon::now()->toDateString());
                        });
                })
                    //Custom
                    ->orWhere(function ($query) {
                        $query->whereHas('client.client_main_information', function ($query) {
                            $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM);
                        })->whereHas('client_payment_service', function ($query) {
                            $query->whereRaw('DATE(created_at) <= DATE(DATE_SUB(now(),INTERVAL 1 MONTH))');
                        });
                    })
                    //Recurrent
                    ->orWhere(function ($query) {
                        $query
                            ->whereHas('client.client_main_information', function ($query) {
                                $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT);
                            })
                            ->whereHas('client.billing_configuration', function ($query) {
                                $query->where('billing_activated', 1)
                                    ->where('billing_date', (integer)Carbon::now()->format('d'));
                            })
                            ->whereDoesntHave('transactions', function ($query) {
                                $query->where(DB::Raw('DATE(created_at)'), Carbon::now()->toDateString());
                            })
                            ->whereHas('transactions', function ($query) {
                                $query->whereRaw('DATE(created_at) = DATE(DATE_SUB(now(), INTERVAL billing_configurations.period MONTH))');
                            })
                            ->where(function ($query) {
                                $query->whereNull('billing_configurations.grace_period')
                                    ->orWhereDoesntHave('client_grace_period')
                                    ->orWhereHas('client_grace_period', function ($query) {
                                        $query->whereRaw('DATE(created_at) >= DATE(DATE_SUB(now(), INTERVAL billing_configurations.grace_period Day))');
                                    });
                            });
                    });
            })
            ->orWhere(function ($query) {
                $query->whereHas('client.client_main_information', function ($query) {
                    $query->where('estado', '=', 'Bloqueado');
                })
                    ->whereHas('client.balance', function ($query) {
                        $query->whereRaw('amount >= price');
                    })
                    ->whereHas('service_in_address_list')
                    ->whereNull('client_bundle_service_id');
            })
            ->orWhere(function ($query) {
                $query->isClientTypeOfBilling(TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM);
                $query->whereHas('client.payments', function ($query) {
                    $query->where('enabled_payment_promise', 1)
                        ->whereHas('payment_promise', function ($query) {
                            $query->whereDate('court_date', '=', \Carbon\Carbon::now()->format('Y-m-d'));
                        });
                });
            })
            ->get();

        $clientVozServices = ClientVozService::with('client.balance', 'client.client_main_information', 'voise', 'client.billing_configuration')
            ->leftJoin('clients', 'client_voz_services.client_id', '=', 'clients.id')
            ->leftJoin('billing_configurations', 'clients.id', '=', 'billing_configurations.client_id')
            ->select('client_voz_services.*')
            ->where('estado', '=', 'Activado')
            ->where('charged', '=', 1)
            ->where('deployed', '=', 1)
            ->whereNull('client_bundle_service_id')
            ->where(function ($query) {
                //Daily
                $query->where(function ($query) {
                    $query->whereHas('client.client_main_information', function ($query) {
                        $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_DAILY);
                    })
                        ->whereDoesntHave('client_payment_service', function ($query) {
                            $query->whereDate('created_at', Carbon::now()->toDateString());
                        })
                        ->whereDoesntHave('transactions', function ($query) {
                            $query->whereDate('created_at', Carbon::now()->toDateString());
                        });
                })
                    //Custom
                    ->orWhere(function ($query) {
                        $query->whereHas('client.client_main_information', function ($query) {
                            $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM);
                        })->whereHas('client_payment_service', function ($query) {
                            $query->whereRaw('DATE(created_at) <= DATE(DATE_SUB(now(),INTERVAL 1 MONTH))');
                        });
                    })
                    //Recurrent
                    ->orWhere(function ($query) {
                        $query
                            ->whereHas('client.client_main_information', function ($query) {
                                $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT);
                            })
                            ->whereHas('client.billing_configuration', function ($query) {
                                $query->where('billing_activated', 1)
                                    ->where('billing_date', (integer)Carbon::now()->format('d'));
                            })
                            ->whereDoesntHave('transactions', function ($query) {
                                $query->where(DB::Raw('DATE(created_at)'), Carbon::now()->toDateString());
                            })
                            ->whereHas('transactions', function ($query) {
                                $query->whereRaw('DATE(created_at) = DATE(DATE_SUB(now(), INTERVAL billing_configurations.period MONTH))');
                            })
                            ->where(function ($query) {
                                $query->whereNull('billing_configurations.grace_period')
                                    ->orWhereDoesntHave('client_grace_period')
                                    ->orWhereHas('client_grace_period', function ($query) {
                                        $query->whereRaw('DATE(created_at) >= DATE(DATE_SUB(now(), INTERVAL billing_configurations.grace_period Day))');
                                    });
                            });
                    });
            })
            ->get();

        $clientCustomServices = ClientCustomService::with('client.balance', 'client.client_main_information', 'custom', 'client.billing_configuration', 'client.payments')
            ->leftJoin('clients', 'client_custom_services.client_id', '=', 'clients.id')
            ->leftJoin('billing_configurations', 'clients.id', '=', 'billing_configurations.client_id')
            ->select('client_custom_services.*')
            ->where('estado', '=', 'Activado')
            ->where('charged', '=', 1)
            ->where('deployed', '=', 1)
            ->whereNull('client_bundle_service_id')
            ->where('payment_type', '=', 'Pago recurrente')
            ->where(function ($query) {
                //Daily
                $query->where(function ($query) {
                    $query->whereHas('client.client_main_information', function ($query) {
                        $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_DAILY);
                    })
                        ->whereDoesntHave('client_payment_service', function ($query) {
                            $query->whereDate('created_at', Carbon::now()->toDateString());
                        })
                        ->whereDoesntHave('transactions', function ($query) {
                            $query->whereDate('created_at', Carbon::now()->toDateString());
                        });
                })
                    //Custom
                    ->orWhere(function ($query) {
                        $query->whereHas('client.client_main_information', function ($query) {
                            $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM);
                        })->whereHas('client_payment_service', function ($query) {
                            $query->whereRaw('DATE(created_at) <= DATE(DATE_SUB(now(),INTERVAL 1 MONTH))');
                        });
                    })
                    //Recurrent
                    ->orWhere(function ($query) {
                        $query
                            ->whereHas('client.client_main_information', function ($query) {
                                $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT);
                            })
                            ->whereHas('client.billing_configuration', function ($query) {
                                $query->where('billing_activated', 1)
                                    ->where('billing_date', (integer)Carbon::now()->format('d'));
                            })
                            ->whereDoesntHave('transactions', function ($query) {
                                $query->where(DB::Raw('DATE(created_at)'), Carbon::now()->toDateString());
                            })
                            ->whereHas('transactions', function ($query) {
                                $query->whereRaw('DATE(created_at) = DATE(DATE_SUB(now(), INTERVAL billing_configurations.period MONTH))');
                            })
                            ->where(function ($query) {
                                $query->whereNull('billing_configurations.grace_period')
                                    ->orWhereDoesntHave('client_grace_period')
                                    ->orWhereHas('client_grace_period', function ($query) {
                                        $query->whereRaw('DATE(created_at) >= DATE(DATE_SUB(now(), INTERVAL billing_configurations.grace_period Day))');
                                    });
                            });
                    });
            })
            ->get();

        $clientBundleServices = ClientBundleService::with('client.balance', 'client.client_main_information', 'client.billing_configuration', 'service_internet.service_in_address_list', 'client.payments.payment_promise')
            ->leftJoin('clients', 'client_bundle_services.client_id', '=', 'clients.id')
            ->leftJoin('billing_configurations', 'clients.id', '=', 'billing_configurations.client_id')
            ->leftJoin('client_internet_services', 'client_bundle_services.id', '=', 'client_internet_services.client_bundle_service_id')
            ->select('client_bundle_services.*')
            ->bundleActive()
            ->bundleCharged()
            ->bundleDeployed()
            ->whereDoesntHave('service_internet.service_in_address_list')
            ->where(function ($query) {
                //  Daily
                $query->where(function ($query) {
                    $query->isClientTypeOfBilling(TypeBilling::TYPE_OF_BILLING_PREPAID_DAILY)
                        ->getClientDontHaveClientPaymentToday()
                        ->getClientDontHaveTransactionToday();
                })
                    //Custom
                    ->orWhere(function ($query) {
                        $query->isClientTypeOfBilling(TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM)
                            ->getServicePaymentAMonthAgo();
                    })
                    //Recurrent
                    ->orWhere(function ($query) {
                        $query->isClientTypeOfBilling(TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT)
                            ->getClientActiveBillingToday()
                            ->getClientDontHaveTransactionToday()
                            ->getClientDontHaveTransactionAMonthAgo()
                            ->where(function ($query) {
                                $query->whereNull('billing_configurations.grace_period')
                                    ->orWhereDoesntHave('client.client_grace_period')
                                    ->getIsGracePeriodExpired();
                            });
                    });
            })
            ->orWhere(function ($query) {
                $query->getIsClientEstado("Bloqueado")
                    ->whereHas('client.balance', function ($query) {
                        $query->whereRaw('amount >= client_bundle_services.price');
                    })
                    ->whereHas('service_internet.service_in_address_list');
            })
            ->orWhere(function ($query) {
                $query->isClientTypeOfBilling(TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT);
                $query->whereHas('client.payments', function ($query) {
                    $query->where('enabled_payment_promise', 1)
                        ->whereHas('payment_promise', function ($query) {
                            $query->whereDate('court_date', '=', \Carbon\Carbon::now()->format('Y-m-d'));
                        });
                });
            })
            ->get();

        $services = [
            $clientInternetServices,
            $clientVozServices,
            $clientCustomServices,
            $clientBundleServices
        ];

        $clientIdInternetServices = $clientInternetServices->pluck('client_id');
        $clientIdVozServices = $clientVozServices->pluck('client_id');
        $clientIdCustomServices = $clientCustomServices->pluck('client_id');
        $clientIdBundleServices = $clientBundleServices->pluck('client_id');

        $clients = $this->getClientIds($clientIdInternetServices, $clientIdVozServices ,$clientIdCustomServices ,$clientIdBundleServices);

        foreach ($clients as $clientId){
            $client = Client::find($clientId);
            if ($client) {
                if ($this->noExisteFacturaConServiciosParaCliente($client)){
                    $invoice = $this->clientRepository->addInvoiceService($client->id, false);
                    foreach ($services as $service) {
                        $idServices =  $service->where('client_id', $client->id)->pluck('id');
                        foreach($idServices as $id) {
                            $invoice->client_invoice_service()->update([
                                'client_serviceable_id' => $id,
                                'client_serviceable_type' => get_class($service[0])
                            ]);
                        }
                    }
                }
            }
        }


        foreach ($clientInternetServices as $clientInternetService) {
            RectifyBalanceAndCreateTransactionInClientService::dispatch('Internet', $clientInternetService);
        }

        foreach ($clientVozServices as $clientVozService) {
            RectifyBalanceAndCreateTransactionInClientService::dispatch('Voise', $clientVozService);
        }

        foreach ($clientCustomServices as $clientCustomService) {
            RectifyBalanceAndCreateTransactionInClientService::dispatch('Custom', $clientCustomService);
        }

        foreach ($clientBundleServices as $clientBundleService) {
            RectifyBalanceAndCreateTransactionInClientService::dispatch('bundle', $clientBundleService);
        }

    }

    public function getClientIds($clientIdInternetServices, $clientIdVozServices ,$clientIdCustomServices ,$clientIdBundleServices){
        $union = Arr::collapse([$clientIdInternetServices,$clientIdVozServices,$clientIdCustomServices,$clientIdBundleServices]);
        return array_unique( $union,SORT_NUMERIC);
    }

    public function getInvoiceId($clientId){
        return Client::find($clientId)->lastInvoice()->id;
    }

    public function noExisteFacturaConServiciosParaCliente($client)
    {
        return $this->clientCustomAndRecurrente($client);
        //daily

    }

    public function clientCustomAndRecurrente($client){
        $client = Client::with('client_main_information', 'client_invoices')
            ->whereHas('client_main_information',function ($query) {
                $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT)
                    ->orWhere('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM);
            })
            ->where('id',$client->id)
            ->first();

        $invoice = $client->client_invoices()
            ->where('payment', 0)
            ->where(DB::raw('DATE_FORMAT(created_at,"%m")'), Carbon::now()->format('m'))
            ->orderBy('created_at', 'desc')
            ->first();

        if ($invoice) {
            $invoiceServices = $invoice->client_invoice_service;
        } else {
            return true;
        }

        $services = ["bundle_service", "internet_service", "voz_service", "custom_service"];
        if ($client) {
            $activeServices = $this->clientRepository->getServiceActive($client->id);
            foreach ($services as $service) {
                foreach ($activeServices->$service as $activeService) {
                    $invoiceService = $invoiceServices->where('client_serviceable_id',$activeService->id);
                    if ($invoiceService->count()) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        }
    }
}
