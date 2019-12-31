<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $table = 'avisos_agenda';

    public function delegacion()
    {
        return $this->belongsTo('App\Delegacion', 'delegacion_id');
    }
}
