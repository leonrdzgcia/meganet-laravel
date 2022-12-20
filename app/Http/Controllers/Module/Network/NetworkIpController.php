<?php

namespace App\Http\Controllers\Module\Network;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Http\HelpersModule\module\network\NetworkIpDatatableHelper;
use App\Http\Requests\module\network\NetworkIpCreateRequest;
use App\Http\Requests\module\network\NetworkIpUpdateRequest;


class NetworkIpController extends Controller
{
    private $helper;

    public function __construct(NetworkIpDatatableHelper $helper)
    {
        $model = 'NetworkIp';
        $this->data['url'] = 'meganet.module.' . Str::lower($model);
        $this->data['module'] = $model;
        $this->data['model'] = 'App\Models\\' . $model;
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
        return view($this->data['url'] . '.index', $this->data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->data['notifications'] = $this->userNotification();
        $this->includeLibraryDinamic($this->data['model']);
        $this->data['id'] = $id;

        return view($this->data['url'] . '.ver', $this->data);
    }

    public function success()
    {
        return redirect('/red/ipv4/listar');
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

    public function store(NetworkIpCreateRequest $request)
    {
        $input = defined($this->data['model'] . '::MULTIPLE_RELATIONS') ?
            $request->except(collect($this->data['model']::MULTIPLE_RELATIONS)->keys()->toArray()) :
            $request->all();

        $model = $this->data['model']::create($input);
        $this->saveRelationMultipleIfExist($this->data['model'], $model, $request);

        return $model;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(NetworkIpUpdateRequest $request, $id)
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->data['model']::findOrFail($id)->delete();
        return redirect()->back()->with('message', $this->data['module'] . ' Eliminado Correctamente');
    }

    public function table(Request $request)
    {
        return $this->helper->fetch_datatable_data($request);
    }
}
