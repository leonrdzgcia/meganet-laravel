<?php

namespace App\Http\Controllers\Module\Client;

use App\Jobs\Mikrotik\CheckMikrotikConection;
use App\Jobs\Mikrotik\MikrotikRemoveClientServiceFromAddressList;
use App\Models\Client;
use App\Models\ClientMainInformation;
use App\Http\Controllers\Controller;
use App\Models\ClientUser;
use Facade\Ignition\DumpRecorder\Dump;
use Illuminate\Http\Request;
use App\Http\Requests\module\client\ClientInformationRequest;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use App\Jobs\Mikrotik\MikrotikCreateAddressList;

class ClientInformationController extends Controller
{
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ClientInformation $clientMainInformation
     * @return \Illuminate\Http\Response
     */
    public function show(ClientInformation $clientMainInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ClientInformation $clientMainInformation
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientInformation $clientMainInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ClientInformation $clientInformation
     * @return \Illuminate\Http\Response
     */
    public function update(ClientInformationRequest $request, $id)
    {
        $client = \App\Models\Client::with('internet_service.router.mikrotik')->findOrFail($id);
        $input = $request->all();
        // Eliminando la fecha de alta del request para que en el actualizar no cambie el campo
        unset($input['discharge_date']);
        // unset($input['user']);

        if ($this->ifEstadoChangeToLocked($request)){
            foreach ($client->internet_service as $clientService) {
                try {
                    Bus::chain([
                        new CheckMikrotikConection($clientService),
                        new MikrotikCreateAddressList($clientService)
                    ])->dispatch();
                } catch (\Exception $exception) {
                    Log::info($exception);
                }
            }
        }

        if ($this->ifEstadoChangeToActive($request)){
            foreach ($client->internet_service as $clientService) {

                try {
                    Bus::chain([
                        new CheckMikrotikConection($clientService),
                        new MikrotikRemoveClientServiceFromAddressList($clientService)
                    ])->dispatch();
                } catch (\Exception $exception) {
                    Log::info($exception);
                }
            }
        }

        return $this->saveSingleRelationIfExist('App\Models\Client', $client, collect($input));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ClientInformation $clientMainInformation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientInformation $clientMainInformation)
    {
        //
    }

    public function getClientWithBalance($id){
        $client = Client::where('id', $id)->with('balance')->first();
        return [
            'name' => $client->clientFullName(),
            'balance' => $client->balance->amount
        ];
    }

    public function getClientTicketsOpen($id){
        $client = Client::where('id', $id)->withCount('tickets_open', 'tickets_closed')->first();
        return [
            'open' => $client->tickets_open_count,
            'closed' => $client->tickets_closed_count
        ];
    }

    public function ifEstadoChangeToLocked($request){
       $input = $request->all();
       return ($input['estado'] =='Bloqueado');
    }

    public function ifEstadoChangeToActive($request){
        $input = $request->all();
        return ($input['estado'] =='Activo');
    }

    public function getClientStatus($id){
        $client = \App\Models\Client::findOrFail($id);
        return $client->client_main_information->estado;
    }
}
