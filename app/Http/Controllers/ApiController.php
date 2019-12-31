<?php

namespace App\Http\Controllers;

use App\Avisos;
use App\Anomalias;
use App\Resultados;
use App\EntidadesPagos;
use App\ObservacionesRapidas;
use App\Usuarios;
use App\Log;
use App\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ApiController extends Controller
{
  public function login(Request $request)
  {
    $response = null;
    $usuarios = Usuarios::where('nickname', '=', $request->user)->where('contrasena', '=', $request->password)->first();
    if(isset($usuarios->id)){
      $response = array(
        'estado' => true,
        'nombre' => $usuarios->nombre,
        'nickname' => $usuarios->nickname,
        'tipo' => $usuarios->tipo_id,
        'fk_delegacion' => $usuarios->delegacion_id,
        'fk_id' => $usuarios->id
      );
    } else {
      $response = array(
        'estado' => false
      );
    }

    return $response;
  }
  public function getAvisos(Request $request)
  {
    $arrayAvisos = [];
    $arrayAnomalias = [];
    $arrayResultados = [];
    $arrayEntidades = [];
    $arrayObservacionesRapidas = [];
    $arrayFINAL = [];
    $collection = null;

    $fechaHoy = Carbon::now()->format('Y-m-d');
    $avisos = Avisos::select('agenda_id')
                    ->where('gestor_id', '=', $request->user)
                    ->where('estado', '=', '1')
                    ->groupBy('agenda_id')->get();
    $agendas = Agenda::where('fecha', '<=', "'$fechaHoy'");
    foreach ($avisos as $aviso) {
      $agendas = $agendas->orWhere('id', $aviso->agenda_id);
    }
    $agendas = $agendas->get();

    $arrayIn = array();
    foreach ($agendas as $agenda) {
      $arrayIn[] = $agenda->id;
    }

    $avisos = Avisos::where('gestor_id', '=', $request->user)
                    ->where('estado', '=', '1')
                    ->whereIn('agenda_id', $arrayIn)->get();
    foreach ($avisos as $aviso) {
      array_push($arrayAvisos, (object) array(
        'id' => $aviso->id,
        'tipo_visita' => $aviso->tipo_visita,
        'municipio' => $aviso->municipio,
        'localidad' => $aviso->localidad,
        'barrio' => $aviso->barrio,
        'direccion' => $aviso->direccion,
        'cliente' => $aviso->cliente,
        'deuda' => $aviso->deuda,
        'factura_vencida' => $aviso->factura_vencida,
        'nic' => $aviso->nic,
        'nis' => $aviso->nis,
        'medidor' => $aviso->medidor,
        'tarifa' => $aviso->tarifa,
        'fecha_limite_compromiso' => $aviso->compromiso
      ));
    }

    $anomalias = Anomalias::all();
    foreach ($anomalias as $anomalia) {
      array_push($arrayAnomalias, (object) array(
        'id' => $anomalia->id,
        'nombre' => $anomalia->nombre
      ));
    }

    $resultados = Resultados::where('estado', '=', '1')->get();
    foreach ($resultados as $resultado) {
      array_push($arrayResultados, (object) array(
        'id' => $resultado->id,
        'nombre' => $resultado->nombre
      ));
    }

    $entidades = EntidadesPagos::all();
    foreach ($entidades as $entidad) {
      array_push($arrayEntidades, (object) array(
        'id' => $entidad->id,
        'nombre' => $entidad->nombre
      ));
    }

    $observaciones = ObservacionesRapidas::all();
    foreach ($observaciones as $observacion) {
      array_push($arrayObservacionesRapidas, (object) array(
        'id' => $observacion->id,
        'nombre' => $observacion->nombre
      ));
    }

    array_push($arrayFINAL, (object) array(
      'estado' => true,
      'visitas' => $arrayAvisos,
      'anomalias' => $arrayAnomalias,
      'resultados' => $arrayResultados,
      'entidades' => $arrayEntidades,
      'observaciones_rapidas' => $arrayObservacionesRapidas
    ));
    $collection = new Collection($arrayFINAL);
    return $collection;
  }

  public function actualizarAviso(Request $request)
  {
    $response = null;
    if($request->user){
      $aviso = Avisos::where('id', '=', $request->id)->where('estado', '=', '1')->first();
      if(isset($aviso->id)){
        if($request->resultado == 0){
          $request->resultado = null;
        }
        if($request->anomalia == 0){
          $request->anomalia = null;
        }
        if($request->entidad_recaudo == 0){
          $request->entidad_recaudo = null;
        }
        if($request->observacion_rapida == 0){
          $request->observacion_rapida = null;
        }
        if($request->fecha_compromiso != ""){
          $request->fecha_compromiso = Carbon::createFromFormat('d/m/Y', $request->fecha_compromiso)->format('Y-m-d');
        } else if($request->fecha_compromiso == ""){
          $request->fecha_compromiso = null;
        }
        if($request->fecha_pago != ""){
          $request->fecha_pago = Carbon::createFromFormat('d/m/Y', $request->fecha_pago)->format('Y-m-d');
        } else if($request->fecha_pago == ""){
          $request->fecha_pago = null;
        }
        $aviso->resultado_id = $request->resultado;
        $aviso->anomalia_id = $request->anomalia;
        $aviso->entidad_recaudo_id = $request->entidad_recaudo;
        $aviso->fecha_pago = $request->fecha_pago;
        $aviso->fecha_compromiso = $request->fecha_compromiso;
        $aviso->persona_contacto = $request->persona_contacto;
        $aviso->cedula = $request->cedula;
        $aviso->titular_pago = $request->titular_pago;
        $aviso->telefono = $request->telefono;
        $aviso->correo_electronico = $request->correo_electronico;
        $aviso->observacion_rapida = $request->observacion_rapida;
        $aviso->lectura = $request->lectura;
        $aviso->observacion_analisis = $request->observacion_analisis;
        $aviso->latitud = $request->latitud;
        $aviso->longitud = $request->longitud;
        $aviso->fecha_recibido = $request->fecha_realizado;
        $aviso->fecha_recibido_servidor = Carbon::now();
        $aviso->estado = 2;
        $aviso->orden_realizado = $request->orden_realizado;

        $aviso->save();

        try {
          /*$logSeg = new Log();
          $logSeg->log = '' . $request;
          $logSeg->aviso_id = $aviso->id;
          $logSeg->save();*/

          if($request->foto != null || $request->foto != ""){
            //decode base64 string
            $image = base64_decode($request->foto);

            $archivo = $aviso->id . '.png';
            \File::put(config('myconfig.ruta_fotos') . $archivo, $image);
          }
        } catch (\Exception $e) {
          $logSeg = new Log();
          $logSeg->log = '' . $e;
          $logSeg->aviso_id = $aviso->id;
          $logSeg->save();
        } finally {

        }

        $response = array(
          'estado' => true
        );
      } else {
        $response = array(
          'estado' => true
        );
      }
    } else {
      $response = array(
        'estado' => false
      );
    }

    return $response;
  }
}
