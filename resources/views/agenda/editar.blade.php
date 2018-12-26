@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin">
            <div class="card">
                <form action="{{route('aviso.editar.save')}}" method="POST">
                    {{csrf_field()}}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <center><h4>EDITAR AVISO</h4></center>
                            </div>
                        </div>
                        <br>

                        <input type="hidden" name="aviso_id" value="{{$aviso->id}}">
                        <div class="row">
                            <div class="col-md-2">
                                Nic: {{$aviso->nic}}
                            </div>

                            <div class="col-md-4">
                                Cliente: {{$aviso->cliente}}
                            </div>

                            <div class="col-md-3">
                            </div>

                            <div class="col-md-3">

                                <button style="margin-bottom: 8px"
                                        class="btn-block btn btn-outline-primary" type="submit">
                                    Guardar <i class="mdi mdi-content-save"></i>
                                </button>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Resultado</label>
                                <select class="form-control" name="resultado">
                                    <option value="">Selecciona..</option>
                                    @foreach($resultados as $resultado)
                                        @if($resultado->id == $aviso->resultado_id)
                                            <option value="{{$resultado->id}}"
                                                    selected>{{$resultado->nombre}}</option>
                                        @else
                                            <option
                                                value="{{$resultado->id}}">{{$resultado->nombre}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Anomalia</label>
                                <select class="form-control" name="anomalia">
                                    <option value="">Selecciona..</option>
                                    @foreach($anomalias as $anomalia)
                                        @if($anomalia->id == $aviso->anomalia_id)
                                            <option value="{{$anomalia->id}}"
                                                    selected>{{$anomalia->nombre}}</option>
                                        @else
                                            <option
                                                value="{{$anomalia->id}}">{{$anomalia->nombre}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Recaudo</label>
                                <select class="form-control" name="recaudo">
                                    <option value="">Selecciona..</option>
                                    @foreach($recaudos as $recaudo)
                                        @if($recaudo->id == $aviso->entidad_recaudo_id)
                                            <option value="{{$recaudo->id}}"
                                                    selected>{{$recaudo->nombre}}</option>
                                        @else
                                            <option
                                                value="{{$recaudo->id}}">{{$recaudo->nombre}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Fecha Pago</label>
                                <input class="form-control" type="date" name="fecha_pago" id="fechapagoedit">
                            </div>

                            <div class="col-md-3">
                                <label>Atiende</label>
                                <input class="form-control" name="atiende" required
                                       value="{{$aviso->persona_contacto}}">
                            </div>

                            <div class="col-md-3">
                                <label>Cedula</label>
                                <input class="form-control" type="text" name="cedula" value="{{$aviso->cedula}}">
                            </div>

                            <div class="col-md-3">
                                <label>Titular Pago</label>
                                <input class="form-control" name="titular" value="{{$aviso->titular_pago}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Telefono</label>
                                <input class="form-control" type="number" name="telefono" value="{{$aviso->telefono}}">
                            </div>

                            <div class="col-md-3">
                                <label>Correo electronico</label>
                                <input type="email" class="form-control" name="correo_electronico"
                                       value="{{$aviso->correo_electronico}}">
                            </div>

                            <div class="col-md-3">
                                <label>Observacion Rapida</label>
                                <select class="form-control" name="observacion">
                                    <option value="">Selecciona..</option>
                                    @foreach($observaciones as $observacion)
                                        @if($observacion->id == $aviso->observacion_rapida)
                                            <option value="{{$observacion->id}}"
                                                    selected>{{$observacion->nombre}}</option>
                                        @else
                                            <option
                                                value="{{$observacion->id}}">{{$observacion->nombre}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Lectura</label>
                                <input class="form-control" type="text" name="lectura" value="{{$aviso->lectura}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Observacion Analisis</label>
                                <textarea class="form-control" name="observacion_analisis"
                                          rows="6">{{$aviso->observacion_analisis}}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label>Foto</label>
                                <br>
                                <img src="{{$path}}" height="300px" width="100%">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
