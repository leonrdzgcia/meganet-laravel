<?php

namespace App\Http\Controllers\Module\Crm;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Models\Client;
use App\Models\CrmLeadInformation;
use App\Models\CrmMainInformation;
use App\Models\Module;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\HelpersModule\module\crm\CrmDatatableHelper;
use App\Http\Requests\module\crm\CrmCreateRequest;
use App\Http\Requests\module\crm\CrmUpdateRequest;
use App\Models\Crm;
use Illuminate\Support\Facades\Auth;

class CrmController extends Controller
{
    private $helper;

    public function __construct(CrmDatatableHelper $helper)
    {
        $model = 'Crm';
        $this->data['url'] = 'meganet.module.crm';
        $this->data['module'] = 'Crm';
        $this->data['model'] = 'App\Models\\' . $model;
        $this->data['group'] = 'crm';
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
        return redirect('/crm/editar/'. $id);
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
    public function store(CrmCreateRequest $request)
    {
        $model = $this->data['model']::create($request->only('enable_same_name_or_rfc'))
            ->createMainInformation($request)
            ->createLeadInformation($request);

        $model->log_activities()->create([
            'user_id' => Auth::user()->id,
            'type' => 'create_crm',
            'data' => json_encode([
                'crm' => $model->toArray(),
                'crm_main_information' => $model->crm_main_information()->first()->toArray(),
                'crm_lead_information' => $model->crm_lead_information()->first()->toArray()
            ])
        ]);
        return $model;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['notifications'] = $this->userNotification();
        $this->includeLibraryDinamic($this->data['model']);
        $this->data['id'] = $id;

        $crmLeadInformation = CrmLeadInformation::where('crm_id', $id)->first();
        if ($crmLeadInformation){
            $crmLeadInformation->score++;
            $crmLeadInformation->update();

            $this->data['show'] = $this->getTabs();

            return view($this->data['url'] . '.edit', $this->data);
        }
        return view('meganet.pages.404');
    }

    public function getTabs()
    {
        $tabs = [];
        if ($this->userAutenticated()->hasPermissionTo('crm_information_view_tab_crm') || $this->userAutenticated()->isAdmin()) $tabs[] = 'Informacion';
        if ($this->userAutenticated()->hasPermissionTo('crm_document_view_tab_crm') || $this->userAutenticated()->isAdmin()) $tabs[] = 'Documentos';
        return json_encode($tabs);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function ver($id)
    {
        $this->includeLibraryDinamic($this->data['model']);
        $this->data['id'] = $id;

        return view($this->data['url'] . '.ver', $this->data);
    }

    public function convertToClient($crmId)
    {
        $crm = Crm::find($crmId)->load(['crm_main_information', 'crm_lead_information']);
        $client = Client::create();

        $crmMainInformation = collect($crm->crm_main_information)->only(
            array_merge(['id'], Module::where('name', 'CrmMainInformation')->first()->fields->pluck('name')->toArray())
        )->toArray();

        $util = new HelperController();
        $crmMainInformation['user'] = $util->getGenerateUser();
        $crmMainInformation['discharge_date'] = $crm->crm_main_information->high_date ?? Carbon::now()->toDateString();
        $crmMainInformation['estado'] = 'Nuevo';
        $crmMainInformation['activation_date'] = $crm->crm_lead_information->instalation_date != null ?  $crm->crm_lead_information->instalation_date : Carbon::now()->toDateTimeString();

        $crmAdditionalInformation = ['vendor' => $crm->crm_lead_information->owner_id];

        $client->client_main_information()->create(collect($crmMainInformation)->only(Module::where('name', 'ClientMainInformation')->first()->fields->pluck('name')->toArray())->toArray());
        $client->client_additional_information()->create($crmAdditionalInformation);
        $client->clientCreateClientUser($client);
        $client->clientCreateBalance();
        $client->clientCreateBillingConfiguration();
        $crm->delete();
        return $client->id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = $this->data['model']::findOrFail($id);
        $model->delete();
        return redirect()->back()->with('message', $this->data['module'] . ' Eliminado Correctamente');
    }

    public function table(Request $request)
    {
        return $this->helper->fetch_datatable_data($request, $this->data['model']);
    }

    public function updateLastContacted($id){
        $this->data['model']::findOrFail($id)->crm_lead_information()->update(['last_contacted' => Carbon::now()->toDateString()]);
    }
}
