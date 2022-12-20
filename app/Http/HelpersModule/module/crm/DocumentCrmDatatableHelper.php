<?php


namespace App\Http\HelpersModule\module\crm;

use App\Models\DocumentCrm;
use App\Models\Module;
use Illuminate\Support\Arr;

class DocumentCrmDatatableHelper
{
    private $model;
    private $columns;

    public function __construct()
    {
        $this->model = DocumentCrm::class;
        $moduleName = 'DocumentCrm';
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
        return $this->model::where('crm_id', $idModule)
            ->count();
    }

    public function ordering_query($start, $limit, $order, $dir, $idModule)
    {
        return $this->model::select('*')
            ->where('crm_id', $idModule)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }

    public function searching_query($start, $limit, $order, $dir, $search, $idModule)
    {
        return $this->model::filters($this->columns, $search)
            ->select('*')
            ->where('crm_id', $idModule)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }

    public function filtering_query($search,$idModule)
    {
        return $this->model::filters($this->columns, $search)
            ->where('crm_id', $idModule)
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
                foreach ($this->columns as $val) {
                    $nestedData[$val] = $value->$val;
                }

                $document = null;
                if ($value->file){
                    $document = url($value->file->path);
                }

                $info = [
                    'id' => $id,
                    'module' => 'DocumentCrm',
                    'group' => 'crm_document',
                    'submodule' => 'crm',
                    'document' => $document
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

    public function includeButtonEditTypeModalIfIsRequested($request)
    {
        return isset($request['request']->modal);
    }
}
