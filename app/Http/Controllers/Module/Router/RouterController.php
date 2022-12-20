<?php

namespace App\Http\Controllers\Module\Router;

use App\Models\Mikrotik;
use App\Models\ClientInternetService;
use App\Models\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\HelpersModule\module\router\RouterDatatableHelper;
use App\Http\Requests\module\router\RouterCreateRequest;
use App\Http\Requests\module\router\RouterUpdateRequest;
use Illuminate\Support\Str;

class RouterController extends Controller
{
    private $helper;
    public function __construct(RouterDatatableHelper $helper)
    {
        $model = 'Router';
        $this->data['url'] = 'meganet.module.' . Str::lower($model);
        $this->data['module'] = $model;
        $this->data['model'] = 'App\Models\\'.$model;
        $this->data['group'] = 'router';
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

    public function success($id){
        return redirect('/red/router/editar/'. $id);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RouterCreateRequest $request)
    {
        $input = defined($this->data['model'] . '::MULTIPLE_RELATIONS') ?
            $request->except(collect($this->data['model']::MULTIPLE_RELATIONS)->keys()->toArray()) :
            $request->all();

        $model = $this->data['model']::create($input);
        $this->saveRelationMultipleIfExist($this->data['model'], $model, $request);

        return $model;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Router $router)
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
        $this->data['notifications'] = $this->userNotification();
        $this->includeLibraryDinamic($this->data['model']);
        $this->data['id'] = $id;

        return view($this->data['url'] . '.edit',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RouterUpdateRequest $request, $id)
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
        $mikrotik = Mikrotik::where('router_id', '=', $id)->first();
        if ($mikrotik){
            $mikrotik->delete();
        }
        $clientInternetService = ClientInternetService::where('router_id', '=', $id)->first();
        if ($clientInternetService){
            $clientInternetService->delete();
        }
        $this->data['model']::findOrFail($id)->delete();
        return redirect()->back()->with('message',$this->data['module'] . ' Eliminado Correctamente');
    }

    public function table(Request $request)
    {
        return $this->helper->fetch_datatable_data($request, $this->data['model']);
    }
}
