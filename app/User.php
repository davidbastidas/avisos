<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function organismo()
    {
        return $this->belongsTo('App\Organismo', 'id_organismo');
    }

    public function perfiles()
    {
        return $this->belongsToMany('App\Perfil', 'usuario_perfil', 'id_usuario', 'id_perfil')->withTimestamps();
    }

    public function listaPerfiles()
    {
        $perfiles = $this->perfiles->pluck('nombre')->toArray();
        return implode(', ', $perfiles);
    }

    public function listaPermisos()
    {
        $permisos = collect();
        foreach ($this->perfiles as $perfil) {
            foreach ($perfil->permisos as $permiso) {
                $permisos->push($permiso->ruta);
            }
        }

        return $permisos;
    }

    public function autorizarPerfil($permisos, $showError = false)
    {
        if ($this->role == 'admin' || $this->tieneCualquierPermiso($permisos)) {
            return true;
        }

        if ($showError) {
            abort(403, 'This action is unauthorized.');
        }

        return false;
    }

    public function tieneCualquierPermiso($permisos)
    {
        foreach ($permisos as $permiso) {
            if ($this->tienePermiso($permiso)) {
                return true;
            }
        }

        return false;
    }

    public function tienePermiso($etiqueta)
    {
        $permisos = $this->listaPermisos();

        if ($permisos->contains($etiqueta)) {
            return true;
        }

        return false;
    }
}
