<?php

namespace App\Http\Controllers;

use App\Agenda;
use App\Avisos;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UploadExcelController extends Controller
{
    public function index($id_agenda)
    {
        $agenda = Agenda::where('id', $id_agenda)->first();

        $fecha = explode(' ', $agenda->fecha)[0];

        $id = Session::get('adminId');
        $name = Session::get('adminName');

        return view('agenda.upload', ['id' => $id, 'name' => $name, 'agenda' => $agenda, 'fecha' =>$fecha]);
    }

}
