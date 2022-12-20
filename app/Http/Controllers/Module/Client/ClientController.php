<?php

namespace App\Http\Controllers\Module\Client;

use App\Http\Controllers\Controller;
use App\Jobs\DeletedClientWithServiceJob;
use App\Jobs\PupulateUserColumnsDatatableModuleDefaultsJob;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\HelpersModule\module\client\ClientDatatableHelper;
use App\Http\Requests\module\client\ClientCreateRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class ClientController extends Controller
{
    private $helper;

    public function __construct(ClientDatatableHelper $helper)
    {
        $model = 'Client';
        $this->data['url'] = 'meganet.module.client';
        $this->data['module'] = 'client';
        $this->data['model'] = 'App\Models\\' . $model;
        $this->data['group'] = 'client';
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
        return view($this->data['url'] . '.index', $this->data);
    }

    public function success($id)
    {
        return redirect('/cliente/editar/' . $id);
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
        return view($this->data['url'] . '.add', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientCreateRequest $request)
    {
        $model = $this->data['model']::create();
        $model = $model->clientCreateClientMainInformation($request)
            ->clientCreateClientAdditionalInformation($request);

        $model->clientCreateClientUser($model);
        $model->clientCreateBalance();
        $model->clientCreateReminderConfiguration();
        $model->clientCreateBillingConfiguration();
        return $model;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Client $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Client $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['notifications'] = $this->userNotification();
        $this->includeLibraryDinamic($this->data['model']);
        $this->data['id'] = $id;
        $this->data['show'] = $this->getTabs();
        $client_name = Client::findOrFail($id)->clientFullName();
        $this->data['breadcrumb'] = json_encode([['title' => "Cliente"], ['title' => $client_name, 'active' => "active"]]);
        return view($this->data['url'] . '.edit', $this->data);
    }

    public function getTabs()
    {
        $tabs = [];
        if ($this->userAutenticated()->hasPermissionTo('client_information_view_tab_client') || $this->userAutenticated()->isAdmin()) $tabs[] = 'Informacion';

        // TODO: implementar permisos para document in client
//        if ($this->userAutenticated()->hasPermissionTo('client_document_view_tab_client') || $this->userAutenticated()->isAdmin())
        $tabs[] = 'Documentos';
        if ($this->userAutenticated()->hasPermissionTo('client_service_view_tab_client') || $this->userAutenticated()->isAdmin()) $tabs[] = 'Servicios';
        if ($this->userAutenticated()->hasPermissionTo('client_payroll_view_tab_client') || $this->userAutenticated()->isAdmin()) $tabs[] = 'Facturacion';
        return json_encode($tabs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Client $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Client $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::find($id);
        if ($client){
                foreach ($client->internet_service as $client_internet_service){
                  if($client_internet_service){
                      DeletedClientWithServiceJob::dispatchAfterResponse($client_internet_service);
                  }
                }

            $client->internet_service()->delete();
            $client->voz_service()->delete();
            $client->custom_service()->delete();
            $client->bundle_service()->delete();

            $client->client_main_information()->update(['estado' => 'Cancelado']);
        }

        return redirect()->back()->with('message', Str::title($this->data['module']) . ' Eliminado Correctamente');
    }

    public function table(Request $request)
    {
        return $this->helper->fetch_datatable_data($request, $this->data['model']);
    }

    public function getVal(Request $request, $id)
    {
        $model = $request->model;
        $field = $request->field;

        $data = $model::where('client_id', $id)->selectRaw($field)->first();
        if ($data) return $data->toArray();
    }

    public function getClientDebit($id){

        $client = Client::find($id);
        $amount =   $client->balance()->first()->amount;
        return $amount < 0 ? $amount : 0;
    }
}
