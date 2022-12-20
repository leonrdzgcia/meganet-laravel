<?php

namespace App\Http\Controllers\Module\Plan;

use App\Http\Controllers\Controller;
use App\Http\HelpersModule\module\planes\BundleDatatableHelper;
use App\Http\Requests\module\plan\BundleCreateRequest;
use App\Http\Requests\module\plan\BundleUpdateRequest;
use App\Models\Bundle;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    private $helper;

    public function __construct(BundleDatatableHelper $helper)
    {
        $model = 'Bundle';
        $this->data['url'] = 'meganet.module.paquetes';
        $this->data['module'] = 'paquetes';
        $this->data['model'] = 'App\Models\\' . $model;
        $this->data['group'] = 'plan';
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

    public function success($id){
        $message =  'Plan de Paquetes ' . ($id == 'null' ? 'Creado' : 'Actualizado') . ' Correctamente';
        return redirect()->route('paquetes')->with(['message' => $message]);
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
    public function store(BundleCreateRequest $request)
    {
        $input = defined($this->data['model'] . '::MULTIPLE_RELATIONS') ?
            $request->except(collect($this->data['model']::MULTIPLE_RELATIONS)->keys()->toArray()) :
            $request->all();

        $planes = ['planes_internet', 'planes_voz', 'planes_custom'];
        $input = collect($input)->except($planes)->toArray();

        $bundle = $this->data['model']::create($input);
        foreach ($planes as $value) {
            foreach (collect($request->$value)->groupBy("value") as $key => $val) {
                $bundle->$value()->attach($key, ['cant' => count($val)]);
            }
        }
        $this->saveRelationMultipleIfExist($this->data['model'], $bundle, $request);

        return $bundle;
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

        return view($this->data['url'] . '.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BundleUpdateRequest $request, $id)
    {
        $model = $this->data['model']::find($id);
        $input = defined($this->data['model'] . '::MULTIPLE_RELATIONS') ?
            $request->except(collect($this->data['model']::MULTIPLE_RELATIONS)->keys()->toArray()) :
            $request->all();
        $planes = ['planes_internet', 'planes_voz', 'planes_custom'];
        $input = collect($input)->except($planes)->toArray();

        $this->saveRelationMultipleIfExist($this->data['model'], $model, $request, 'sync');

        foreach ($planes as $value) {
            $model->$value()->detach();
            foreach (collect($request->$value)->groupBy("value") as $key => $val) {
                $model->$value()->attach($key, ['cant' => count($val)]);
            }
        }
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
        $this->data['model']::findOrFail($id)
            ->delete();
        return redirect()->back()->with('message', $this->data['module'] . ' Eliminado Correctamente');
    }

    public function table(Request $request)
    {
        return $this->helper->fetch_datatable_data($request, $this->data['model']);
    }
}
