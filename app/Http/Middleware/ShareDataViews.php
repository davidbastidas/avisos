<?php

namespace App\Http\Middleware;

use App\Menu;
use Closure;

class ShareDataViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $menus = Menu::all();
        $padres_hijos = [];

        foreach ($menus as $menu) {
            if ($menu->padre == null) {
                array_push($padres_hijos, (object)array(
                    'id' => $menu->id,
                    'nombre' => $menu->nombre,
                    'descripcion' => $menu->descripcion,
                    'ruta' => $menu->ruta,
                    'ruta_name' => $menu->ruta_name,
                    'icon' => $menu->icon,
                    'padre' => $menu->padre,
                    'hijos' => $menu->getHijos($menu->id)
                ));
            }
        }

        \View::share('menus', $padres_hijos);

        return $next($request);

    }
}
