<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'perfiles';

    function permisos()
    {
        return $this->belongsToMany('App\Menu', 'perfil_menu', 'id_perfil', 'id_menu')->withTimestamps();
    }
}
