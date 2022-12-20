<?php


namespace App\Http\HelpersModule\module\client;

use App\Models\Client;
use App\Models\Module;

class ClientDatatableHelper
{
    private $model;
    private $columns;

    public function __construct()
    {
        $this->model = Client::class;
        $moduleName = 'Client';
        $this->columns = Module::where('name', $moduleName)->first()->columnsDatatable->pluck('name')->toArray();
    }

    public function fetch_datatable_data($request)
    {
        $totalData = $this->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $this->columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $array = $this->ordering_query($start, $limit, $order, $dir);
        } else {
            $search = $request->input('search.value');

            $array = $this->searching_query($start, $limit, $order, $dir, $search);

            $totalFiltered = $this->filtering_query($search);
        }

        $param_resource = collect(['array' => $array, 'totalData' => $totalData, 'totalFiltered' => $totalFiltered, 'request' => $request]);
        return $this->transform($param_resource);
    }

    public function count()
    {
        return $this->model::count();
    }

    public function ordering_query($start, $limit, $order, $dir)
    {
        return $this->model::select(
            'clients.id',
            'client_internet_services.description as internet_fees',
            'client_voz_services.description as voz_fees',
            'client_custom_services.description as custom_fees',
            'client_bundle_services.description as bundle_fees',
            'network_ips.ip as ip_ranges',
            'client_main_information.password',
            'client_main_information.user',
            'client_main_information.name',
            'client_main_information.father_last_name',
            'client_main_information.mother_last_name',
            'client_main_information.estado',
            'client_main_information.phone',
            'client_main_information.phone2',
            'client_main_information.email',
            'client_main_information.street',
            'client_main_information.zip',
            'client_main_information.external_number',
            'client_main_information.internal_number',
            'client_main_information.created_at',
            'client_main_information.updated_at',

            'type_billings.type as type_of_billing_id',

            'client_additional_information.category',
            'client_additional_information.modem_sn',
            'client_additional_information.gpon_ont',
            'client_additional_information.power_dbm',
            'client_additional_information.original_password',
            'client_additional_information.vendor',
            'client_additional_information.box_nomenclator',
            'client_additional_information.user_film',
            'client_additional_information.password_film',
            'client_additional_information.password_wifi',
            'client_additional_information.reinstatement',
            'client_additional_information.social_id',
            'client_additional_information.comment',
            'client_additional_information.installation_on_time',
            'client_additional_information.amount_technician_and_why',
            'client_additional_information.doubt_signed_contract',
            'client_additional_information.technician_attencion',
            'client_additional_information.last_time_online',

            'billing_configurations.billing_activated',
            'billing_configurations.period',
            'billing_configurations.billing_date',
            'billing_configurations.billing_expiration',
            'billing_configurations.grace_period',
            'billing_configurations.autopay_invoice',
            'billing_configurations.send_financial_notification',
            'method_of_payments.type as payment_method_id',

            'type_billings.type as type_billing_id',

            'reminders_configurations.activate_reminders',
            'reminders_configurations.type_of_message',
            'reminders_configurations.reminder_1_days',
            'reminders_configurations.reminder_2_days',
            'reminders_configurations.reminder_3_days',
            'reminders_configurations.reminder_payment_3',
            'reminders_configurations.reminder_payment_amount',
            'reminders_configurations.reminder_payment_comment',

            'billing_addresses.billing_name',
            'billing_addresses.billing_street',
            'billing_addresses.billing_zip_code',
            'billing_addresses.billing_city',

            'balances.amount',

            'states.name as state_id',
            'municipalities.name as municipality_id',
            'colonies.name as colony_id',
            'partners.name as partner_id',
            'client_main_information.nif_pasaport',
            'locations.name as location_id',
            'routers.title as router',
            'client_internet_services.additional_ipv4 as redes_adicionales',
            'client_internet_services.ipv6 as ipv6',
            'client_internet_services.delegated_ipv6 as ipv6_delegada',
            'client_internet_services.mac as mac',
            'client_internet_services.price',
            'client_voz_services.price as voz_price',
            'client_custom_services.price as custom_price',
            'client_bundle_services.price as recurrent_price',

            'client_internet_services.start_date as internet_services_start_date',
            'client_internet_services.finish_date as internet_services_end_date',

            'client_custom_services.start_date as custom_services_start_date',
            'client_custom_services.finish_date as custom_services_end_date',

            'client_voz_services.start_date as voice_services_start_date',
            'client_voz_services.finish_date as voice_services_end_date',

            'client_bundle_services.contract_start_date as recurring_services_start_date',
            'client_bundle_services.contract_end_date as recurring_services_end_date',

        )
            ->leftJoin('client_main_information', 'clients.id', '=', 'client_main_information.client_id')
            ->leftJoin('client_additional_information', 'clients.id', '=', 'client_additional_information.client_id')
            ->leftJoin('billing_configurations', 'clients.id', '=', 'billing_configurations.client_id')
            ->leftJoin('type_billings', 'billing_configurations.type_billing_id', '=', 'type_billings.id')
            ->leftJoin('reminders_configurations', 'clients.id', '=', 'reminders_configurations.client_id')
            ->leftJoin('billing_addresses', 'clients.id', '=', 'billing_addresses.client_id')
            ->leftJoin('balances', 'clients.id', '=', 'balances.balanceable_id')
            ->leftJoin('method_of_payments', 'billing_configurations.payment_method_id', '=', 'method_of_payments.id')
            ->leftJoin('client_internet_services', 'clients.id', '=', 'client_internet_services.client_id')
            ->leftJoin('client_voz_services', 'clients.id', '=', 'client_voz_services.client_id')
            ->leftJoin('client_custom_services', 'clients.id', '=', 'client_custom_services.client_id')
            ->leftJoin('client_bundle_services', 'clients.id', '=', 'client_bundle_services.client_id')
            ->leftJoin('network_ips', 'clients.id', '=', 'network_ips.client_id')
            ->leftJoin('locations', 'client_main_information.location_id', '=', 'locations.id')
            ->leftJoin('routers', 'locations.id', '=', 'routers.location_id')
            ->leftJoin('partners', 'client_main_information.partner_id', '=', 'partners.id')
            ->leftJoin('states', 'client_main_information.state_id', '=', 'states.id')
            ->leftJoin('municipalities', 'client_main_information.municipality_id', '=', 'municipalities.id')
            ->leftJoin('colonies', 'client_main_information.colony_id', '=', 'colonies.id')
            ->where('client_main_information.estado', '!=', 'Cancelado')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }

    public function searching_query($start, $limit, $order, $dir, $search)
    {
        return $this->model::filters($this->columns, $search)
            ->select(
                'clients.id',
                'client_internet_services.description as internet_fees',
                'client_voz_services.description as voz_fees',
                'client_custom_services.description as custom_fees',
                'client_bundle_services.description as bundle_fees',
                'network_ips.ip as ip_ranges',
                'client_main_information.password',
                'client_main_information.user',
                'client_main_information.name',
                'client_main_information.father_last_name',
                'client_main_information.mother_last_name',
                'client_main_information.estado',
                'client_main_information.phone',
                'client_main_information.phone2',
                'client_main_information.email',
                'client_main_information.street',
                'client_main_information.zip',
                'client_main_information.external_number',
                'client_main_information.internal_number',
                'client_main_information.created_at',
                'client_main_information.updated_at',

                'type_billings.type as type_of_billing_id',

                'client_additional_information.category',
                'client_additional_information.modem_sn',
                'client_additional_information.gpon_ont',
                'client_additional_information.power_dbm',
                'client_additional_information.original_password',
                'client_additional_information.vendor',
                'client_additional_information.box_nomenclator',
                'client_additional_information.user_film',
                'client_additional_information.password_film',
                'client_additional_information.password_wifi',
                'client_additional_information.reinstatement',
                'client_additional_information.social_id',
                'client_additional_information.comment',
                'client_additional_information.installation_on_time',
                'client_additional_information.amount_technician_and_why',
                'client_additional_information.doubt_signed_contract',
                'client_additional_information.technician_attencion',
                'client_additional_information.last_time_online',

                'billing_configurations.billing_activated',
                'billing_configurations.period',
                'billing_configurations.billing_date',
                'billing_configurations.billing_expiration',
                'billing_configurations.grace_period',
                'billing_configurations.autopay_invoice',
                'billing_configurations.send_financial_notification',
                'method_of_payments.type as payment_method_id',

                'type_billings.type as type_billing_id',

                'reminders_configurations.activate_reminders',
                'reminders_configurations.type_of_message',
                'reminders_configurations.reminder_1_days',
                'reminders_configurations.reminder_2_days',
                'reminders_configurations.reminder_3_days',
                'reminders_configurations.reminder_payment_3',
                'reminders_configurations.reminder_payment_amount',
                'reminders_configurations.reminder_payment_comment',

                'billing_addresses.billing_name',
                'billing_addresses.billing_street',
                'billing_addresses.billing_zip_code',
                'billing_addresses.billing_city',

                'balances.amount',

                'states.name as state_id',
                'municipalities.name as municipality_id',
                'colonies.name as colony_id',
                'partners.name as partner_id',
                'client_main_information.nif_pasaport',
                'locations.name as location_id',
                'routers.title as router',
                'client_internet_services.additional_ipv4 as redes_adicionales',
                'client_internet_services.ipv6 as ipv6',
                'client_internet_services.delegated_ipv6 as ipv6_delegada',
                'client_internet_services.mac as mac',
                'client_internet_services.price',
                'client_voz_services.price as voz_price',
                'client_custom_services.price as custom_price',
                'client_bundle_services.price as recurrent_price',

                'client_internet_services.start_date as internet_services_start_date',
                'client_internet_services.finish_date as internet_services_end_date',

                'client_custom_services.start_date as custom_services_start_date',
                'client_custom_services.finish_date as custom_services_end_date',

                'client_voz_services.start_date as voice_services_start_date',
                'client_voz_services.finish_date as voice_services_end_date',

                'client_bundle_services.contract_start_date as recurring_services_start_date',
                'client_bundle_services.contract_end_date as recurring_services_end_date',

            )
            ->leftJoin('client_main_information', 'clients.id', '=', 'client_main_information.client_id')
            ->leftJoin('client_additional_information', 'clients.id', '=', 'client_additional_information.client_id')
            ->leftJoin('billing_configurations', 'clients.id', '=', 'billing_configurations.client_id')
            ->leftJoin('type_billings', 'billing_configurations.type_billing_id', '=', 'type_billings.id')
            ->leftJoin('reminders_configurations', 'clients.id', '=', 'reminders_configurations.client_id')
            ->leftJoin('billing_addresses', 'clients.id', '=', 'billing_addresses.client_id')
            ->leftJoin('balances', 'clients.id', '=', 'balances.balanceable_id')
            ->leftJoin('method_of_payments', 'billing_configurations.payment_method_id', '=', 'method_of_payments.id')
            ->leftJoin('client_internet_services', 'clients.id', '=', 'client_internet_services.client_id')
            ->leftJoin('client_voz_services', 'clients.id', '=', 'client_voz_services.client_id')
            ->leftJoin('client_custom_services', 'clients.id', '=', 'client_custom_services.client_id')
            ->leftJoin('client_bundle_services', 'clients.id', '=', 'client_bundle_services.client_id')
            ->leftJoin('network_ips', 'clients.id', '=', 'network_ips.client_id')
            ->leftJoin('locations', 'client_main_information.location_id', '=', 'locations.id')
            ->leftJoin('routers', 'locations.id', '=', 'routers.location_id')
            ->leftJoin('partners', 'client_main_information.partner_id', '=', 'partners.id')
            ->leftJoin('states', 'client_main_information.state_id', '=', 'states.id')
            ->leftJoin('municipalities', 'client_main_information.municipality_id', '=', 'municipalities.id')
            ->leftJoin('colonies', 'client_main_information.colony_id', '=', 'colonies.id')
            ->where('client_main_information.estado', '!=', 'Cancelado')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }

    public function filtering_query($search)
    {
        return $this->model::filters($this->columns, $search)
            ->leftJoin('client_main_information', 'clients.id', '=', 'client_main_information.client_id')
            ->leftJoin('client_additional_information', 'clients.id', '=', 'client_additional_information.client_id')
            ->leftJoin('client_internet_services', 'clients.id', '=', 'client_internet_services.client_id')
            ->where('client_main_information.estado', '!=', 'Cancelado')
            ->count();
    }

    public function transform($request)
    {
        $data = array();

        if (!empty($request['array'])) {
            foreach ($request['array'] as $key => $value) {
                $id = $value->id;
                foreach ($this->columns as $val) {
                    $nestedData[$val] = view('meganet.shared.table.column', [
                        'dir' => '/cliente/editar/' . $value->id,
                        'value' => $value,
                        'column' => $val,
                    ])->toHtml();
                }
                $nestedData['action'] = view('meganet.shared.table.actions', [
                    'id' => $id,
                    'module' => 'cliente',
                    'group' => 'client',
                    'submodule' => 'client'
                ])->toHtml();
                $data[] = $nestedData;
            }
        }

        return [
            "draw" => intval($request['request']->input('draw')),
            "recordsTotal" => intval($request['totalData']),
            "recordsFiltered" => intval($request['totalFiltered']),
            "data" => $data
        ];

    }
}
