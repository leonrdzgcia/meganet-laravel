<?php

namespace App\Http\Controllers\Module\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\HelpersModule\module\client\DocumentClientDatatableHelper;
use App\Http\Requests\module\client\DocumentClientUpdateRequest;
use App\Http\Requests\module\client\DocumentClientCreateRequest;
use Illuminate\Support\Str;
use function React\Promise\all;

class DocumentClientController extends Controller
{
    private $helper;
    public function __construct(DocumentClientDatatableHelper $helper)
    {
        $model = 'DocumentClient';
        $this->data['url'] = 'meganet.module.' . Str::lower($model);
        $this->data['module'] = $model;
        $this->data['model'] = 'App\Models\\'.$model;
        $this->helper = $helper;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //  return view($this->data['url'] . '.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->includeLibraryDinamic($this->data['model']);
        return view($this->data['url'] . '.add',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentClientCreateRequest $request, $idClient)
    {
        return Client::findOrFail($idClient)->clientCreateDocument($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentClient  $documentClient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentClient  $documentClient
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->includeLibraryDinamic($this->data['model']);
        $this->data['id'] = $id;

        return view($this->data['url'] . '.edit',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentClient  $documentClient
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentClientUpdateRequest $request, $id)
    {
        $input = $request->except('file');
        if (isset($input['show'])) $input['show'] = ($input['show'] == 'true');

        $model = $this->data['model']::find($id);
        $model->updateDocumentUpload($request->file('file'));

        return $model->update($input);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentClient  $documentClient
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = $this->data['model']::findOrFail($id);

        // Para que el observer de delete funcione, se debe llamar al metodo delete luego del first()
        $model->file()->first()->delete();
        $model->delete();

        return redirect()->back()->with('message',$this->data['module'] . ' Eliminado Correctamente');
    }

    public function table(Request $request)
    {
        return $this->helper->fetch_datatable_data($request, $this->data['model']);
    }
}
