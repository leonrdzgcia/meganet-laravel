<?php

namespace App\Http\Controllers\Module\Crm;

use App\Http\Controllers\Controller;
use App\Http\Requests\module\crm\CrmUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CrmInformationController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CrmUpdateRequest $request, $id)
    {
        $crm = \App\Models\Crm::find($id);
        $beforeCrmMainInformation = $crm->crm_main_information()->first()->toArray();
        $beforeCrmLeadInformation = $crm->crm_lead_information()->first()->toArray();

        $this->saveSingleRelationIfExist('App\Models\Crm', $crm, $request);
        $crm->fresh();

        $crm->log_activities()->create([
            'user_id' => Auth::user()->id,
            'type' => 'updated_crm',
            'data' => json_encode([
                'crm' => $crm->first()->toArray(),
                'crm_main_information' => array_diff($crm->crm_main_information()->first()->toArray(), $beforeCrmMainInformation),
                'crm_lead_information' => array_diff($crm->crm_lead_information()->first()->toArray(), $beforeCrmLeadInformation),
            ])
        ]);
        return $crm;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
