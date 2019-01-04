<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    function hijos(){
        return $this->hasMany('App\Menu', 'padre');
    }

    function getHijos($padre){
        $hijos = Menu::where('padre', $padre)->get();
        return $hijos;
    }
}
