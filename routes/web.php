<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
include('script_db.php');

Auth::routes();
Route::get('test', function () {
    return view('welcome');
});
//Language Translation
Route::get('script', 'TestScriptController@script');
Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['check_route_permission']], function () {
        Route::group(['namespace' => 'Module'], function () {
            // Module Configuracion
            Route::group(['prefix' => 'configuracion', 'namespace' => 'Setting'], function () {
                Route::get('/', 'SettingController@index');
                Route::post('/debt-payment-client-recurrent', 'SettingController@debtPaymentClientRecurrent');
                Route::post('/debt-payment-client-custom', 'SettingController@debtPaymentClientCustom');

                Route::group(['prefix' => 'debitcustom'], function () {
                    Route::get('/', 'SettingDebitPaymentCustomController@index');
                    Route::post('/add', 'SettingDebitPaymentCustomController@store');
                    Route::get('/editar/{id}', 'SettingDebitPaymentCustomController@edit');
                    Route::post('/update/{id}', 'SettingDebitPaymentCustomController@update');
                    Route::post('/destroy/{id}', 'SettingDebitPaymentCustomController@destroy');
                    Route::post('/table', 'SettingDebitPaymentCustomController@table');
                });
            });

            // Module Administracion
            Route::group(['prefix' => 'administracion', 'namespace' => 'Administration'], function () {
                Route::get('/', 'AdministracionController@index');
                Route::get('/clean-all-client-service', 'AdministracionController@clearAllClientServices');

                Route::group(['prefix' => 'rol', 'namespace' => 'Rol'], function () {
                    Route::get('/', 'RolController@index')->name('roles');
                    Route::get('/crear', 'RolController@create');
                    Route::post('/add', 'RolController@store');
                    Route::get('/editar/{id}', 'RolController@edit');
                    Route::post('/update/{id}', 'RolController@update');
                    Route::get('/destroy/{id}', 'RolController@destroy');
                    Route::post('/table', 'RolController@table');
                });

                Route::group(['prefix' => 'permisos', 'namespace' => 'Permission'], function () {
                    Route::post('/get-permission-for-role/{role}', 'PermissionController@get');
                });

                Route::group(['prefix' => 'socios', 'namespace' => 'Partner'], function () {
                    Route::get('/', 'PartnerController@index');
                    Route::post('/add', 'PartnerController@store');
                    Route::get('/editar/{id}', 'PartnerController@edit');
                    Route::post('/update/{id}', 'PartnerController@update');
                    Route::post('/destroy/{id}', 'PartnerController@destroy');
                    Route::post('/table', 'PartnerController@table');
                });

                Route::group(['prefix' => 'ubicacion', 'namespace' => 'Location'], function () {
                    Route::get('/', 'LocationController@index');
                    Route::post('/add', 'LocationController@store');
                    Route::get('/editar/{id}', 'LocationController@edit');
                    Route::post('/update/{id}', 'LocationController@update');
                    Route::post('/destroy/{id}', 'LocationController@destroy');
                    Route::post('/table', 'LocationController@table');
                });

                Route::group(['prefix' => 'estado', 'namespace' => 'State'], function () {
                    Route::get('/', 'StateController@index');
                    Route::post('/add', 'StateController@store');
                    Route::get('/editar/{id}', 'StateController@edit');
                    Route::post('/update/{id}', 'StateController@update');
                    Route::post('/destroy/{id}', 'StateController@destroy');
                    Route::post('/table', 'StateController@table');
                });

                Route::group(['prefix' => 'ift', 'namespace' => 'Ift'], function () {
                    Route::get('/', 'IftController@index');
                    Route::post('/add', 'IftController@store');
                    Route::get('/editar/{id}', 'IftController@edit');
                    Route::post('/update/{id}', 'IftController@update');
                    Route::post('/destroy/{id}', 'IftController@destroy');
                    Route::post('/table', 'IftController@table');
                });

                Route::group(['prefix' => 'municipio', 'namespace' => 'Municipality'], function () {
                    Route::get('/', 'MunicipalityController@index');
                    Route::post('/add', 'MunicipalityController@store');
                    Route::get('/editar/{id}', 'MunicipalityController@edit');
                    Route::post('/update/{id}', 'MunicipalityController@update');
                    Route::post('/destroy/{id}', 'MunicipalityController@destroy');
                    Route::post('/table', 'MunicipalityController@table');
                });

                Route::group(['prefix' => 'colonia', 'namespace' => 'Colony'], function () {
                    Route::get('/', 'ColonyController@index');
                    Route::post('/add', 'ColonyController@store');
                    Route::get('/editar/{id}', 'ColonyController@edit');
                    Route::post('/update/{id}', 'ColonyController@update');
                    Route::post('/destroy/{id}', 'ColonyController@destroy');
                    Route::post('/table', 'ColonyController@table');
                });

                Route::group(['prefix' => 'metotdo-de-pago', 'namespace' => 'MethodOfPayment'], function () {
                    Route::get('/', 'MethodOfPaymentController@index');
                    Route::post('/add', 'MethodOfPaymentController@store');
                    Route::get('/editar/{id}', 'MethodOfPaymentController@edit');
                    Route::post('/update/{id}', 'MethodOfPaymentController@update');
                    Route::post('/destroy/{id}', 'MethodOfPaymentController@destroy');
                    Route::post('/table', 'MethodOfPaymentController@table');
                });
            });

            // Module Plan
            Route::group(['namespace' => 'Plan'], function () {
                // Internet
                Route::group(['prefix' => 'internet'], function () {
                    Route::get('/', 'InternetController@index')->name('internet');
                    Route::get('/success/{id}', 'InternetController@success');
                    Route::get('/crear', 'InternetController@create');
                    Route::post('/add', 'InternetController@store');
                    Route::get('/editar/{id}', 'InternetController@edit');
                    Route::post('/update/{id}', 'InternetController@update');
                    Route::post('/destroy/{id}', 'InternetController@destroy');
                    Route::post('/table', 'InternetController@table');
                });
                // Bundles
                Route::group(['prefix' => 'paquetes'], function () {
                    Route::get('/', 'BundleController@index')->name('paquetes');
                    Route::get('/success/{id}', 'BundleController@success');
                    Route::get('/crear', 'BundleController@create');
                    Route::post('/add', 'BundleController@store');
                    Route::get('/editar/{id}', 'BundleController@edit');
                    Route::post('/update/{id}', 'BundleController@update');
                    Route::post('/destroy/{id}', 'BundleController@destroy');
                    Route::post('/table', 'BundleController@table');
                });
                // Voz
                Route::group(['prefix' => 'voz'], function () {
                    Route::get('/', 'VozController@index')->name('voz');
                    Route::get('/success/{id}', 'VozController@success');
                    Route::get('/crear', 'VozController@create');
                    Route::post('/add', 'VozController@store');
                    Route::get('/editar/{id}', 'VozController@edit');
                    Route::post('/update/{id}', 'VozController@update');
                    Route::post('/destroy/{id}', 'VozController@destroy');
                    Route::post('/table', 'VozController@table');
                });
                // Custom
                Route::group(['prefix' => 'custom'], function () {
                    Route::get('/', 'CustomController@index')->name('recurrente');
                    Route::get('/success/{id}', 'CustomController@success');
                    Route::get('/crear', 'CustomController@create');
                    Route::post('/add', 'CustomController@store');
                    Route::get('/editar/{id}', 'CustomController@edit');
                    Route::post('/update/{id}', 'CustomController@update');
                    Route::post('/destroy/{id}', 'CustomController@destroy');
                    Route::post('/table', 'CustomController@table');
                });
            });

// Module Tickets
            Route::group(['prefix' => 'tickets', 'namespace' => 'Ticket'], function () {
                Route::get('/', 'DashboardController@index');

                Route::get('/abiertos', 'TicketController@opened');
                Route::get('/abiertos/{client_id}', 'TicketController@opened');
                Route::get('/cerrados', 'TicketController@closed');
                Route::get('/cerrados/{client_id}', 'TicketController@closed');
                Route::get('/reciclados', 'TicketController@trash');
                Route::get('/crear', 'TicketController@create');
                Route::get('/crear/{clientId}', 'TicketController@create');
                Route::post('/add', 'TicketController@store');
                Route::get('/success/{id}', 'TicketController@success');
                Route::get('/editar/{id}', 'TicketController@edit');
                Route::get('/ver/{id}', 'TicketController@ver');
                Route::post('/update/{id}', 'TicketController@update');
                Route::post('/mensaje/update/{id}', 'TicketThreadController@update');
                Route::post('/mensaje/add/{id}', 'TicketThreadController@store');
                Route::get('/destroy/{id}', 'TicketController@destroy');
                Route::post('/table', 'TicketController@table');
                Route::get('/notifica/{id}', 'TicketController@notificationsReadMarked');

                Route::post('/get-ticket-by-id/{id}', 'TicketController@getTicketById');
                Route::post('/get-time-lapsed/{id}', 'TicketController@getTimeLapsed');
                Route::post('/get-user-data-by-ticket-id/{id}', 'TicketController@getUserDataTicketById');
                Route::post('/set-status-ticket-by-id/{id}', 'TicketController@setStatusTicketById');
                Route::post('/get-ticket-thread-by-id/{id}', 'TicketThreadController@getTicketThreadById');
                Route::post('/get-data-client/{id}', 'TicketController@getDataClient');
                Route::post('/get-parent-ticket-by-id/{id}', 'TicketThreadController@getParentTicketById');
                Route::post('/get-child-ticket-by-id/{id}', 'TicketThreadController@getChildTicketById');

                Route::post('/request-statistics-for-tarjets-by-status', 'DashboardController@getStatisticsForTarjetsByStatus');
                Route::post('/request-ticket-assigned-to-me', 'DashboardController@getTicketAssignedToMe');
                Route::post('/request-ticket-assigned-to', 'DashboardController@getTicketAssignedTo');


            });

            // Module CRM
            Route::group(['prefix' => 'crm', 'namespace' => 'Crm'], function () {
                Route::get('/', 'DashboardController@index');
                Route::get('/success/{id}', 'CrmController@success');
                Route::get('/listar', 'CrmController@index')->name('crm');
                Route::get('/crear', 'CrmController@create');
                Route::post('/add', 'CrmController@store');
                Route::get('/editar/{id}', 'CrmController@edit');
                Route::post('/update/{id}', 'CrmInformationController@update');
                Route::post('/convert-to-client/{id}', 'CrmController@convertToClient');
                Route::post('/update-last-contacted/{id}', 'CrmController@updateLastContacted');
                Route::post('/destroy/{id}', 'CrmController@destroy');
                Route::post('/table', 'CrmController@table');

                Route::group(['prefix' => 'document'], function () {
                    Route::post('/add/{idCrm}', 'DocumentCrmController@store');
                    Route::post('/update/{idCrm}', 'DocumentCrmController@update');
                    Route::post('/upload-file/{id}', 'DocumentCrmController@uploadFile');
                    Route::post('/table', 'DocumentCrmController@table');
                    Route::post('/destroy/{id}', 'DocumentCrmController@destroy');
                });
            });

            // Module Cliente
            Route::group(['prefix' => 'cliente', 'namespace' => 'Client'], function () {
                Route::get('/success/{id}', 'ClientController@success');
                Route::post('/debit/{id}', 'ClientController@getClientDebit');
                Route::get('/listar', 'ClientController@index')->name('cliente');
                Route::get('/crear', 'ClientController@create');
                Route::post('/add', 'ClientController@store');
                Route::get('/editar/{id}', 'ClientController@edit');
                Route::post('/editar/{id}', 'ClientController@getVal');
                Route::post('/update/{id}', 'ClientInformationController@update');



                Route::group(['prefix' => 'document'], function () {
                    Route::post('/add/{idClient}', 'DocumentClientController@store');
                    Route::post('/update/{idClient}', 'DocumentClientController@update');
                    Route::post('/upload-file/{id}', 'DocumentClientController@uploadFile');
                    Route::post('/table', 'DocumentClientController@table');
                    Route::post('/destroy/{id}', 'DocumentClientController@destroy');
                });

                Route::post('/get-client-with-balance/{id}', 'ClientInformationController@getClientWithBalance');
                Route::post('/get-tickets-open/{id}', 'ClientInformationController@getClientTicketsOpen');
                Route::post('/get-client-status/{id}', 'ClientInformationController@getClientStatus');

                Route::post('/destroy/{id}', 'ClientController@destroy');
                Route::post('/table', 'ClientController@table');

                Route::post('/has-service/{id}', 'ClientServiceController@hasService');
                Route::post('/can-add-service/{id}', 'ClientServiceController@canAddService');

                Route::group(['prefix' => 'clientbundleservice'], function () {
                    Route::post('/bundle/{bundle_id}', 'ClientBundleServiceController@getPlansById');
                    Route::post('/bundle/edit/{service_bundle_id}', 'ClientBundleServiceController@getEditedServiceBundleById');
                    Route::post('/table', 'ClientBundleServiceController@table');
                    Route::post('/update/{id}', 'ClientBundleServiceController@update');
                    Route::post('/crear/{id}', 'ClientBundleServiceController@store');
                    Route::post('/destroy/{id}', 'ClientBundleServiceController@destroy');
                });
                Route::group(['prefix' => 'clientinternetservice'], function () {
                    Route::post('/table', 'ClientInternetServiceController@table');
                    Route::post('/update/{id}', 'ClientInternetServiceController@update');
                    Route::post('/crear/{id}', 'ClientInternetServiceController@store');
                    Route::post('/destroy/{id}', 'ClientInternetServiceController@destroy');
                });
                Route::group(['prefix' => 'clientvozservice'], function () {
                    Route::post('/table', 'ClientVozServiceController@table');
                    Route::post('/update/{id}', 'ClientVozServiceController@update');
                    Route::post('/crear/{id}', 'ClientVozServiceController@store');
                    Route::post('/destroy/{id}', 'ClientVozServiceController@destroy');
                });
                Route::group(['prefix' => 'clientcustomservice'], function () {
                    Route::post('/table', 'ClientCustomServiceController@table');
                    Route::post('/update/{id}', 'ClientCustomServiceController@update');
                    Route::post('/crear/{id}', 'ClientCustomServiceController@store');
                    Route::post('/destroy/{id}', 'ClientCustomServiceController@destroy');
                });

                Route::post('/clientbundleservice/table', 'ClientBundleServiceController@table');

                Route::group(['prefix' => 'billing'], function () {
                    Route::post('/update-billing-configuration/{id}', 'ClientBillingConfigurationController@update');
                    Route::post('/client-debit-rectification-agreement/{id}', 'ClientBillingConfigurationController@getClientDebitRectificationAgreement');

                    Route::post('/update-billing-address/{id}', 'ClientBillingAddressController@update');
                    Route::post('/update-reminders-configuration/{id}', 'ClientBillingRemindersConfigurationController@update');
                    Route::post('/get-reminder-payment-amount/{id}', 'ClientBillingRemindersConfigurationController@getReminderPaymentAmount');
                    Route::post('/get-billing-information-block/{id}', 'ClientBillingConfigurationController@getBillingInformationBlock');
                    Route::post('/get-payment-method/{id}', 'ClientBillingConfigurationController@getPaymentMethod');


                    Route::group(['prefix' => 'payment'], function () {
                        Route::post('/crear/{id}', 'ClientPaymentController@store');
                        Route::post('/update/{id}', 'ClientPaymentController@update');
                        Route::post('/destroy/{id}', 'ClientPaymentController@destroy');
                        Route::post('/table', 'ClientPaymentController@table');
                        Route::get('/pdf/{id}', 'ClientPaymentController@getPrintPdf');

                        Route::post('/get-totals/{id}', 'ClientPaymentController@getTotals');
                        Route::post('/get-cost-all-service-active/{id}', 'ClientPaymentController@getCostAllServiceActive');
                        Route::post('/get-active-service-expiration/{id}', 'ClientPaymentController@getActiveServiceExpiration');
                    });

                    Route::group(['prefix' => 'transaction'], function () {
                        Route::post('/crear/{id}', 'ClientTransactionController@store');
                        Route::post('/update/{id}', 'ClientTransactionController@update');
                        Route::post('/get-totals/{id}', 'ClientTransactionController@getTotals');
                        Route::post('/destroy/{id}', 'ClientTransactionController@destroy');
                        Route::post('/table', 'ClientTransactionController@table');
                    });

                    Route::group(['prefix' => 'invoice'], function () {
                        Route::post('/crear/{id}', 'ClientInvoiceController@store');
                        Route::post('/update/{id}', 'ClientInvoiceController@update');
                        Route::post('/get-totals/{id}', 'ClientInvoiceController@getTotals');
                        Route::post('/destroy/{id}', 'ClientInvoiceController@destroy');
                        Route::post('/table', 'ClientInvoiceController@table');
                        Route::get('/pdf/{id}', 'ClientInvoiceController@getPrintPdf');
                        Route::get('/create-new-client-invoice/{id}', 'ClientInvoiceController@createManualClientInvoice');


                    });
                });
            });

            Route::group(['prefix' => 'mapas', 'namespace' => 'Mapas'], function () {
                Route::get('/', 'MapasController@index');
            });

            Route::group(['prefix' => 'red'], function () {

                Route::group(['prefix' => 'ipv4', 'namespace' => 'Network'], function () {
                    Route::post('/add', 'NetworkController@store');
                    Route::get('/listar', 'NetworkController@index');
                    Route::get('/success', 'NetworkController@success');
                    Route::get('/crear', 'NetworkController@create');
                    Route::post('/table', 'NetworkController@table');
                    Route::post('/update/{id}', 'NetworkController@update');
                    Route::post('/destroy/{id}', 'NetworkController@destroy');
                    Route::post('/network/{id}', 'NetworkController@getIpByNetwork');

                    Route::get('/ver/{id}', 'NetworkIpController@show');
                    Route::post('/ip/table', 'NetworkIpController@table');

                    Route::post('/calculator', 'Ipv4CalculatorController@calculator');
                });

                Route::group(['prefix' => 'router', 'namespace' => 'Router'], function () {
                    Route::get('/listar', 'RouterController@index');
                    Route::get('/success/{id}', 'RouterController@success');
                    Route::get('/crear', 'RouterController@create');
                    Route::post('/add', 'RouterController@store');
                    Route::get('/editar/{id}', 'RouterController@edit');
                    Route::post('/update/{id}', 'RouterController@update');
                    Route::post('/destroy/{id}', 'RouterController@destroy');
                    Route::post('/table', 'RouterController@table');


                    Route::group(['prefix' => 'mikrotik'], function () {
                        Route::get('/crear', 'MikrotikController@create');
                        Route::post('/add', 'MikrotikController@store');
                        Route::get('/editar/{id}', 'MikrotikController@edit');
                        Route::post('/update/{id}', 'MikrotikController@update');
                        Route::post('/crear/{id}', 'MikrotikController@store');
                        Route::post('/destroy/{id}', 'MikrotikController@destroy');
                        Route::post('/table', 'MikrotikController@table');
                        Route::get('/cleantails', 'MikrotikController@clearMikrotikTails');

                        Route::group(['prefix' => 'config'], function () {
                            Route::get('/editar/{id}', 'MikrotikConfigController@edit');
                            Route::post('/update/{id}', 'MikrotikConfigController@update');
                            Route::post('/crear/{id}', 'MikrotikConfigController@store');
                            Route::post('/destroy/{id}', 'MikrotikConfigController@destroy');
                        });
                    });
                });
            });

            Route::group(['prefix' => 'finanzas','namespace' => 'Finance'], function () {

                Route::group(['prefix' => 'transacciones','namespace' => 'Transaction'], function () {
                    Route::get('/', 'FinanceTransactionController@index');
                    Route::post('/table', 'FinanceTransactionController@table');
                });

                Route::group(['prefix' => 'facturas','namespace' => 'Invoice'], function () {
                    Route::get('/', 'FinanceInvoiceController@index');
                    Route::post('/table', 'FinanceInvoiceController@table');
                });

                Route::group(['prefix' => 'pagos','namespace' => 'Payment'], function () {
                    Route::get('/', 'FinancePaymentController@index');
                    Route::post('/table', 'FinancePaymentController@table');
                });
            });
        });

        Route::post('/cliente/get-receipt-for-client', 'Utils\ReceiptController@getReceiptForClient');
        Route::post('/get-payment-period', 'Utils\UtilController@getPaymentPeriod');


        Route::group(['prefix' => 'perfil'], function () {
            Route::get('/{id}', 'UserController@show');
            Route::get('/editar/{id}', 'UserController@edit');
            Route::post('/update/{id}', 'UserController@update');
            Route::post('/update-image/{id}', 'UserController@updateImage');
        });

        Route::group(['namespace' => 'Module'], function () {
            Route::group(['namespace' => 'Router'], function () {
                Route::post('/status-by-router/{id}', 'MikrotikController@getMikrotikStatus');
                Route::post('/remove-rules-by-router/{id}', 'MikrotikController@getMikrotikRemoveRules');
                Route::post('/create-rules-by-router/{id}', 'MikrotikController@getMikrotikCreateRules');
                Route::post('/request-clone-client-to-mikrotik', 'MikrotikController@cloneClientToMikrotik');
            });
        });
    });

    Route::get('/', 'HomeController@index');
    Route::post('/get-home-statistics-for-tarjets-by-status-c', 'HomeController@getHomeStatisticsForTarjetsByStatus');
    Route::post('/get-home-statistics-for-text-card-in-dashboard-c', 'HomeController@getStatisticsForTextCardInDashBoard');
    Route::post('/get-stats-client-card-in-dashboard-c', 'HomeController@getStatsCardClientInDashBoard');
    Route::post('/get-stats-ticket-card-in-dashboard-c', 'HomeController@getStatsCardTicketsInDashBoard');

    Route::get('/index', 'HomeController@index');

    Route::post('/user/get-next-user', 'HelperController@getNextUserId');

    Route::post('/get-data/{module}', 'HelperController@getData');
    Route::post('/fields-by-module', 'HelperController@getFieldsByModule');

    Route::post('/fields-by-module-and-relation', 'HelperController@getFieldsByModuleRelation');
    Route::post('/fields-by-module-with-module-requested', 'HelperController@getFieldsByModuleWithModuleRequested');

    Route::post('/fields-by-module/{id}', 'HelperController@getFieldsEditedById');
    Route::post('/fields-by-module/general/edited', 'HelperController@requestGeneralEditedFields');
    Route::post('/columns-by-module', 'HelperController@getColumnsByModule');
    Route::post('/all-columns-by-module', 'HelperController@getAllColumnsByModule');
    Route::post('/update-column-by-user', 'HelperController@updateColumnsByUser');


    Route::post('/request-random-password', 'HelperController@getRandomPassword');
    Route::post('/save-random-password', 'HelperController@saveRandomPassword');
    Route::post('/request-generate-user', 'HelperController@getGenerateUser');

    Route::post('/get-options-select', 'SearchModelController@search');
    Route::post('/get-options-select/{id}', 'SearchModelController@searchWithoutId');

    Route::post('/has-permission-to-view/{view}', 'Module\Administration\Permission\PermissionController@hasPermissionToView');
    Route::post('/all-view-has-permission', 'Module\Administration\Permission\PermissionController@allViewHasPermission');

    Route::post('/perfil/get-perfil-by-id/{id}', 'UserController@getPerfilById');

    Route::post('/get-user-authenticated', 'HelperController@getUserAuthenticated');

    Route::post('/get-default-value/{date}', 'Utils\DefaultValueController@getDefaultValue');
    Route::post('/get-default-billing-date-for-client', 'Utils\DefaultValueController@getDefaultBillingDateForClient');

    Route::post('/cliente/get-user-for-client', 'Utils\DefaultValueController@getDefaultValueForUserClient');

    Route::post('/crm/send-notification/{id}', 'Utils\NotificationController@sendNotificationCrm');
    Route::post('/get-log-activities/{id}', 'Utils\LogActivityController@getLogActivities');
    Route::post('/fullcalendar/get-billing-configuration', 'Utils\FullcalendarController@getBillingConfiguration');

    Route::post('/get-crm-client-if-exist', 'HelperController@getCrmClientIfExist');

});

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
