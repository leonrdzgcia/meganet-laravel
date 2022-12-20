<?php

namespace App\Http\Controllers\Module\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\module\client\ClientInternetServiceCreateRequest;
use App\Models\ClientInternetService;
use App\Http\HelpersModule\module\client\ClientInternetServiceDatatableHelper;
use Illuminate\Http\Request;
use App\Http\Traits\RouterConnection;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use App\Models\NetworkIp;

class ClientInternetServiceController extends Controller
{
    use RouterConnection;
    private $helper;

    public function __construct(ClientInternetServiceDatatableHelper $helper)
    {
        $model = 'ClientInternetService';
        $this->data['url'] = 'meganet.module' . $model;
        $this->data['module'] = 'ClientInternetService';
        $this->data['model'] = 'App\Models\\' . $model;
        $this->helper = $helper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->includeLibraryDinamic($this->data['model']);
        return view($this->data['url'] . '.add', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientInternetServiceCreateRequest $request, $idClient)
    {
        $input = defined($this->data['model'] . '::MULTIPLE_RELATIONS') ?
            $request->except(collect($this->data['model']::MULTIPLE_RELATIONS)->keys()->toArray()) :
            $request->all();

        $input['client_id'] = $idClient;
        // $ip = $this->setIpToClientByAssignmentMethod($input);
        // $input['ipv4'] = NetworkIp::where('ip', $ip)->first()->id;
        //TODO arreglar que desde la migracion obtenga  0 por defecto
        $input['cost_activation'] = $input['cost_activation'] == null ? 0 : $input['cost_activation'];
        $model = $this->data['model']::create($input);
        $this->saveRelationMultipleIfExist($this->data['model'], $model, $request);       
        return $model;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ClientInternetService $clientInternetService
     * @return \Illuminate\Http\Response
     */
    public function show(ClientInternetService $clientInternetService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ClientInternetService $clientInternetService
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientInternetService $clientInternetService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ClientInternetService $clientInternetService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = $this->data['model']::find($id);
        $input = defined($this->data['model'] . '::MULTIPLE_RELATIONS') ?
            $request->except(collect($this->data['model']::MULTIPLE_RELATIONS)->keys()->toArray()) :
            $request->all();
        $this->saveRelationMultipleIfExist($this->data['model'], $model, $request, 'sync');
        return $model->update($input);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ClientInternetService $clientInternetService
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
       $this->data['model']::findOrFail($id)->delete();
       return redirect()->back()->with('message', $this->data['module'] . ' Eliminado Correctamente');
    }

    public function table(Request $request)
    {
        return $this->helper->fetch_datatable_data($request, $this->data['model']);
    }
}
