<?php

namespace App\Http\Controllers;

use App\Avisos;
use App\Delegacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class DownloadController extends Controller
{
    public $avisos = null;


    public function download(Request $request)
    {
        $agenda = $request->agenda;

        $model = new Avisos();
        $avisos = $model->hydrate(
            DB::select(
                "call download_avisos($agenda)"
            )
        );

        $this->avisos = $avisos;

        Excel::create('Avisos', function ($excel) {

            $avisos = $this->avisos;

            $excel->sheet('Avisos', function ($sheet) use ($avisos) {

                $sheet->fromArray($avisos);

            });

        })->export('xlsx');
    }
}
