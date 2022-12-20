<?php


namespace App\Http\Controllers\Module\Mapas;


use App\Http\Controllers\Controller;

class MapasController extends Controller
{
    public function index(){
        return view('meganet.module.mapas.index');
    }
}
