<?php

namespace App\Http\HelpersModule\module;

class HelperDatatable {

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
}
