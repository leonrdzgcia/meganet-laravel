<?php


namespace App\Http\Controllers\Module\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientVozService;
use App\Http\HelpersModule\module\client\ClientVozServiceDatatableHelper;
use App\Http\Requests\module\client\ClientVozServiceCreateRequest;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientVozServiceController extends Controller
{
    private $helper;

    public function __construct(ClientVozServiceDatatableHelper $helper)
    {
        $model = 'ClientVozService';
        $this->data['url'] = 'meganet.module' . $model;
        $this->data['module'] = 'ClientVozService';
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
    public function store(ClientVozServiceCreateRequest $request, $idClient)
    {
        $input = defined($this->data['model'] . '::MULTIPLE_RELATIONS') ?
            $request->except(collect($this->data['model']::MULTIPLE_RELATIONS)->keys()->toArray()) :
            $request->all();


        // $client = Client::find($idClient);
        // if ($client->client_main_information->estado != 'Activo') {
        //     $client->client_main_information()->update(['estado' => 'Activo']);
        // };

        $input['client_id'] = $idClient;
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
    public function show(ClientVozService $clientInternetService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ClientInternetService $clientInternetService
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientVozService $clientInternetService)
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
    public function update(ClientVozServiceCreateRequest $request, $id)
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
