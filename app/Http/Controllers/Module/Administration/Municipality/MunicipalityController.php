<?php

namespace App\Http\Controllers\Module\Administration\Municipality;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\HelpersModule\module\administration\municipality\MunicipalityDatatableHelper;

class MunicipalityController extends Controller
{
    private $helper;

    public function __construct(MunicipalityDatatableHelper $helper)
    {
        $this->data['model'] = 'App\Models\Municipality';
        $this->data['url'] = 'meganet.module.administration.municipality';
        $this->helper = $helper;
    }

    public function index()
    {
        $this->data['notifications'] = $this->userNotification();
        $this->includeLibraryDinamic('Municipality');
        return view('meganet.module.administration.municipality.listar', $this->data);
    }
    
    public function store(Request $request)
    {
        $input = defined($this->data['model'] . '::MULTIPLE_RELATIONS') ?
            $request->except(collect($this->data['model']::MULTIPLE_RELATIONS)->keys()->toArray()) :
            $request->all();

        $model = $this->data['model']::create($input);
        $this->saveRelationMultipleIfExist($this->data['model'], $model, $request);

        return $model;
    }

    public function update(Request $request, $id)
    {
        $model = $this->data['model']::find($id);
        $input = defined($this->data['model'] . '::MULTIPLE_RELATIONS') ?
            $request->except(collect($this->data['model']::MULTIPLE_RELATIONS)->keys()->toArray()) :
            $request->all();
        $this->saveRelationMultipleIfExist($this->data['model'], $model, $request,'sync');
        return $model->update($input);
    }

    public function destroy($id)
    {
        return  $this->data['model']::findOrFail($id)->delete();
    }

    public function table(Request $request)
    {
        return $this->helper->fetch_datatable_data($request);
    }
}
