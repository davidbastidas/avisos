<?php

namespace App\Http\Controllers;

use App\Usuarios;
use App\Delegacion;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
       if (isset(\Illuminate\Support\Facades\Auth::user()->id)){
         $usuarios = Usuarios::orderBy('nombre')->get();
         $delegaciones = Delegacion::all();
        return view('home', [
            'usuarios' => $usuarios,
            'delegaciones' => $delegaciones,
        ]);
       }else{
           return view('auth.login');
       }
     }
}
