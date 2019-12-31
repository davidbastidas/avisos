<?php

namespace App\Http\Controllers;

use App\Agenda;
use App\Avisos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function getAvancePorGestor(Request $request){
    $agendas = Agenda::where('fecha', 'LIKE', DB::raw("'%$request->fecha%'"));
    if($request->has('delegacion_filtro')){
      $delegacion_filtro = $request->delegacion_filtro;
      if($delegacion_filtro != 0){
        $agendas = $agendas->where('delegacion_id', $delegacion_filtro);
      }
    }
    $agendas = $agendas->get();

    $arrayAgendas = [];
    $count = 0;
    $stringIn = '';
    foreach ($agendas as $agenda) {
      $arrayAgendas[] = $agenda->id;
      if($count == 0){
        $stringIn = $agenda->id;
        $count++;
      } else {
        $stringIn .= ',' . $agenda->id;
      }
    }

    $gestores = [];
    if(count($arrayAgendas) > 0){
      $gestores = Avisos::select(
              DB::raw("a.gestor_id"),
              DB::raw("u.nombre"),
              DB::raw("(select count(1) from avisos ar where a.gestor_id = ar.gestor_id and ar.estado > 1 and ar.agenda_id in ($stringIn)) as realizados"),
              DB::raw("(select count(1) from avisos ar where a.gestor_id = ar.gestor_id and ar.estado = 1 and ar.agenda_id in ($stringIn)) as pendientes")
          )
          ->from(DB::raw('avisos a'))
          ->join('usuarios as u', 'u.id', '=', 'a.gestor_id')
          ->whereIn('a.agenda_id', $arrayAgendas);

      if($request->has('gestor_filtro')){
        $gestor_filtro = $request->gestor_filtro;
        if($gestor_filtro != 0){
          $gestores = $gestores->where('a.gestor_id', $gestor_filtro);
        }
      }
      if($request->has('estados_filtro')){
        $estados_filtro = $request->estados_filtro;
        if($estados_filtro != 0){
          if($estados_filtro == 2){
            $gestores = $gestores->where('a.estado', '>', 1);
          }else{
            $gestores = $gestores->where('a.estado', $estados_filtro);
          }
        }
      }
      $gestores = $gestores->groupBy('a.gestor_id', 'u.nombre', 'realizados', 'pendientes')
                            ->orderBy('u.nombre')->get();
    }

    return response()->json([
        'gestores' => $gestores
    ]);
  }

  public function getAvanceDiario(Request $request){
    $agendas = Agenda::where('fecha', 'LIKE', DB::raw("'%$request->fecha%'"));
    if($request->has('delegacion_filtro')){
      $delegacion_filtro = $request->delegacion_filtro;
      if($delegacion_filtro != 0){
        $agendas = $agendas->where('delegacion_id', $delegacion_filtro);
      }
    }
    $agendas = $agendas->get();

    $arrayAgendas = [];
    foreach ($agendas as $agenda) {
      $arrayAgendas[] = $agenda->id;
    }

    $pendientes = 0;
    $resueltos = 0;
    if(count($arrayAgendas) > 0){
      $pendientes = Avisos::where('estado','1')->whereIn('agenda_id', $arrayAgendas);
      $resueltos = Avisos::where('estado','2')->whereIn('agenda_id', $arrayAgendas);
      if($request->has('gestor_filtro')){
        $gestor_filtro = $request->gestor_filtro;
        if($gestor_filtro != 0){
          $pendientes = $pendientes->where('gestor_id', $gestor_filtro);
          $resueltos = $resueltos->where('gestor_id', $gestor_filtro);
        }
      }
      $pendientes = $pendientes->count();
      $resueltos = $resueltos->count();
    }

    return response()->json([
        'pendientes' => $pendientes,
        'resueltos' => $resueltos
    ]);
  }

  public function getPointMapGestores(Request $request){
    $agendas = Agenda::where('fecha', 'LIKE', DB::raw("'%$request->fecha%'"));
    if($request->has('delegacion_filtro')){
      $delegacion_filtro = $request->delegacion_filtro;
      if($delegacion_filtro != 0){
        $agendas = $agendas->where('delegacion_id', $delegacion_filtro);
      }
    }
    $agendas = $agendas->get();

    $arrayAgendas = [];
    $count = 0;
    $stringIn = '';
    foreach ($agendas as $agenda) {
      $arrayAgendas[] = $agenda->id;
      if($count == 0){
        $stringIn = $agenda->id;
        $count++;
      } else {
        $stringIn .= ',' . $agenda->id;
      }
    }

    $gestores = [];
    if(count($arrayAgendas) > 0){
      $gestores = Avisos::select(
              DB::raw("u.nombre"),
              DB::raw("(select ar.latitud from avisos ar where a.gestor_id = ar.gestor_id and ar.agenda_id in ($stringIn) order by ar.orden_realizado desc limit 1) as lat"),
              DB::raw("(select ar.longitud from avisos ar where a.gestor_id = ar.gestor_id and ar.agenda_id in ($stringIn) order by ar.orden_realizado desc limit 1) as lon")
          )
          ->from(DB::raw('avisos a'))
          ->join('usuarios as u', 'u.id', '=', 'a.gestor_id')
          ->whereIn('a.agenda_id', $arrayAgendas);
      if($request->has('gestor_filtro')){
        $gestor_filtro = $request->gestor_filtro;
        if($gestor_filtro != 0){
          $gestores = $gestores->where('a.gestor_id', $gestor_filtro);
        }
      }
      if($request->has('estados_filtro')){
        $estados_filtro = $request->estados_filtro;
        if($estados_filtro != 0){
          if($estados_filtro == 2){
            $gestores = $gestores->where('a.estado', '>', 1);
          }else{
            $gestores = $gestores->where('a.estado', $estados_filtro);
          }
        }
      }
      $gestores = $gestores->groupBy('u.nombre', 'lat', 'lon')->get();
    }

    return response()->json([
      'gestores' => $gestores
    ]);
  }
}
