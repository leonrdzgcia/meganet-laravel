<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\ClientMainInformation;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->data['notifications'] = $this->userNotification();
        return view('meganet.module.started-page', $this->data);
    }

    public function getHomeStatisticsForTarjetsByStatus()
    {
        $array = [
            "Online" => [
                "estado" => "Cliente en Linea",
                "total" => 0,
                "time_human" => "hace unos segundos",
                "icon" => "fas fa-exchange-alt",
                "link" => "/cliente/listar",
                "porcent" => "0.0",
            ],
            "ClientNew" =>  [
                "estado" => "Clientes nuevos",
                "total" => 0,
                "time_human" => "hace unos segundos",
                "icon" => "bx bx-user",
                "link" => "/cliente/listar",
                "porcent" => "0.0",
            ],
            "TicketsOpen" => [
                "estado" => "Tickets nuevos/abiertos",
                "total" => 0,
                "time_human" => "hace unos segundos",
                "icon" => "bx bx-list-check",
                "link" => "/tickets/abiertos",
                "porcent" => "0.0",
            ],
            "Devices" => [
                "estado" => "Los dispositivos sin respuesta",
                "total" => 0,
                "time_human" => "hace unos segundos",
                "icon" => "bx bx-plug",
                "link" => "/red/router/listar",
                "porcent" => "0.0",
            ]
        ];

        $client_online_info = ClientMainInformation::groupBy('estado')->select('estado', DB::raw('count(*) as total'))->where('estado', 'Online')->get();
        $clientCount = DB::table('client_main_information')->groupBy('estado')->count();

        $client_nuevo_info = ClientMainInformation::groupBy('estado')->select('estado', DB::raw('count(*) as total'))->where('estado', 'Nuevo')->get();

        $ticket_info = Ticket::groupBy('estado')->select('estado', DB::raw('count(*) as total'))->where('estado', 'Nuevo')->get();
        $ticketCount = DB::table('tickets')->groupBy('estado')->count();

        if (isset($client_online_info[0])) {
            $array['Online']['total']  = $client_online_info[0]['total'];
            $array['Online']['porcent']  =  $client_online_info[0]['total'] * 100 / $clientCount;
        };

        if (isset($client_nuevo_info[0])) {
            $array['ClientNew']['total']  = $client_nuevo_info[0]['total'];
            $array['ClientNew']['porcent']  =  $client_nuevo_info[0]['total'] * 100 / $clientCount;
        };

        if (isset($ticket_info[0])) {
            $array['TicketsOpen']['total']  = $ticket_info[0]['total'];
            $array['TicketsOpen']['porcent']  =  $ticket_info[0]['total'] * 100 / $ticketCount;
        };

        //TODO agregar consulta de los dispisitivos que estan en bd pero se encuentran desconectados

        return $array;
    }

    public function getStatisticsForTextCardInDashBoard()
    {
        //TODO cuando se activen las facturas se activaria esta linea siguiente
        // $invoisesCount = Invoises::where('created_at', '>=',  \Carbon\Carbon::today()->toDateString())->count();
        $transactionsCount = Transaction::where('created_at', '>=',  \Carbon\Carbon::today()->toDateString())->count();
        return ['invoises' => '0', 'transactions' =>  $transactionsCount];
    }

    public function getStatsCardClientInDashBoard()
    {
        $array =  [
            "Total" => [
                "estado" => "Total",
                "total" => 0,
            ],
            "Nuevo" => [
                "estado" => "Nuevo",
                "total" => 0,
            ],
            "Activo" => [
                "estado" => "Activo",
                "total" => 0,
            ],
            "Online" => [
                "estado" => "Online",
                "total" => 0,
            ],
            "En línea hoy" => [
                "estado" => "En línea hoy",
                "total" => 0,
            ],
            "Bloqueado" => [
                "estado" => "Bloqueado",
                "total" => 0,
            ],
            "Inactivo" => [
                "estado" => "Inactivo",
                "total" => 0,
            ],
            "Añadido ultimo mes" => [
                "estado" => "Añadido ultimo mes",
                "total" => 0,
            ],
            "Añadido ultimo año" => [
                "estado" => "Añadido ultimo año",
                "total" => 0,
            ],
        ];

        $client_status_infos = ClientMainInformation::groupBy('estado')->select('estado', DB::raw('count(*) as total'))->get();
        $client_month = ClientMainInformation::where('created_at', '>=', Carbon::now()->subMonth()->toDateString())->count();
        $client_year = ClientMainInformation::where('created_at', '>=', Carbon::now()->subYear()->toDateString())->count();

        foreach ($client_status_infos as $key => $client_status_info) {
            $array[$client_status_info['estado']]['total']  = $client_status_info['total'];
        }

        $array["Añadido ultimo mes"]['total'] = $client_month;
        $array["Añadido ultimo año"]['total'] = $client_year;
        return $array;
    }

    public function getStatsCardTicketsInDashBoard()
    {
        $array =  [
            "Nuevo" => [
                "estado" => "Nuevo",
                "total" => 0,
            ],
            "Trabajo en curso" => [
                "estado" => "Trabajo en curso",
                "total" => 0,
            ],
            "Resuelto" => [
                "estado" => "Resuelto",
                "total" => 0,
            ],
            "Esperando al agente" => [
                "estado" => "Esperando al agente",
                "total" => 0,
            ],
        ];

        $ticket_status_infos = Ticket::groupBy('estado')->select('estado', DB::raw('count(*) as total'))->get();
        foreach ($ticket_status_infos as $key => $ticket_status_info) {
            $array[$ticket_status_info['estado']]['total']  = $ticket_status_info['total'];
        }
        return $array;
    }

   
}
