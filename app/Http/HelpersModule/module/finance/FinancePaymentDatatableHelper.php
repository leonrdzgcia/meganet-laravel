<?php


namespace App\Http\HelpersModule\module\finance;

use App\Models\Module;
use App\Models\Payment;
use App\Http\HelpersModule\module\HelperDatatable;


class FinancePaymentDatatableHelper
{
    private $model, $columns;


    public function __construct()
    {
        $this->model = Payment::class;
        $moduleName = 'FinancePayment';
        $this->columns = Module::where('name', $moduleName)->first()
        ->columnsDatatable->where('name', '!=', 'action')->pluck('name')->toArray();
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
        return $this->model::select('payments.id', 'payments.date','method_of_payments.type as payment_method_id','payments.amount', 'payments.payment_period','payments.comment', 'client_main_information.name as paymentable_id')
            ->leftJoin('client_main_information', 'payments.paymentable_id', '=', 'client_main_information.id')
            ->leftJoin('method_of_payments', 'payments.payment_method_id', '=', 'method_of_payments.id')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }

    public function searching_query($start, $limit, $order, $dir, $search)
    {
        return $this->model::filters($this->columns, $search)
            ->select('payments.id', 'payments.date','method_of_payments.type as payment_method_id','payments.amount', 'payments.payment_period','payments.comment', 'client_main_information.name as paymentable_id')
            ->leftJoin('client_main_information', 'payments.paymentable_id', '=', 'client_main_information.id')
            ->leftJoin('method_of_payments', 'payments.payment_method_id', '=', 'method_of_payments.id')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }

    public function filtering_query($search)
    {
        return $this->model::filters($this->columns, $search)
            ->count();
    }

    public function transform($request)
    {   
        $data = array();

        $type_modal_edit = '';

        if (!empty($request['array'])) {
            foreach ($request['array'] as $key => $value) {
                $id = $value->id;
                foreach ($this->columns as $val){
                    $nestedData[$val] = $value->$val;
                }

                $info = [
                    'id' => $id,
                    'module' => 'FinancePayment',
                    'group' => 'finance',
                    'submodule' => 'FinancePayment'
                ];

                if ($type_modal_edit) $info = Arr::add($info, 'modal', $request['request']->modal);
                $nestedData['action'] = view('meganet.shared.table.actions' . $type_modal_edit, $info)->toHtml();
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
    public function objectToArray($object)
    {
        return collect($object)->map(function ($item, $key) {
            $result = [];
            foreach ($item as $type => $values) {
                $result[$type] = [];
                foreach ($values as $key => $val) {
                    $result[$type][$key] = $val;
                }
            }
            return $result;
        })->toArray();
    }
    public function isNotAdmin($column){
        return $column->name != 'Administrador';
    }
}
