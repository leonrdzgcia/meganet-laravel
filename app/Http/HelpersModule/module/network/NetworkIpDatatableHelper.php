<?php


namespace App\Http\HelpersModule\module\network;

use App\Models\Module;
use App\Models\NetworkIp;

class NetworkIpDatatableHelper
{
    private $model, $columns;


    public function __construct()
    {
        $this->model = NetworkIp::class;
        $moduleName = 'NetworkIp';
        $this->columns = Module::where('name', $moduleName)->first()->columnsDatatable->where('name', '!=', 'action')->pluck('name')->toArray();
    }

    public function fetch_datatable_data($request)
    {
        $idModule = $request->idModule;
        $totalData = $this->count($idModule);

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $this->columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $array = $this->ordering_query($start, $limit, $order, $dir, $idModule);
        } else {
            $search = $request->input('search.value');

            $array = $this->searching_query($start, $limit, $order, $dir, $search, $idModule);

            $totalFiltered = $this->filtering_query($search, $idModule);
        }

        $param_resource = collect(['array' => $array, 'totalData' => $totalData, 'totalFiltered' => $totalFiltered, 'request' => $request]);
        return $this->transform($param_resource);
    }

    public function count($idModule)
    {
        return $this->model::where('network_id', $idModule)
            ->count();
    }

    public function ordering_query($start, $limit, $order, $dir, $idModule)
    {
        return $this->model::select('network_ips.*', 'client_main_information.name as client_name')
            ->leftJoin('client_main_information', 'network_ips.used_by', '=', 'client_main_information.id')
            ->where('network_id', $idModule)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }

    public function searching_query($start, $limit, $order, $dir, $search, $idModule)
    {
        return $this->model::filters($this->columns, $search)
            ->select('network_ips.*', 'client_main_information.name as client_name')
            ->leftJoin('client_main_information', 'network_ips.used_by', '=', 'client_main_information.id')
            ->where('network_id', $idModule)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }

    public function filtering_query($search, $idModule)
    {
        return $this->model::filters($this->columns, $search)
            ->where('network_id', $idModule)
            ->count();
    }

    public function transform($request)
    {
        $data = array();
        $buttons = $this->objectToArray(json_decode($request['request']->buttons));

        if (!empty($request['array'])) {
            foreach ($request['array'] as $key => $value) {
                $id = $value->id;
                foreach ($this->columns as $val) {
                    if ($val == 'used'){
                        $nestedData[$val] = $value->$val ? 'Si' : 'No';
                        continue;
                    }
                    $nestedData[$val] = $value->$val;
                }

                if ($this->isNotAdmin($value)) {
                    $nestedData['action'] = view('meganet.shared.table.actions_group_buttons', [
                        'id' => $id,
                        'buttons' => $buttons
                    ])->toHtml();
                } else {
                    $nestedData['action'] = '';
                }

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

    public function isNotAdmin($column)
    {
        return $column->name != 'Administrador';
    }
}
