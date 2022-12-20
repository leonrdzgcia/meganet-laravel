<?php

namespace App\Http\Controllers\Module\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->data['url'] = 'meganet.module.tickets';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     $this->data['notifications'] = $this->userNotification();
     return view($this->data['url'] . '.dashboard', $this->data);
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
    public function update(Request $request, $id)
    {
        //
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

    public function getTicketAssignedToMe(Request $request)
    {
        $estado = $request->value;
        if ($estado != 'Todo') {
            $ticketAsignedToMe = Ticket::where('customer_lead', $this->userAutenticated()
                ->id)
                ->where('estado', $estado)
                ->orderBy('priority')->get();
            return $ticketAsignedToMe;
        }
        $ticketAsignedToMe = Ticket::where('customer_lead', $this->userAutenticated()
            ->id)
            ->orderBy('priority')->get();
        return $ticketAsignedToMe;
    }

    public function getTicketAssignedTo(Request $request)
    {
        $array = [];
        $estado = $request->value;
        if ($estado != 'Todo') {
            $ticketAsigneds = Ticket::select(DB::raw('count(*), assigned_to'))
                ->with('assign')
                ->where('estado', $estado)
                ->groupBy('assigned_to')
                ->get();
            $ticketCount = DB::table('tickets')->count();
            foreach ($ticketAsigneds as $key => $ticketAsigned) {
                $array[$key]['asignado']  = $ticketAsigned['assign']['name'];
                $array[$key]['cantidad']  = $ticketAsigned['count(*)'];
                $array[$key]['porcentaje'] =  $ticketAsigned['count(*)'] * 100 / $ticketCount;
            }
            return $array;
        }
        $ticketAsigneds = Ticket::select(DB::raw('count(*), assigned_to'))
            ->with('assign')
            ->groupBy('assigned_to')
            ->get();
        $ticketCount = DB::table('tickets')->count();
        foreach ($ticketAsigneds as $key => $ticketAsigned) {
            $array[$key]['asignado']  = $ticketAsigned['assign']['name'];
            $array[$key]['cantidad']  = $ticketAsigned['count(*)'];
            $array[$key]['porcentaje'] =  $ticketAsigned['count(*)'] * 100 / $ticketCount;
        }
        return $array;
    }

    public function getStatisticsForTarjetsByStatus()
    {
        $array = [
            "Nuevo" => [
                "estado" => "Nuevo",
                "total" => 0,
                "time_human" => "hace unos segundos",
                "icon" => "bx bx-id-card",
                "link" => "/tickets/abiertos",
                "porcent" => "0.0",
            ],
            "Trabajo en curso" =>  [
                "estado" => "Trabajo en curso",
                "total" => 0,
                "time_human" => "hace unos segundos",
                "icon" => "bx bx-menu-alt-left",
                "link" => "/tickets/abiertos",
                "porcent" => "0.0",
            ],
            "Resueltos" => [
                "estado" => "Resueltos",
                "total" => 0,
                "time_human" => "hace unos segundos",
                "icon" => "bx bx-list-check",
                "link" => "/tickets/cerrados",
                "porcent" => "0.0",
            ],
            "Esperando al agente" => [
                "estado" => "Esperando al agente",
                "total" => 0,
                "time_human" => "hace unos segundos",
                "icon" => "bx bx-user",
                "link" => "/tickets/abiertos",
                "porcent" => "0.0",
            ]
        ];

        $ticket_infos = Ticket::groupBy('estado')->select('estado', DB::raw('count(*) as total'))->get();
        $ticketCount = DB::table('tickets')->count();

        foreach ($ticket_infos as $ticket_info) {
            $array[$ticket_info['estado']]['total']  = $ticket_info['total'];
            $array[$ticket_info['estado']]['time_human']  = $ticket_info['time_human'];
            $array[$ticket_info['estado']]['porcent']  =  $ticket_info['total'] * 100 / $ticketCount;
        }

        return $array;
    }
}
