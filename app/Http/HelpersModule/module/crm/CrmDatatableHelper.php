<?php


namespace App\Http\HelpersModule\module\crm;

use App\Models\Crm;
use App\Models\Module;

class CrmDatatableHelper
{
    private $model;
    private $columns;
    public function __construct()
    {
        $this->model = Crm::class;
        $moduleName = 'Crm';
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
            $array = $this->ordering_query($start,$limit,$order,$dir);
        } else {
            $search = $request->input('search.value');

            $array = $this->searching_query($start,$limit,$order,$dir,$search);

            $totalFiltered = $this->filtering_query($search);
        }

        $param_resource = collect(['array' => $array,'totalData' => $totalData,'totalFiltered' => $totalFiltered,'request' => $request]);
        return $this->transform($param_resource);
    }

    public function count()
    {
        return $this->model::count();
    }

    public function ordering_query($start, $limit, $order, $dir)
    {
        return $this->model::select('*')
            ->leftJoin('crm_main_information', 'crms.id', '=', 'crm_main_information.crm_id')
            ->leftJoin('crm_lead_information', 'crms.id', '=', 'crm_lead_information.crm_id')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }

    public function searching_query($start, $limit, $order, $dir, $search)
    {
        return $this->model::filters($this->columns, $search)
            ->select('*')
            ->leftJoin('crm_main_information', 'crms.id', '=', 'crm_main_information.crm_id')
            ->leftJoin('crm_lead_information', 'crms.id', '=', 'crm_lead_information.crm_id')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }

    public function filtering_query($search)
    {
        return $this->model::filters($this->columns, $search)
            ->leftJoin('crm_main_information', 'crms.id', '=', 'crm_main_information.crm_id')
            ->leftJoin('crm_lead_information', 'crms.id', '=', 'crm_lead_information.crm_id')
            ->count();
    }

    public function transform($request)
    {
        $data = array();

        if (!empty($request['array'])) {
            foreach ($request['array'] as $key => $value) {
                $id = $value->id;
                foreach ($this->columns as $val){
                    $nestedData[$val] = view('meganet.shared.table.column',[
                        'dir' => '/crm/editar/'. $value->crm_id,
                        'value' => $value,
                        'column' => $val,
                    ])->toHtml();
                }

                $nestedData['action'] = view('meganet.shared.table.actions',[
                    'id' => $id,
                    'module' => 'crm',
                    'group' => 'crm',
                    'submodule' => 'crm'
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
