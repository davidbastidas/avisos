@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <center><h3>AGENDAS</h3></center>
                    <br><br>

                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn btn-success btn-block" data-toggle="modal"
                                    data-target="#modal">Nueva Agenda
                            </button>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="modal" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Nueva Agenda</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form style="padding: 0;" action="{{route('agenda.save')}}"
                                          method="POST">
                                        {{csrf_field()}}
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-5">
                                                    <label>Fecha</label>
                                                    <input id="fechaAgenda" type="date"
                                                           class="form-control" name="fecha">
                                                </div>
                                                <div class="col-md-5">
                                                    <label>Delegacion</label>
                                                    <select class="form-control" name="delegacion">
                                                        @foreach($delegaciones as $del)
                                                            <option
                                                                value="{{$del->id}}">{{$del->nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cerrar
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                Guardar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4"></div>
                        <div class="col-md-4" style="top: -20px; display: none">
                            <center><p class="mb-2">Total Avisos</p>
                                <p class="display-3 mb-4 font-weight-light"><span
                                        class="mdi mdi-bell" style="color:#35abde;">
                                                        {$totalAvisos}}
                                                    </span></p>
                            </center>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead>
                            <tr>
                                <th scope="col">Agenda</th>
                                <th scope="col">Delegacion</th>
                                <th scope="col">Responsable</th>
                                <th scope="col">Por Asignar</th>
                                <th scope="col">Pend.</th>
                                <th scope="col">Reali.</th>
                                <th scope="col">Accion</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($agendas as $agenda)
                                <tr>
                                    <td>{{$agenda->fecha}}</td>
                                    <td>
                                        @if($agenda->delegacion == 1)
                                            ATLANTICO NORTE
                                        @else
                                            ATLANTICO SUR
                                        @endif
                                    </td>
                                    <td>{{$agenda->usuario}}</td>
                                    <td>{{$agenda->cargasPendientes}}</td>
                                    <td>{{$agenda->pendientes}}</td>
                                    <td>{{$agenda->realizados}}</td>
                                    <td>
                                        <form action="{{route('asignar.avisos', ['agenda' => $agenda->id])}}">
                                            <button style="margin-bottom: 8px"
                                                    class="btn-block btn btn-outline-primary">
                                                Abrir <i class="mdi mdi-folder-open"></i>
                                            </button>
                                        </form>
                                        <form action="{{route('admin.avisos.subir', ['agenda' => $agenda->id])}}">
                                            <button style="margin-bottom: 8px" class="btn btn-block btn-outline-info">
                                                Cargar <i class="mdi mdi-upload"></i>
                                            </button>
                                        </form>
                                        @if(!$agenda->flag)
                                            <form method="get" action="{{route('agenda.delete', $agenda->id)}}">
                                                <button style="margin-bottom: 8px"
                                                        class="btn-block btn btn-outline-danger btn-block">
                                                    Eliminar<i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{route('admin.agenda.download')}}">
                                            <input type="hidden" name="agenda" value="{{$agenda->id}}">
                                            <button style="margin-bottom: -8px"
                                                    class="btn btn-block btn-outline-success">
                                                Descargar <i class="mdi mdi-download"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $agendas->appends([])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
