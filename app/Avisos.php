<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avisos extends Model
{
    protected $table = 'avisos';

    public function usuario() {
      return $this->belongsTo('App\Usuarios', 'gestor_id', 'id');
    }

    public function resultado() {
      return $this->belongsTo('App\Resultados', 'resultado_id', 'id');
    }

    public function anomalia() {
      return $this->belongsTo('App\Anomalias', 'anomalia_id', 'id');
    }

    public function entidad() {
      return $this->belongsTo('App\EntidadesPagos', 'entidad_recaudo_id', 'id');
    }

    public function estado() {
      return $this->belongsTo('App\Estados', 'estado', 'id');
    }
}
