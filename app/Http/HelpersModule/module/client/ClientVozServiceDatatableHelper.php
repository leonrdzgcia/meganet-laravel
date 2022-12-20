<?php


namespace App\Http\HelpersModule\module\client;

use App\Models\ClientVozService;
use App\Models\Module;
use Illuminate\Support\Arr;

class ClientVozServiceDatatableHelper
{
    private $model;
    private $columns;
    public function __construct()
    {
        $this->model = ClientVozService::class;
        $moduleName = 'ClientVozService';
        $this->columns = Module::where('name', $moduleName)->first()->columnsDatatable->pluck('name')->toArray();
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
            $array = $this->ordering_query($start,$limit,$order,$dir,$idModule);
        } else {
            $search = $request->input('search.value');

            $array = $this->searching_query($start,$limit,$order,$dir,$search, $idModule);

            $totalFiltered = $this->filtering_query($search,$idModule);
        }

        $param_resource = collect(['array' => $array,'totalData' => $totalData,'totalFiltered' => $totalFiltered,'request' => $request]);
        return $this->transform($param_resource);
    }

    public function count($idModule)
    {
        return $this->model::where('client_id', $idModule)
        ->count();
    }


    public function ordering_query($start, $limit, $order, $dir, $idModule)
    {
        return $this->model::select('*')
            ->where('client_id', $idModule)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }

    public function searching_query($start, $limit, $order, $dir, $search, $idModule)
    {
        return $this->model::filters($this->columns, $search)
            ->select('*')
            ->where('client_id', $idModule)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }


    public function filtering_query($search, $idModule)
    {
        return $this->model::filters($this->columns, $search)
            ->where('client_id', $idModule)
            ->count();
    }

    public function transform($request)
    {
        $data = array();

        $type_modal_edit = $this->includeButtonEditTypeModalIfIsRequested($request)
            ? '_type_modal' : '';

        if (!empty($request['array'])) {
            foreach ($request['array'] as $key => $value) {
                $id = $value->id;
                foreach ($this->columns as $val){
                    $nestedData[$val] = $value->$val;
                }

                $info = [
                    'id' => $id,
                    'module' => 'ClientVozService',
                    'group' => 'client_service_voz',
                    'submodule' => 'client'
                ];

                if (!$value->client_bundle_service_id) {
                    if ($type_modal_edit) $info = Arr::add($info, 'modal', $request['request']->modal);
                    $nestedData['action'] = view('meganet.shared.table.actions' . $type_modal_edit, $info)->toHtml();
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

    public function includeButtonEditTypeModalIfIsRequested($request)
    {
        return isset($request['request']->modal);
    }
}
