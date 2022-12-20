<?php


namespace App\Http\HelpersModule\module\administration\colony;


use App\Models\Colony;
use App\Models\Module;
use Illuminate\Support\Arr;

class ColonyDatatableHelper
{
    private $model, $columns;

    public function __construct()
    {
        $this->model = Colony::class;
        $moduleName = 'Colony';
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
        return $this->model::select('*')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    }

    public function searching_query($start, $limit, $order, $dir, $search)
    {
        return $this->model::filters($this->columns, $search)
            ->select('*')
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

        $type_modal_edit = $this->includeButtonEditTypeModalIfIsRequested($request)
            ? '_type_modal' : '';

        if (!empty($request['array'])) {
            foreach ($request['array'] as $key => $value) {
                $id = $value->id;
                foreach ($this->columns as $val) {
                    $nestedData[$val] = $value->$val;
                }

                $info = [
                    'id' => $id,
                    'module' => 'Colony',
                    'group' => 'colony',
                    'submodule' => 'colony'
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
