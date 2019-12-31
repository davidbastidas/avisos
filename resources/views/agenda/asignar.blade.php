@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    @if(count($gestores) > 0)
                        <div class="row">
                            <div class="col-md-10">
                                <h4>Asignar Avisos {{$agendaModel->codigo}} de {{$agendaModel->fecha}}</h4>
                            </div>
                            <div class="col-md-2">
                                <form action="{{route('admin.vaciar.carga')}}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="agenda" value="{{$agenda}}">
                                    <button class="btn btn-danger" type="submit">Vaciar Carga</button>
                                </form>
                            </div>
                        </div>

                        @if(isset($success))
                            <div class="alert alert-success" role="alert">
                                <strong>{{$success}}</strong>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{route('admin.asignar.avisos')}}" method="post">
                                    <input type="hidden" name="agenda" value="{{$agenda}}">
                                    <div class="form-group">
                                        <label>Gestor Cargado</label>
                                        <select name="gestor" class="form-control">
                                            @foreach($gestores as $gestor)
                                                <option value="{{$gestor->gestor}}">{{$gestor->gestor}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Gestor a Asignar</label>
                                        <select name="user" class="form-control">
                                            @foreach($usuarios as $user)
                                                <option value="{{$user->id}}">{{$user->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="btn btn-success mr-2" type="submit">
                                        Asignar Uno
                                    </button>
                                </form>
                                <br>
                                <form action="{{route('admin.asignarall')}}" method="post">
                                    <input type="hidden" name="agenda" value="{{$agenda}}">
                                    <button class="btn btn-outline-info" type="submit">Asignar Todo</button>
                                </form>
                            </div>
                        </div>
                        <hr>
                    @endif

                    <div class="row">
                        <div class="col-md-10">
                            <h4>Lista de Avisos {{$agendaModel->codigo}} de {{$agendaModel->fecha}}</h4>
                        </div>
                    </div>
                    @php
                        $colorBar = 'danger';
                        $porcentaje = 0;
                        if(($pendientes + $realizados) > 0){
                          $porcentaje = round((100 * $realizados) / ($pendientes + $realizados));
                          if($porcentaje < 20){
                            $colorBar = 'danger';
                          } else if($porcentaje >= 20 && $porcentaje < 50){
                            $colorBar = 'warning';
                          } else if($porcentaje >= 50 && $porcentaje < 70){
                            $colorBar = 'info';
                          } else if($porcentaje >= 70 && $porcentaje < 100){
                            $colorBar = 'primary';
                          } else if($porcentaje == 100){
                            $colorBar = 'success';
                          }
                        }
                    @endphp
                    <br>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="wrapper d-flex justify-content-between">
                                <div class="side-left">
                                    <p class="mb-2">Realizados</p>
                                    <p class="display-4 mb-4 font-weight-light text-success">
                                        @if ($realizados == 0)
                                            {{$realizados}}
                                        @else
                                            {{$realizados}}
                                            <i class="mdi mdi-arrow-up"></i>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="wrapper d-flex justify-content-between">
                                <div class="side-left">
                                    <p class="mb-2">Pendientes</p>
                                    <p class="display-4 mb-4 font-weight-light text-danger">
                                        @if ($pendientes == 0)
                                            {{$pendientes}}
                                        @else
                                            {{$pendientes}}
                                            <i class="mdi mdi-arrow-down"></i>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">Avance {{$porcentaje.'%'}}</p>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{$colorBar}}"
                                     role="progressbar" style="width: {{$porcentaje}}%" aria-valuenow="{{$porcentaje}}"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="sr-only">Borrar Masivo</label>
                            @if ($pendientes > 0)
                                <input type="hidden" id="agenda_id" value="{{$agenda}}">
                                <button class="btn btn-danger mb-2" type="submit" id="borrar_masivo">Borrar Masivo
                                </button>
                                <div id="form-hidden" style="display: none;"></div>
                            @endif
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-inline" action="{{route('asignar.avisos',['agenda' => $agenda])}}"
                                  method="get">
                                <label class="sr-only">Gestor</label>
                                <select name="gestor_filtro" class="form-control mb-2 mr-sm-2">
                                    <option value="0">[Todos los Gestores]</option>
                                    @foreach($gestoresAsignados as $gestor)
                                        @foreach ($usuarios as $usuario)
                                            @if ($usuario->id == $gestor->gestor_id)
                                                @if ($gestor_filtro == $usuario->id)
                                                    <option value="{{$usuario->id}}"
                                                            selected>{{$usuario->nombre}}</option>
                                                @else
                                                    <option value="{{$usuario->id}}">{{$usuario->nombre}}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endforeach
                                </select>

                                <label class="sr-only">Estado</label>
                                <select name="estados_filtro" class="form-control mb-2 mr-sm-2">
                                    <option value="0">[Todos los Estados]</option>
                                    <option value="1" @if ($estados_filtro == 1) selected @endif>PENDIENTES</option>
                                    <option value="2" @if ($estados_filtro == 2) selected @endif>REALIZADOS</option>
                                    <option value="3" @if ($estados_filtro == 3) selected @endif>MODIFICADOS</option>
                                </select>

                                <label class="sr-only">NIC</label>
                                <input type="text" class="form-control mb-2 mr-sm-2" name="nic_filtro" placeholder="NIC"
                                       value="{{$nic_filtro}}">

                                <label class="sr-only">MEDIDOR</label>
                                <input type="text" class="form-control mb-2 mr-sm-2" name="medidor_filtro"
                                       placeholder="MEDIDOR" value="{{$medidor_filtro}}">

                                <button class="btn btn-success mb-2" type="submit">Filtrar</button>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table style="width: 100%;text-align:center;font-size: 12px;" class="table-bordered">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%;padding: 10px;">
                                            @if ($pendientes == 0)
                                                #
                                            @else
                                                <input type="checkbox" id="avisos-check-all">
                                            @endif
                                        </th>
                                        <th style="width: 20%;">Gestor</th>
                                        <th style="width: 15%;">Barrio</th>
                                        <th style="width: 10%;">NIC</th>
                                        <th style="width: 10%;">Result.</th>
                                        <th style="width: 10%;">Accion</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $count = 1;
                                    @endphp
                                    @foreach ($avisos as $aviso)
                                        <tr>
                                            <td>
                                                @if ($aviso->estado == 1)
                                                    <input type="checkbox" class="check-avisos" name="avisos[]"
                                                           value="{{ $aviso->id }}">
                                                @else
                                                    {{$count++}}
                                                @endif
                                            </td>
                                            <td>{{ $aviso->usuario->nombre }}</td>
                                            <td>{{ $aviso->barrio }}</td>
                                            <td>{{ $aviso->nic }}</td>
                                            <td>
                                                @if (isset($aviso->resultado->nombre))
                                                    {{ $aviso->resultado->nombre }}
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{route('aviso.editar', ['aviso' => $aviso->id])}}">
                                                    <button style="margin-bottom: 8px"
                                                            class="btn-sm btn btn-outline-primary">
                                                        Ver <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                </form>
                                                @if ($aviso->estado == 1)
                                                    <form action="{{route('aviso.eliminar', ['aviso' => $aviso->id])}}">
                                                        <button style="margin-bottom: 8px"
                                                                class="btn-sm btn btn-outline-danger">
                                                            Eliminar <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                {{ $avisos->appends([])->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
