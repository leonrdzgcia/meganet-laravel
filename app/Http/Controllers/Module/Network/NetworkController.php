<?php

namespace App\Http\Controllers\Module\Network;


use App\Jobs\CreateNetWorkIpRowsJob;
use App\Models\Network;
use App\Models\NetworkIp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Http\HelpersModule\module\network\NetworkDatatableHelper;
use App\Http\Requests\module\network\NetworkCreateRequest;
use App\Http\Requests\module\network\NetworkUpdateRequest;
use App\Http\Controllers\Module\Network\Ipv4CalculatorController;


class NetworkController extends Controller
{
    private $helper;
    public function __construct(NetworkDatatableHelper $helper)
    {
        $model = 'Network';
        $this->data['url'] = 'meganet.module.' . Str::lower($model);
        $this->data['module'] = $model;
        $this->data['model'] = 'App\Models\\'.$model;
        $this->data['group'] = 'network';
        $this->helper = $helper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['notifications'] = $this->userNotification();
        $this->includeLibraryDinamic($this->data['model']);
        return view($this->data['url'] . '.index',$this->data);
    }

    public function success(){
        $message =  'Grupo de Ip Creado Correctamente';
        return redirect('/red/ipv4/listar')->with(['message' => $message]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['notifications'] = $this->userNotification();
        $this->includeLibraryDinamic($this->data['model']);
        return view($this->data['url'] . '.add',$this->data);
    }

    public function store(NetworkCreateRequest $request)
    {
        $input = defined($this->data['model'] . '::MULTIPLE_RELATIONS') ?
            $request->except(collect($this->data['model']::MULTIPLE_RELATIONS)->keys()->toArray()) :
            $request->all();

        $model = $this->data['model']::create($input);
        $this->saveRelationMultipleIfExist($this->data['model'], $model, $request);

        $Ipv4Calculator = new Ipv4CalculatorController();
        $ips = $Ipv4Calculator->createIpInNetwork($request->network, $request->bm);

      //TODO agregar pasar a segundo plano.
        CreateNetWorkIpRowsJob::dispatchAfterResponse($model,$ips);

        return $model;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NetworkUpdateRequest $request, $id)
    {
        $model = $this->data['model']::find($id);
        $input = defined($this->data['model'] . '::MULTIPLE_RELATIONS') ?
            $request->except(collect($this->data['model']::MULTIPLE_RELATIONS)->keys()->toArray()) :
            $request->all();
        $this->saveRelationMultipleIfExist($this->data['model'], $model, $request,'sync');
        return $model->update($input);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        NetworkIp::where('network_id','=',$id)->delete();

        $this->data['model']::findOrFail($id)->delete();
        return redirect()->back()->with('message',$this->data['module'] . ' Eliminado Correctamente');
    }

    public function table(Request $request)
    {
        return $this->helper->fetch_datatable_data($request);
    }

    public function getIpByNetwork(Request $request, $network_id)
    {
        $offset = 0;
        $limit = 50;
        if (isset($request->page) && $request->page > 0) {
            $offset = ($request->page - 1) * $limit;
        }

        return NetworkIp::where('network_id', $network_id)
            ->skip($offset)
            ->take($limit)
            ->get();
    }
}
